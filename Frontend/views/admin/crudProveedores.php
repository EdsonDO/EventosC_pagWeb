<?php
$proveedorModel = new Proveedor();
$alerta = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    try {
        if ($accion === 'crear') {
            $datos = [
                'nombre_empresa' => trim($_POST['nombre_empresa']),
                'nombre_contacto' => trim($_POST['nombre_contacto']),
                'direccion' => trim($_POST['direccion']),
                'telefono' => trim($_POST['telefono'])
            ];
            if ($proveedorModel->crearProveedor($datos))
                $alerta = ['tipo' => 'success', 'msj' => 'Proveedor registrado.'];
            else
                $alerta = ['tipo' => 'error', 'msj' => 'Error al registrar.'];
        } elseif ($accion === 'editar') {
            $id = intval($_POST['id_proveedor']);
            $datos = [
                'nombre_empresa' => trim($_POST['nombre_empresa']),
                'nombre_contacto' => trim($_POST['nombre_contacto']),
                'direccion' => trim($_POST['direccion']),
                'telefono' => trim($_POST['telefono'])
            ];
            if ($proveedorModel->actualizarProveedor($id, $datos))
                $alerta = ['tipo' => 'success', 'msj' => 'Proveedor actualizado.'];
            else
                $alerta = ['tipo' => 'error', 'msj' => 'Error al actualizar.'];
        } elseif ($accion === 'eliminar') {
            $id = intval($_POST['id_eliminar']);
            if ($proveedorModel->eliminarProveedor($id))
                $alerta = ['tipo' => 'success', 'msj' => 'Proveedor eliminado.'];
            else
                $alerta = ['tipo' => 'error', 'msj' => 'No se puede eliminar: Tiene servicios asociados.'];
        }
    } catch (Exception $e) {
        $alerta = ['tipo' => 'error', 'msj' => $e->getMessage()];
    }
}

$proveedores = $proveedorModel->listarProveedores();
require_once 'Frontend/views/layouts/admin_header.php';
?>

<title>Proveedores | Panel Admin</title>

<style>
    .static-card {
        background-color: #030303 !important;
        backdrop-filter: none !important;
        cursor: default !important;
    }

    .static-card:hover {
        transform: none !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5) !important;
        border-color: rgba(245, 158, 11, 0.3) !important;
    }
</style>

<div class="admin-container animate-fade-in">

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-brand text-white flex items-center gap-3">
                <span
                    class="p-2 bg-amber-600/20 rounded-lg text-amber-400 border border-amber-500/30 shadow-[0_0_15px_rgba(245,158,11,0.3)]">
                    <span class="material-symbols-rounded" style="font-size: 32px;">local_shipping</span>
                </span>
                RED DE PROVEEDORES
            </h1>
            <p class="text-gray-400 mt-2 font-light text-sm pl-1">Gestión de socios estratégicos y logística.</p>
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

    <div class="module-card border-amber mb-12 static-card">
        <span class="material-symbols-rounded module-bg-icon text-amber-500/10">inventory_2</span>

        <div class="card-content">
            <div class="mb-6 flex justify-between items-center border-b border-gray-800 pb-4">
                <h2 id="form-title" class="text-lg font-brand text-white flex items-center gap-2">
                    <span class="text-amber-500 material-symbols-rounded">add_business</span> NUEVO SOCIO
                </h2>
                <button type="button" id="btn-cancelar" onclick="cancelarEdicion()"
                    class="hidden text-xs text-gray-400 hover:text-white uppercase font-bold flex items-center gap-1 transition-colors">
                    <span class="material-symbols-rounded text-sm">close</span> Cancelar
                </button>
            </div>

            <form method="POST" id="form-proveedor" class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <input type="hidden" name="accion" id="input-accion" value="crear">
                <input type="hidden" name="id_proveedor" id="input-id">

                <div class="md:col-span-6 flex flex-col justify-end space-y-2">
                    <label class="text-[10px] font-bold text-amber-400 uppercase tracking-widest ml-1">Nombre
                        Empresa</label>
                    <div class="relative group">
                        <span
                            class="absolute left-3 top-3 text-gray-500 group-focus-within:text-amber-500 transition-colors material-symbols-rounded text-xl">domain</span>
                        <input type="text" name="nombre_empresa" id="input-empresa" required
                            class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white pl-10 pr-4 py-3 rounded-lg focus:border-amber-500 focus:shadow-[0_0_15px_rgba(245,158,11,0.3)] outline-none transition-all placeholder-gray-700"
                            placeholder="Ej. Distribuidora Central SAC">
                    </div>
                </div>

                <div class="md:col-span-6 flex flex-col justify-end space-y-2">
                    <label class="text-[10px] font-bold text-amber-400 uppercase tracking-widest ml-1">Contacto
                        Principal</label>
                    <div class="relative group">
                        <span
                            class="absolute left-3 top-3 text-gray-500 group-focus-within:text-amber-500 transition-colors material-symbols-rounded text-xl">person</span>
                        <input type="text" name="nombre_contacto" id="input-contacto" required
                            class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white pl-10 pr-4 py-3 rounded-lg focus:border-amber-500 focus:shadow-[0_0_15px_rgba(245,158,11,0.3)] outline-none transition-all placeholder-gray-700"
                            placeholder="Ej. Roberto Gómez">
                    </div>
                </div>

                <div class="md:col-span-8 flex flex-col justify-end space-y-2">
                    <label class="text-[10px] font-bold text-amber-400 uppercase tracking-widest ml-1">Dirección
                        Fiscal</label>
                    <div class="relative group">
                        <span
                            class="absolute left-3 top-3 text-gray-500 group-focus-within:text-amber-500 transition-colors material-symbols-rounded text-xl">map</span>
                        <input type="text" name="direccion" id="input-direccion" required
                            class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white pl-10 pr-4 py-3 rounded-lg focus:border-amber-500 focus:shadow-[0_0_15px_rgba(245,158,11,0.3)] outline-none transition-all placeholder-gray-700"
                            placeholder="Av. Los Industriales 123">
                    </div>
                </div>

                <div class="md:col-span-4 flex flex-col justify-end space-y-2">
                    <label class="text-[10px] font-bold text-amber-400 uppercase tracking-widest ml-1">Teléfono</label>
                    <div class="relative group">
                        <span
                            class="absolute left-3 top-3 text-gray-500 group-focus-within:text-amber-500 transition-colors material-symbols-rounded text-xl">call</span>
                        <input type="text" name="telefono" id="input-telefono" required
                            class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white pl-10 pr-4 py-3 rounded-lg focus:border-amber-500 focus:shadow-[0_0_15px_rgba(245,158,11,0.3)] outline-none transition-all placeholder-gray-700"
                            placeholder="999 000 000">
                    </div>
                </div>

                <div class="md:col-span-12 flex justify-end pt-4 mt-2 border-t border-white/5">
                    <button type="submit" id="btn-submit"
                        class="bg-amber-600 hover:bg-amber-500 hover:scale-[1.02] active:scale-95 text-black px-8 py-3 rounded-lg font-bold uppercase transition-all shadow-lg shadow-amber-900/40 flex items-center gap-2">
                        <span class="material-symbols-rounded">save</span>
                        <span id="btn-text">GUARDAR SOCIO</span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <?php if (empty($proveedores)): ?>
        <div class="text-center py-20 border border-dashed border-gray-800 rounded-2xl bg-black/20">
            <span class="material-symbols-rounded text-gray-700 mb-4" style="font-size: 64px;">no_accounts</span>
            <h3 class="text-xl text-gray-500 font-brand">SIN PROVEEDORES</h3>
            <p class="text-gray-600 text-sm mt-2">No hay socios comerciales registrados.</p>
        </div>
    <?php else: ?>
        <div class="modules-grid">
            <?php foreach ($proveedores as $p): ?>
                <div class="module-card group">
                    <span class="material-symbols-rounded module-bg-icon">local_shipping</span>

                    <div class="card-content flex flex-col h-full">
                        <div class="flex justify-between items-start mb-6">
                            <div
                                class="icon-box bg-amber-500/10 border border-amber-500/30 text-amber-400 group-hover:bg-amber-600 group-hover:text-black group-hover:shadow-[0_0_15px_rgba(245,158,11,0.6)]">
                                <span
                                    class="font-bold text-lg"><?php echo strtoupper(substr($p['nombre_empresa'], 0, 1)); ?></span>
                            </div>
                            <span class="text-gray-600 font-mono text-xs tracking-widest">ID
                                #<?php echo str_pad($p['id_proveedor'], 3, '0', STR_PAD_LEFT); ?></span>
                        </div>

                        <div class="mb-4">
                            <h3
                                class="text-lg text-white font-bold mb-1 truncate tracking-wide group-hover:text-amber-400 transition-colors">
                                <?php echo htmlspecialchars($p['nombre_empresa']); ?>
                            </h3>
                            <div class="flex items-center gap-2 text-gray-500 text-sm">
                                <span class="material-symbols-rounded text-[16px]">person</span>
                                <span class="truncate"><?php echo htmlspecialchars($p['nombre_contacto']); ?></span>
                            </div>
                        </div>

                        <div
                            class="bg-black/40 rounded-lg p-3 space-y-2 mb-6 border border-white/5 group-hover:border-amber-500/20 transition-colors mt-auto">
                            <div class="flex items-center gap-2 text-xs text-gray-400">
                                <span class="material-symbols-rounded text-amber-500" style="font-size: 16px;">call</span>
                                <?php echo htmlspecialchars($p['telefono']); ?>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-400">
                                <span class="material-symbols-rounded text-amber-500" style="font-size: 16px;">map</span>
                                <span class="truncate"><?php echo htmlspecialchars($p['direccion']); ?></span>
                            </div>
                        </div>

                        <div class="flex gap-2 pt-4 border-t border-white/5">
                            <button onclick='editarProveedor(<?php echo json_encode($p); ?>)'
                                class="flex-1 py-2 rounded-lg bg-gray-800/50 hover:bg-amber-500 hover:text-black text-gray-400 transition-all font-bold text-[10px] uppercase tracking-wider flex justify-center items-center gap-2 border border-transparent hover:border-amber-400">
                                <span class="material-symbols-rounded text-sm">edit</span> Editar
                            </button>

                            <form method="POST"
                                onsubmit="return confirm('⚠️ ¿ELIMINAR PROVEEDOR?\n\n🏢 <?php echo addslashes($p['nombre_empresa']); ?>\n\nEsta acción es irreversible.');">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="id_eliminar" value="<?php echo $p['id_proveedor']; ?>">
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

<script>
    function editarProveedor(data) {
        window.scrollTo({ top: 0, behavior: 'smooth' });

        document.getElementById('input-accion').value = 'editar';
        document.getElementById('input-id').value = data.id_proveedor;
        document.getElementById('input-empresa').value = data.nombre_empresa;
        document.getElementById('input-contacto').value = data.nombre_contacto;
        document.getElementById('input-direccion').value = data.direccion;
        document.getElementById('input-telefono').value = data.telefono;

        const title = document.getElementById('form-title');
        title.innerHTML = '<span class="material-symbols-rounded text-green-400">edit_square</span> EDITANDO SOCIO';
        title.classList.add('text-green-400');

        const btn = document.getElementById('btn-submit');
        document.getElementById('btn-text').textContent = 'ACTUALIZAR DATOS';
        btn.querySelector('.material-symbols-rounded').textContent = 'sync';

        btn.classList.remove('bg-amber-600', 'hover:bg-amber-500', 'shadow-amber-900/40', 'text-black');
        btn.classList.add('bg-green-600', 'hover:bg-green-500', 'shadow-green-900/40', 'text-white');

        document.getElementById('btn-cancelar').classList.remove('hidden');
    }

    function cancelarEdicion() {
        document.getElementById('form-proveedor').reset();
        document.getElementById('input-accion').value = 'crear';
        document.getElementById('input-id').value = '';

        const title = document.getElementById('form-title');
        title.innerHTML = '<span class="text-amber-500 material-symbols-rounded">add_business</span> NUEVO SOCIO';
        title.classList.remove('text-green-400');

        const btn = document.getElementById('btn-submit');
        document.getElementById('btn-text').textContent = 'GUARDAR SOCIO';
        btn.querySelector('.material-symbols-rounded').textContent = 'save';

        btn.classList.remove('bg-green-600', 'hover:bg-green-500', 'shadow-green-900/40', 'text-white');
        btn.classList.add('bg-amber-600', 'hover:bg-amber-500', 'shadow-amber-900/40', 'text-black');

        document.getElementById('btn-cancelar').classList.add('hidden');
    }
</script>

<?php require_once 'Frontend/views/layouts/admin_footer.php'; ?>