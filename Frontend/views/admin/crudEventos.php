<?php
$conexion = (new Conexion())->iniciar();
$sedeModel = new Sede();
$clienteModel = new Cliente();

$sedes = $sedeModel->listarSedes();
$clientes = $clienteModel->listarClientes();
$alerta = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    try {
        $conexion->beginTransaction();

        if ($accion === 'crear') {
            $sql = "INSERT INTO evento (id_cliente, id_sede, nombre_evento, fecha_evento, hora_inicio, hora_fin, estado) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                $_POST['id_cliente'],
                $_POST['id_sede'],
                trim($_POST['nombre_evento']),
                $_POST['fecha_evento'],
                $_POST['hora_inicio'],
                $_POST['hora_fin'],
                $_POST['estado_evento']
            ]);
            $idEvento = $conexion->lastInsertId();

            $costo = floatval($_POST['costo_total']);
            $pagado = floatval($_POST['monto_pagado']);
            $estadoPago = ($pagado >= $costo) ? 'Pagado' : (($pagado > 0) ? 'Parcial' : 'Pendiente');

            $sqlRes = "INSERT INTO reserva (id_evento, fecha_reserva, costo_total, monto_pagado, estado_pago) 
                       VALUES (?, CURDATE(), ?, ?, ?)";
            $conexion->prepare($sqlRes)->execute([$idEvento, $costo, $pagado, $estadoPago]);

            $alerta = ['tipo' => 'success', 'msj' => 'Evento programado exitosamente.'];
        } elseif ($accion === 'editar') {
            $sql = "UPDATE evento SET id_sede=?, nombre_evento=?, fecha_evento=?, hora_inicio=?, hora_fin=?, estado=? WHERE id_evento=?";
            $conexion->prepare($sql)->execute([
                $_POST['id_sede'],
                trim($_POST['nombre_evento']),
                $_POST['fecha_evento'],
                $_POST['hora_inicio'],
                $_POST['hora_fin'],
                $_POST['estado_evento'],
                $_POST['id_evento']
            ]);

            $costo = floatval($_POST['costo_total']);
            $pagado = floatval($_POST['monto_pagado']);
            $estadoPago = ($pagado >= $costo) ? 'Pagado' : (($pagado > 0) ? 'Parcial' : 'Pendiente');

            $sqlRes = "UPDATE reserva SET costo_total=?, monto_pagado=?, estado_pago=? WHERE id_reserva=?";
            $conexion->prepare($sqlRes)->execute([$costo, $pagado, $estadoPago, $_POST['id_reserva']]);

            $alerta = ['tipo' => 'success', 'msj' => 'Evento actualizado.'];
        } elseif ($accion === 'eliminar') {
            $idEv = $_POST['id_eliminar'];
            $idRes = $_POST['id_reserva_eliminar'];

            $conexion->prepare("DELETE FROM detalle_recurso WHERE id_evento = ?")->execute([$idEv]);
            $conexion->prepare("DELETE FROM detalle_servicio WHERE id_evento = ?")->execute([$idEv]);
            $conexion->prepare("DELETE FROM reserva WHERE id_reserva = ?")->execute([$idRes]);
            $conexion->prepare("DELETE FROM evento WHERE id_evento = ?")->execute([$idEv]);

            $alerta = ['tipo' => 'success', 'msj' => 'Evento y datos asociados eliminados.'];
        }
        $conexion->commit();
    } catch (Exception $e) {
        $conexion->rollBack();
        $alerta = ['tipo' => 'error', 'msj' => 'Error: ' . $e->getMessage()];
    }
}

$sqlList = "SELECT e.*, r.id_reserva, r.costo_total, r.monto_pagado, r.estado_pago, 
            u.nombre_completo as cliente, s.nombre as sede 
            FROM evento e 
            INNER JOIN reserva r ON e.id_evento = r.id_evento
            INNER JOIN usuario u ON e.id_cliente = u.id_usuario
            INNER JOIN sede s ON e.id_sede = s.id_sede
            ORDER BY e.fecha_evento DESC";
$eventos = $conexion->query($sqlList)->fetchAll(PDO::FETCH_ASSOC);

require_once 'Frontend/views/layouts/admin_header.php';
?>

<title>Eventos | Panel Admin</title>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(5px);
        z-index: 50;
        display: none;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.2s ease-out;
    }

    .modal-content {
        background: #050505;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.9);
        border-radius: 16px;
        width: 100%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes slideUp {
        from {
            transform: translateY(50px) scale(0.95);
            opacity: 0;
        }

        to {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }
</style>

<div class="admin-container animate-fade-in">

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-brand text-white flex items-center gap-3">
                <span
                    class="p-2 bg-indigo-600/20 rounded-lg text-indigo-400 border border-indigo-500/30 shadow-[0_0_15px_rgba(99,102,241,0.3)]">
                    <span class="material-symbols-rounded" style="font-size: 32px;">calendar_month</span>
                </span>
                GESTIÓN DE EVENTOS
            </h1>
            <p class="text-gray-400 mt-2 font-light text-sm pl-1">Cronograma, pagos y organización general.</p>
        </div>

        <div class="flex gap-4">
            <a href="index.php?view=dashboard" class="nav-btn group">
                <span class="material-symbols-rounded group-hover:-translate-x-1 transition-transform">arrow_back</span>
                VOLVER
            </a>
            <button onclick="abrirModal('crear')"
                class="bg-indigo-600 hover:bg-indigo-500 hover:scale-105 active:scale-95 text-white px-6 py-2 rounded-lg font-bold uppercase transition-all shadow-lg shadow-indigo-900/40 flex items-center gap-2">
                <span class="material-symbols-rounded">event_available</span> NUEVO EVENTO
            </button>
        </div>
    </div>

    <?php if ($alerta): ?>
        <div
            class="mb-8 p-4 rounded-xl flex items-center gap-4 border backdrop-blur-md <?php echo $alerta['tipo'] === 'success' ? 'bg-green-900/30 border-green-500/50 text-green-400 shadow-[0_0_20px_rgba(34,197,94,0.1)]' : 'bg-red-900/30 border-red-500/50 text-red-400 shadow-[0_0_20px_rgba(239,68,68,0.1)]'; ?>">
            <div
                class="p-2 rounded-full <?php echo $alerta['tipo'] === 'success' ? 'bg-green-500/20' : 'bg-red-500/20'; ?>">
                <span
                    class="material-symbols-rounded"><?php echo $alerta['tipo'] === 'success' ? 'check' : 'priority_high'; ?></span>
            </div>
            <span class="font-bold tracking-wide"><?php echo $alerta['msj']; ?></span>
        </div>
    <?php endif; ?>

    <?php if (empty($eventos)): ?>
        <div class="text-center py-24 border border-dashed border-gray-800 rounded-2xl bg-black/20">
            <span class="material-symbols-rounded text-gray-700 mb-4" style="font-size: 64px;">event_busy</span>
            <h3 class="text-xl text-gray-500 font-brand">AGENDA VACÍA</h3>
            <p class="text-gray-600 text-sm mt-2">No hay eventos programados en el sistema.</p>
        </div>
    <?php else: ?>
        <div class="modules-grid">
            <?php foreach ($eventos as $ev):
                $stPago = $ev['estado_pago'];
                $colorPago = ($stPago == 'Pagado') ? 'text-green-400' : (($stPago == 'Parcial') ? 'text-amber-400' : 'text-red-400');
                $borderEstado = ($ev['estado'] == 'Confirmado') ? 'border-green-500/30 text-green-400 bg-green-500/10' : (($ev['estado'] == 'Cancelado') ? 'border-red-500/30 text-red-400 bg-red-500/10' : 'border-gray-600 text-gray-400 bg-gray-800/30');
                ?>
                <div class="module-card group">
                    <span class="material-symbols-rounded module-bg-icon">event</span>

                    <div class="card-content flex flex-col h-full">
                        <div class="flex justify-between items-start mb-4">
                            <div
                                class="p-2 rounded-lg bg-indigo-500/10 text-indigo-400 border border-indigo-500/30 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <div class="text-xs uppercase font-bold text-center leading-none">
                                    <?php echo strtoupper(date('M', strtotime($ev['fecha_evento']))); ?>
                                </div>
                                <div class="text-xl font-bold text-center leading-none mt-1">
                                    <?php echo date('d', strtotime($ev['fecha_evento'])); ?>
                                </div>
                            </div>
                            <span
                                class="px-3 py-1 rounded-full text-[10px] uppercase font-bold border <?php echo $borderEstado; ?>">
                                <?php echo $ev['estado']; ?>
                            </span>
                        </div>

                        <div class="mb-4">
                            <h3
                                class="text-lg text-white font-brand mb-1 truncate leading-tight group-hover:text-indigo-400 transition-colors">
                                <?php echo htmlspecialchars($ev['nombre_evento']); ?>
                            </h3>
                            <div class="flex items-center gap-1 text-xs text-indigo-300/70 font-bold uppercase tracking-wide">
                                <span class="material-symbols-rounded" style="font-size: 14px;">person</span>
                                <?php echo htmlspecialchars($ev['cliente']); ?>
                            </div>
                        </div>

                        <div class="bg-black/40 rounded-lg p-3 space-y-2 mb-4 border border-white/5">
                            <div class="flex items-center gap-2 text-xs text-gray-400">
                                <span class="material-symbols-rounded text-indigo-500" style="font-size: 16px;">schedule</span>
                                <?php echo substr($ev['hora_inicio'], 0, 5) . ' - ' . substr($ev['hora_fin'], 0, 5); ?>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-400">
                                <span class="material-symbols-rounded text-indigo-500"
                                    style="font-size: 16px;">location_on</span>
                                <span class="truncate"><?php echo htmlspecialchars($ev['sede']); ?></span>
                            </div>
                        </div>

                        <div class="flex justify-between items-end mb-6 mt-auto px-1 border-t border-white/5 pt-3">
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase">Costo Total</p>
                                <span class="text-white font-mono font-bold">S/
                                    <?php echo number_format($ev['costo_total'], 2); ?></span>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-gray-500 uppercase">Estado Pago</p>
                                <span
                                    class="text-xs font-bold uppercase <?php echo $colorPago; ?>"><?php echo $stPago; ?></span>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button onclick='abrirModal("editar", <?php echo json_encode($ev); ?>)'
                                class="flex-1 py-2 rounded-lg bg-gray-800/50 hover:bg-indigo-600 hover:text-white text-gray-400 transition-all font-bold text-[10px] uppercase tracking-wider flex justify-center items-center gap-2 border border-transparent hover:border-indigo-400">
                                <span class="material-symbols-rounded text-sm">edit</span> EDITAR
                            </button>

                            <form method="POST"
                                onsubmit="return confirm('⚠️ ¿ELIMINAR EVENTO?\n\nEsta acción eliminará también la reserva asociada.');">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="id_eliminar" value="<?php echo $ev['id_evento']; ?>">
                                <input type="hidden" name="id_reserva_eliminar" value="<?php echo $ev['id_reserva']; ?>">
                                <button type="submit"
                                    class="w-[36px] h-[36px] flex items-center justify-center rounded-lg bg-gray-800/50 hover:bg-red-500/20 hover:text-red-500 hover:border-red-500/50 border border-transparent text-gray-500 transition-all">
                                    <span class="material-symbols-rounded text-lg">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div id="modal" class="modal-overlay">
    <div class="modal-content">
        <div class="flex justify-between items-center p-6 border-b border-white/10 bg-white/5">
            <h2 id="modal-titulo" class="text-xl font-brand text-white flex items-center gap-3">
                <span class="text-indigo-500 material-symbols-rounded">event</span> NUEVO EVENTO
            </h2>
            <button onclick="cerrarModal()" class="text-gray-500 hover:text-white transition-colors">
                <span class="material-symbols-rounded" style="font-size: 28px;">close</span>
            </button>
        </div>

        <form method="POST" id="form-evento" class="p-6 space-y-6">
            <input type="hidden" name="accion" id="input-accion" value="crear">
            <input type="hidden" name="id_evento" id="input-id">
            <input type="hidden" name="id_reserva" id="input-id-reserva">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest ml-1">Cliente</label>
                    <div class="relative group">
                        <span class="absolute left-3 top-3 text-gray-500 material-symbols-rounded">person</span>
                        <select name="id_cliente" id="input-cliente" required
                            class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white pl-10 pr-4 py-3 rounded-lg focus:border-indigo-500 outline-none appearance-none">
                            <option value="">Seleccionar Cliente...</option>
                            <?php foreach ($clientes as $c): ?>
                                <option value="<?php echo $c['id_usuario']; ?>"><?php echo $c['nombre_completo']; ?>
                                </option><?php endforeach; ?>
                        </select>
                        <span
                            class="absolute right-3 top-3 text-gray-500 material-symbols-rounded pointer-events-none">expand_more</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest ml-1">Sede</label>
                    <div class="relative group">
                        <span class="absolute left-3 top-3 text-gray-500 material-symbols-rounded">apartment</span>
                        <select name="id_sede" id="input-sede" required
                            class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white pl-10 pr-4 py-3 rounded-lg focus:border-indigo-500 outline-none appearance-none">
                            <?php foreach ($sedes as $s): ?>
                                <option value="<?php echo $s['id_sede']; ?>"><?php echo $s['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span
                            class="absolute right-3 top-3 text-gray-500 material-symbols-rounded pointer-events-none">expand_more</span>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest ml-1">Nombre del
                    Evento</label>
                <div class="relative group">
                    <span class="absolute left-3 top-3 text-gray-500 material-symbols-rounded">celebration</span>
                    <input type="text" name="nombre_evento" id="input-nombre" required
                        class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white pl-10 pr-4 py-3 rounded-lg focus:border-indigo-500 outline-none placeholder-gray-700"
                        placeholder="Ej. Boda Civil Familia Pérez">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest ml-1">Fecha</label>
                    <input type="date" name="fecha_evento" id="input-fecha" required
                        class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white px-4 py-3 rounded-lg focus:border-indigo-500 outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest ml-1">Inicio</label>
                    <input type="time" name="hora_inicio" id="input-inicio" required
                        class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white px-4 py-3 rounded-lg focus:border-indigo-500 outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest ml-1">Fin</label>
                    <input type="time" name="hora_fin" id="input-fin" required
                        class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white px-4 py-3 rounded-lg focus:border-indigo-500 outline-none">
                </div>
            </div>

            <div class="p-4 bg-indigo-900/10 rounded-xl border border-indigo-500/20">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Costo Total (S/)</label>
                        <input type="number" step="0.01" name="costo_total" id="input-costo" required
                            class="w-full bg-black/50 border border-indigo-500/30 text-white p-2 rounded-lg font-mono focus:border-indigo-500 outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">A Cuenta (S/)</label>
                        <input type="number" step="0.01" name="monto_pagado" id="input-pagado" required
                            class="w-full bg-black/50 border border-indigo-500/30 text-white p-2 rounded-lg font-mono focus:border-indigo-500 outline-none">
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest ml-1">Estado del
                    Evento</label>
                <select name="estado_evento" id="input-estado"
                    class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white px-4 py-3 rounded-lg focus:border-indigo-500 outline-none">
                    <option value="Confirmado">Confirmado</option>
                    <option value="Borrador">Borrador</option>
                    <option value="Cancelado">Cancelado</option>
                </select>
            </div>

            <div class="pt-4 border-t border-white/5 flex justify-end">
                <button type="submit" id="btn-submit"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-3 rounded-lg font-bold uppercase transition-all shadow-lg shadow-indigo-900/40 flex items-center gap-2">
                    <span class="material-symbols-rounded">save</span> GUARDAR DATOS
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModal(modo, data = null) {
        const modal = document.getElementById('modal');
        const titulo = document.getElementById('modal-titulo');
        const accion = document.getElementById('input-accion');
        const form = document.getElementById('form-evento');
        const btn = document.getElementById('btn-submit');

        form.reset();
        modal.style.display = 'flex';

        if (modo === 'editar' && data) {
            titulo.innerHTML = '<span class="text-green-400 material-symbols-rounded">edit_calendar</span> EDITAR EVENTO';
            titulo.classList.add('text-green-400');
            accion.value = 'editar';

            document.getElementById('input-id').value = data.id_evento;
            document.getElementById('input-id-reserva').value = data.id_reserva;
            document.getElementById('input-cliente').value = data.id_cliente;
            document.getElementById('input-sede').value = data.id_sede;
            document.getElementById('input-nombre').value = data.nombre_evento;
            document.getElementById('input-fecha').value = data.fecha_evento;
            document.getElementById('input-inicio').value = data.hora_inicio;
            document.getElementById('input-fin').value = data.hora_fin;
            document.getElementById('input-costo').value = data.costo_total;
            document.getElementById('input-pagado').value = data.monto_pagado;
            document.getElementById('input-estado').value = data.estado;

            btn.innerHTML = '<span class="material-symbols-rounded">sync</span> ACTUALIZAR';
            btn.classList.remove('bg-indigo-600', 'hover:bg-indigo-500', 'shadow-indigo-900/40');
            btn.classList.add('bg-green-600', 'hover:bg-green-500', 'shadow-green-900/40');

        } else {
            titulo.innerHTML = '<span class="text-indigo-500 material-symbols-rounded">event_available</span> NUEVO EVENTO';
            titulo.classList.remove('text-green-400');
            accion.value = 'crear';

            btn.innerHTML = '<span class="material-symbols-rounded">save</span> GUARDAR EVENTO';
            btn.classList.add('bg-indigo-600', 'hover:bg-indigo-500', 'shadow-indigo-900/40');
            btn.classList.remove('bg-green-600', 'hover:bg-green-500', 'shadow-green-900/40');
        }
    }

    function cerrarModal() {
        document.getElementById('modal').style.display = 'none';
    }

    document.getElementById('modal').addEventListener('click', function (e) {
        if (e.target === this) cerrarModal();
    });
</script>

<?php require_once 'Frontend/views/layouts/admin_footer.php'; ?>