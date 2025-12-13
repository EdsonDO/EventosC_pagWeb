<?php
$servicioModel = new Servicio();
$proveedorModel = new Proveedor();
$alerta = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    try {
        if ($accion === 'crear') {
            $datos = [
                'nombre_servicio' => trim($_POST['nombre_servicio']),
                'id_proveedor' => intval($_POST['id_proveedor']),
                'costo' => floatval($_POST['costo'])
            ];
            if ($servicioModel->crearServicio($datos))
                $alerta = ['tipo' => 'success', 'msj' => 'Servicio añadido al catálogo'];
            else
                $alerta = ['tipo' => 'error', 'msj' => 'Error al registrar el servicio.'];
        } elseif ($accion === 'editar') {
            $id = intval($_POST['id_servicio']);
            $datos = [
                'nombre_servicio' => trim($_POST['nombre_servicio']),
                'id_proveedor' => intval($_POST['id_proveedor']),
                'costo' => floatval($_POST['costo'])
            ];
            if ($servicioModel->actualizarServicio($id, $datos))
                $alerta = ['tipo' => 'success', 'msj' => 'Servicio actualizado correctamente'];
            else
                $alerta = ['tipo' => 'error', 'msj' => 'No se pudieron guardar los cambios'];
        } elseif ($accion === 'eliminar') {
            $id = intval($_POST['id_eliminar']);
            if ($servicioModel->eliminarServicio($id))
                $alerta = ['tipo' => 'success', 'msj' => 'Servicio eliminado del catálogo'];
            else
                $alerta = ['tipo' => 'error', 'msj' => 'No se puede eliminar: Está en uso.'];
        }
    } catch (Exception $e) {
        $alerta = ['tipo' => 'error', 'msj' => 'Error: ' . $e->getMessage()];
    }
}

$servicios = $servicioModel->listarServicios();
$proveedores = $proveedorModel->listarProveedores();

require_once 'Frontend/views/layouts/admin_header.php';
?>

<title>Servicios | Panel Admin</title>

<div class="admin-container animate-fade-in">

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-brand text-white flex items-center gap-3">
                <span
                    class="p-2 bg-rose-600/20 rounded-lg text-rose-400 border border-rose-500/30 shadow-[0_0_15px_rgba(244,63,94,0.3)]">
                    <span class="material-symbols-rounded" style="font-size: 32px;">design_services</span>
                </span>
                CATÁLOGO DE SERVICIOS
            </h1>
            <p class="text-gray-400 mt-2 font-light text-sm pl-1">Administra la oferta de servicios y sus costos base.
            </p>
        </div>

        <a href="index.php?view=dashboard" class="nav-btn group">
            <span class="material-symbols-rounded group-hover:-translate-x-1 transition-transform">arrow_back</span>
            VOLVER AL DASHBOARD
        </a>
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

    <div class="static-card border-rose mb-8">
        <span class="material-symbols-rounded module-bg-icon text-rose-500/10">design_services</span>

        <div class="card-content">
            <div class="mb-6 flex justify-between items-center border-b border-gray-800 pb-4">
                <h2 id="form-title" class="text-lg font-brand text-white flex items-center gap-2">
                    <span class="text-rose-500 material-symbols-rounded">add_circle</span> NUEVO SERVICIO
                </h2>
                <button type="button" id="btn-cancelar" onclick="cancelarEdicion()"
                    class="hidden text-xs text-gray-400 hover:text-white uppercase font-bold flex items-center gap-1 transition-colors">
                    <span class="material-symbols-rounded text-sm">close</span> Cancelar
                </button>
            </div>

            <form method="POST" id="form-servicio" class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <input type="hidden" name="accion" id="input-accion" value="crear">
                <input type="hidden" name="id_servicio" id="input-id">

                <div class="md:col-span-5 flex flex-col justify-end space-y-2">
                    <label class="text-[10px] font-bold text-rose-400 uppercase tracking-widest ml-1">Nombre del
                        Servicio</label>
                    <div class="relative group">
                        <span
                            class="absolute left-3 top-3 text-gray-500 group-focus-within:text-rose-500 transition-colors material-symbols-rounded text-xl">edit_note</span>
                        <input type="text" name="nombre_servicio" id="input-nombre" required
                            class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white pl-10 pr-4 py-3 rounded-lg focus:border-rose-500 focus:shadow-[0_0_15px_rgba(244,63,94,0.3)] outline-none transition-all placeholder-gray-700"
                            placeholder="Ej. Decoración Floral">
                    </div>
                </div>

                <div class="md:col-span-4 flex flex-col justify-end space-y-2">
                    <label class="text-[10px] font-bold text-rose-400 uppercase tracking-widest ml-1">Proveedor</label>
                    <div class="relative group">
                        <span class="absolute left-3 top-3 text-gray-500 material-symbols-rounded">store</span>
                        <select name="id_proveedor" id="input-proveedor" required
                            class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white pl-10 pr-4 py-3 rounded-lg focus:border-rose-500 outline-none appearance-none cursor-pointer">
                            <option value="">-- Seleccionar --</option>
                            <?php foreach ($proveedores as $p): ?>
                                <option value="<?php echo $p['id_proveedor']; ?>">
                                    <?php echo htmlspecialchars($p['nombre_empresa']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span
                            class="absolute right-3 top-3 text-gray-500 material-symbols-rounded pointer-events-none">expand_more</span>
                    </div>
                </div>

                <div class="md:col-span-3 flex flex-col justify-end space-y-2">
                    <label class="text-[10px] font-bold text-rose-400 uppercase tracking-widest ml-1">Costo (S/)</label>
                    <div class="relative group">
                        <span
                            class="absolute left-3 top-3 text-gray-500 group-focus-within:text-rose-500 transition-colors material-symbols-rounded text-xl">attach_money</span>
                        <input type="number" step="0.01" name="costo" id="input-costo" required min="0"
                            class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white pl-10 pr-4 py-3 rounded-lg focus:border-rose-500 focus:shadow-[0_0_15px_rgba(244,63,94,0.3)] outline-none transition-all placeholder-gray-700 font-mono"
                            placeholder="0.00">
                    </div>
                </div>

                <div class="md:col-span-12 flex justify-end pt-4 mt-2 border-t border-white/5">
                    <button type="submit" id="btn-submit"
                        class="bg-rose-600 hover:bg-rose-500 hover:scale-[1.02] active:scale-95 text-white px-8 py-3 rounded-lg font-bold uppercase transition-all shadow-lg shadow-rose-900/40 flex items-center gap-2">
                        <span class="material-symbols-rounded">save</span>
                        <span id="btn-text">GUARDAR SERVICIO</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if (empty($servicios)): ?>
        <div class="text-center py-20 border border-dashed border-gray-800 rounded-2xl bg-black/20">
            <span class="material-symbols-rounded text-gray-700 mb-4" style="font-size: 64px;">design_services</span>
            <h3 class="text-xl text-gray-500 font-brand">CATÁLOGO VACÍO</h3>
            <p class="text-gray-600 text-sm mt-2">No hay servicios registrados.</p>
        </div>
    <?php else: ?>

        <div class="mb-4 flex items-center gap-2 text-gray-500 text-xs uppercase font-bold tracking-widest pl-2">
            <span class="material-symbols-rounded text-rose-500">swipe</span> Desliza para ver más servicios
        </div>

        <div class="carousel-wrapper">
            <button class="nav-arrow" onclick="scrollCarousel(-1)">
                <span class="material-symbols-rounded">chevron_left</span>
            </button>

            <div class="carousel-container" id="serviciosCarousel">
                <?php foreach ($servicios as $s): ?>
                    <div class="carousel-card group hover:border-rose-500/50 hover:shadow-[0_10px_30px_rgba(244,63,94,0.15)]">

                        <?php $bgHue = rand(330, 355); ?>
                        <div class="carousel-img"
                            style="background: linear-gradient(135deg, hsl(<?php echo $bgHue; ?>, 60%, 15%), hsl(<?php echo $bgHue; ?>, 60%, 5%));">
                            <span class="material-symbols-rounded"
                                style="font-size: 60px; color: rgba(244,63,94,0.1);">design_services</span>

                            <div class="carousel-badge">
                                <span class="material-symbols-rounded" style="font-size: 16px;">payments</span>
                                S/ <?php echo number_format($s['costo'], 2); ?>
                            </div>
                        </div>

                        <div class="carousel-body">
                            <h2 class="carousel-title"><?php echo htmlspecialchars($s['nombre_servicio']); ?></h2>

                            <div class="flex items-center gap-2 text-sm text-gray-400 mb-4">
                                <span class="material-symbols-rounded text-rose-500" style="font-size:18px;">store</span>
                                <span class="truncate"><?php echo htmlspecialchars($s['nombre_empresa'] ?? 'N/A'); ?></span>
                            </div>

                            <div class="bg-white/5 rounded p-2 mb-4 border border-white/5 flex justify-between">
                                <span class="text-[10px] text-gray-500 uppercase">Veces Usado</span>
                                <span class="text-xs font-bold text-white"><?php echo $s['veces_usado'] ?? 0; ?>x</span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 mt-auto">
                                <button onclick='editarServicio(<?php echo json_encode($s); ?>)'
                                    class="py-2 rounded bg-gray-800 hover:bg-rose-500 hover:text-white text-gray-300 font-bold text-xs uppercase transition-all flex justify-center items-center gap-1">
                                    <span class="material-symbols-rounded text-sm">edit</span> Editar
                                </button>

                                <form method="POST"
                                    onsubmit="return confirm('⚠️ ¿ELIMINAR SERVICIO?\n\n🛠️ <?php echo addslashes($s['nombre_servicio']); ?>\n\nEsta acción es irreversible.');"
                                    class="w-full">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <input type="hidden" name="id_eliminar" value="<?php echo $s['id_servicio']; ?>">
                                    <button type="submit"
                                        class="w-full py-2 rounded bg-gray-800 hover:bg-red-500 hover:text-white text-gray-300 font-bold text-xs uppercase transition-all flex justify-center items-center gap-1">
                                        <span class="material-symbols-rounded text-sm">delete</span> Borrar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button class="nav-arrow" onclick="scrollCarousel(1)">
                <span class="material-symbols-rounded">chevron_right</span>
            </button>
        </div>
    <?php endif; ?>
</div>

<script>
    function scrollCarousel(direction) {
        const container = document.getElementById('serviciosCarousel');
        const cardWidth = 300 + 30;
        const scrollAmount = cardWidth * 1;

        container.scrollBy({
            left: direction * scrollAmount,
            behavior: 'smooth'
        });
    }

    function editarServicio(data) {
        window.scrollTo({ top: 0, behavior: 'smooth' });

        document.getElementById('input-accion').value = 'editar';
        document.getElementById('input-id').value = data.id_servicio;
        document.getElementById('input-nombre').value = data.nombre_servicio;
        document.getElementById('input-proveedor').value = data.id_proveedor;
        document.getElementById('input-costo').value = data.costo;

        const title = document.getElementById('form-title');
        title.innerHTML = '<span class="material-symbols-rounded text-green-400">edit_note</span> EDITANDO SERVICIO';
        title.classList.add('text-green-400');

        const btn = document.getElementById('btn-submit');
        document.getElementById('btn-text').textContent = 'ACTUALIZAR DATOS';
        btn.querySelector('.material-symbols-rounded').textContent = 'sync';

        btn.classList.remove('bg-rose-600', 'hover:bg-rose-500', 'shadow-rose-900/40');
        btn.classList.add('bg-green-600', 'hover:bg-green-500', 'shadow-green-900/40');

        document.getElementById('btn-cancelar').classList.remove('hidden');
    }

    function cancelarEdicion() {
        document.getElementById('form-servicio').reset();
        document.getElementById('input-accion').value = 'crear';
        document.getElementById('input-id').value = '';

        const title = document.getElementById('form-title');
        title.innerHTML = '<span class="text-rose-500 material-symbols-rounded">add_circle</span> NUEVO SERVICIO';
        title.classList.remove('text-green-400');

        const btn = document.getElementById('btn-submit');
        document.getElementById('btn-text').textContent = 'GUARDAR SERVICIO';
        btn.querySelector('.material-symbols-rounded').textContent = 'save';

        btn.classList.remove('bg-green-600', 'hover:bg-green-500', 'shadow-green-900/40');
        btn.classList.add('bg-rose-600', 'hover:bg-rose-500', 'shadow-rose-900/40');

        document.getElementById('btn-cancelar').classList.add('hidden');
    }
</script>

<?php require_once 'Frontend/views/layouts/admin_footer.php'; ?>