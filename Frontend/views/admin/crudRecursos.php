<?php
$recursoModel = new Recurso();
$proveedorModel = new Proveedor();
$proveedores = $proveedorModel->listarProveedores();

$alerta = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    try {
        if ($accion === 'crear') {
            $datos = [
                'id_proveedor' => intval($_POST['id_proveedor']),
                'nombre_recurso' => trim($_POST['nombre']),
                'descripcion' => trim($_POST['descripcion']),
                'costounidad' => floatval($_POST['costo']),
                'stock' => intval($_POST['stock'])
            ];
            if ($recursoModel->crearRecurso($datos))
                $alerta = ['tipo' => 'success', 'msj' => 'Recurso añadido al inventario.'];
            else
                $alerta = ['tipo' => 'error', 'msj' => 'Error al crear recurso.'];
        } elseif ($accion === 'editar') {
            $id = intval($_POST['id_recurso']);
            $datos = [
                'id_proveedor' => intval($_POST['id_proveedor']),
                'nombre_recurso' => trim($_POST['nombre']),
                'descripcion' => trim($_POST['descripcion']),
                'costounidad' => floatval($_POST['costo']),
                'stock' => intval($_POST['stock'])
            ];
            if ($recursoModel->actualizarRecurso($id, $datos))
                $alerta = ['tipo' => 'success', 'msj' => 'Inventario actualizado.'];
            else
                $alerta = ['tipo' => 'error', 'msj' => 'Error al guardar.'];
        } elseif ($accion === 'eliminar') {
            $id = intval($_POST['id_eliminar']);
            if ($recursoModel->eliminarRecurso($id))
                $alerta = ['tipo' => 'success', 'msj' => 'Recurso eliminado.'];
            else
                $alerta = ['tipo' => 'error', 'msj' => 'No se puede eliminar: Está en uso.'];
        }
    } catch (Exception $e) {
        $alerta = ['tipo' => 'error', 'msj' => $e->getMessage()];
    }
}

$recursos = $recursoModel->listarRecursos();
require_once 'Frontend/views/layouts/admin_header.php';
?>

<title>Recursos | Panel Admin</title>

<style>
    .static-card {
        background-color: #030303 !important;
        backdrop-filter: none !important;
        cursor: default !important;
    }

    .static-card:hover {
        transform: none !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
</style>

<div class="admin-container animate-fade-in">

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-brand text-white flex items-center gap-3">
                <span
                    class="p-2 bg-cyan-600/20 rounded-lg text-cyan-400 border border-cyan-500/30 shadow-[0_0_15px_rgba(6,182,212,0.3)]">
                    <span class="material-symbols-rounded" style="font-size: 32px;">inventory_2</span>
                </span>
                INVENTARIO DE RECURSOS
            </h1>
            <p class="text-gray-400 mt-2 font-light text-sm pl-1">Control de stock, equipamiento y materiales.</p>
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

    <div class="module-card border-cyan mb-12 static-card">
        <span class="material-symbols-rounded module-bg-icon text-cyan-500/10">add_box</span>

        <div class="card-content">
            <div class="mb-6 flex justify-between items-center border-b border-gray-800 pb-4">
                <h2 id="form-title" class="text-lg font-brand text-white flex items-center gap-2">
                    <span class="text-cyan-500 material-symbols-rounded">add_circle</span> NUEVO ÍTEM
                </h2>
                <button type="button" id="btn-cancelar" onclick="cancelarEdicion()"
                    class="hidden text-xs text-gray-400 hover:text-white uppercase font-bold flex items-center gap-1 transition-colors">
                    <span class="material-symbols-rounded text-sm">close</span> Cancelar
                </button>
            </div>

            <form method="POST" id="form-recurso" class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <input type="hidden" name="accion" id="input-accion" value="crear">
                <input type="hidden" name="id_recurso" id="input-id">

                <div class="md:col-span-6 flex flex-col justify-end space-y-2">
                    <label class="text-[10px] font-bold text-cyan-400 uppercase tracking-widest ml-1">Nombre del
                        Recurso</label>
                    <div class="relative group">
                        <span
                            class="absolute left-3 top-3 text-gray-500 group-focus-within:text-cyan-500 transition-colors material-symbols-rounded text-xl">package_2</span>
                        <input type="text" name="nombre" id="input-nombre" required
                            class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white pl-10 pr-4 py-3 rounded-lg focus:border-cyan-500 focus:shadow-[0_0_15px_rgba(6,182,212,0.3)] outline-none transition-all placeholder-gray-700"
                            placeholder="Ej. Proyector HD">
                    </div>
                </div>

                <div class="md:col-span-6 flex flex-col justify-end space-y-2">
                    <label class="text-[10px] font-bold text-cyan-400 uppercase tracking-widest ml-1">Proveedor</label>
                    <div class="relative group">
                        <span
                            class="absolute left-3 top-3 text-gray-500 group-focus-within:text-cyan-500 transition-colors material-symbols-rounded text-xl">local_shipping</span>
                        <select name="id_proveedor" id="input-proveedor" required
                            class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white pl-10 pr-4 py-3 rounded-lg focus:border-cyan-500 focus:shadow-[0_0_15px_rgba(6,182,212,0.3)] outline-none transition-all placeholder-gray-700 appearance-none">
                            <option value="" disabled selected>Seleccione un proveedor...</option>
                            <?php foreach ($proveedores as $p): ?>
                                <option value="<?php echo $p['id_proveedor']; ?>">
                                    <?php echo htmlspecialchars($p['nombre_empresa']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span
                            class="absolute right-3 top-3 text-gray-500 pointer-events-none material-symbols-rounded">expand_more</span>
                    </div>
                </div>

                <div class="md:col-span-6 flex flex-col justify-end space-y-2">
                    <label
                        class="text-[10px] font-bold text-cyan-400 uppercase tracking-widest ml-1">Descripción</label>
                    <div class="relative group">
                        <span
                            class="absolute left-3 top-3 text-gray-500 group-focus-within:text-cyan-500 transition-colors material-symbols-rounded text-xl">description</span>
                        <input type="text" name="descripcion" id="input-descripcion"
                            class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white pl-10 pr-4 py-3 rounded-lg focus:border-cyan-500 focus:shadow-[0_0_15px_rgba(6,182,212,0.3)] outline-none transition-all placeholder-gray-700"
                            placeholder="Detalles breves del ítem...">
                    </div>
                </div>

                <div class="md:col-span-6 flex flex-col justify-end space-y-2">
                    <label class="text-[10px] font-bold text-cyan-400 uppercase tracking-widest ml-1">Costo Unitario
                        (S/)</label>
                    <div class="relative group">
                        <span
                            class="absolute left-3 top-3 text-gray-500 group-focus-within:text-cyan-500 transition-colors material-symbols-rounded text-xl">attach_money</span>
                        <input type="number" step="0.01" name="costo" id="input-costo" required min="0"
                            class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white pl-10 pr-4 py-3 rounded-lg focus:border-cyan-500 focus:shadow-[0_0_15px_rgba(6,182,212,0.3)] outline-none transition-all placeholder-gray-700 font-mono"
                            placeholder="0.00">
                    </div>
                </div>

                <div class="md:col-span-6 flex flex-col justify-end space-y-2">
                    <label class="text-[10px] font-bold text-cyan-400 uppercase tracking-widest ml-1">Stock
                        Inicial</label>
                    <div class="relative group">
                        <span
                            class="absolute left-3 top-3 text-gray-500 group-focus-within:text-cyan-500 transition-colors material-symbols-rounded text-xl">inventory</span>
                        <input type="number" name="stock" id="input-stock" required min="0"
                            class="w-full bg-[#0a0a0a] border border-[#2a2a2a] text-white pl-10 pr-4 py-3 rounded-lg focus:border-cyan-500 focus:shadow-[0_0_15px_rgba(6,182,212,0.3)] outline-none transition-all placeholder-gray-700 font-mono"
                            placeholder="0">
                    </div>
                </div>

                <div class="md:col-span-12 flex justify-end pt-4 mt-2 border-t border-white/5">
                    <button type="submit" id="btn-submit"
                        class="bg-cyan-600 hover:bg-cyan-500 hover:scale-[1.02] active:scale-95 text-black px-8 py-3 rounded-lg font-bold uppercase transition-all shadow-lg shadow-cyan-900/40 flex items-center gap-2">
                        <span class="material-symbols-rounded">save</span>
                        <span id="btn-text">GUARDAR ÍTEM</span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <?php if (empty($recursos)): ?>
        <div class="text-center py-20 border border-dashed border-gray-800 rounded-2xl bg-black/20">
            <span class="material-symbols-rounded text-gray-700 mb-4" style="font-size: 64px;">inbox</span>
            <h3 class="text-xl text-gray-500 font-brand">INVENTARIO VACÍO</h3>
            <p class="text-gray-600 text-sm mt-2">No hay recursos registrados.</p>
        </div>
    <?php else: ?>
        <div class="modules-grid">
            <?php foreach ($recursos as $r):
                $lowStock = $r['stock'] < 10;
                $borderColor = $lowStock ? 'border-red-500 shadow-[0_0_15px_rgba(239,68,68,0.2)]' : '';
                ?>
                <div class="module-card group <?php echo $borderColor; ?>">
                    <span class="material-symbols-rounded module-bg-icon">inventory_2</span>

                    <div class="card-content flex flex-col h-full">
                        <div class="flex justify-between items-start mb-6">
                            <div
                                class="icon-box bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 group-hover:bg-cyan-600 group-hover:text-black group-hover:shadow-[0_0_15px_rgba(6,182,212,0.6)]">
                                <span class="font-bold text-lg"><span class="material-symbols-rounded">package_2</span></span>
                            </div>

                            <?php if ($lowStock): ?>
                                <span
                                    class="bg-red-500/10 text-red-500 px-3 py-1 rounded-full text-[10px] font-bold border border-red-500/30 animate-pulse flex items-center gap-1">
                                    <span class="material-symbols-rounded text-xs">warning</span> STOCK BAJO
                                </span>
                            <?php else: ?>
                                <span class="text-gray-600 font-mono text-xs tracking-widest">ID
                                    #<?php echo str_pad($r['id_recurso'], 3, '0', STR_PAD_LEFT); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <h3
                                class="text-lg text-white font-bold mb-1 truncate tracking-wide group-hover:text-cyan-400 transition-colors">
                                <?php echo htmlspecialchars($r['nombre_recurso']); ?>
                            </h3>
                            <p class="text-sm text-gray-500 line-clamp-2 min-h-[40px]">
                                <?php echo htmlspecialchars($r['descripcion']); ?>
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-6 mt-auto">
                            <div
                                class="bg-black/40 p-3 rounded-lg border border-white/5 text-center group-hover:border-cyan-500/30 transition-colors">
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Costo Unit.</p>
                                <p class="text-cyan-400 font-mono text-lg leading-none">S/
                                    <?php echo number_format($r['costounidad'], 2); ?>
                                </p>
                            </div>
                            <div
                                class="bg-black/40 p-3 rounded-lg border border-white/5 text-center group-hover:border-cyan-500/30 transition-colors">
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Stock</p>
                                <p
                                    class="text-white font-mono text-lg leading-none <?php echo $lowStock ? 'text-red-500' : ''; ?>">
                                    <?php echo $r['stock']; ?>
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-2 pt-4 border-t border-white/5">
                            <button onclick='editarRecurso(<?php echo json_encode($r); ?>)'
                                class="flex-1 py-2 rounded-lg bg-gray-800/50 hover:bg-cyan-500 hover:text-black text-gray-400 transition-all font-bold text-[10px] uppercase tracking-wider flex justify-center items-center gap-2 border border-transparent hover:border-cyan-400">
                                <span class="material-symbols-rounded text-sm">edit</span> Editar
                            </button>

                            <form method="POST"
                                onsubmit="return confirm('⚠️ ¿ELIMINAR RECURSO?\n\n📦 <?php echo addslashes($r['nombre_recurso']); ?>\n\nEsta acción es irreversible.');">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="id_eliminar" value="<?php echo $r['id_recurso']; ?>">
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
    function editarRecurso(data) {
        window.scrollTo({ top: 0, behavior: 'smooth' });

        document.getElementById('input-accion').value = 'editar';
        document.getElementById('input-id').value = data.id_recurso;
        document.getElementById('input-proveedor').value = data.id_proveedor;
        document.getElementById('input-nombre').value = data.nombre_recurso;
        document.getElementById('input-descripcion').value = data.descripcion;
        document.getElementById('input-costo').value = data.costounidad;
        document.getElementById('input-stock').value = data.stock;

        const title = document.getElementById('form-title');
        title.innerHTML = '<span class="material-symbols-rounded text-green-400">edit_square</span> EDITANDO ÍTEM';
        title.classList.add('text-green-400');

        const btn = document.getElementById('btn-submit');
        document.getElementById('btn-text').textContent = 'ACTUALIZAR STOCK';
        btn.querySelector('.material-symbols-rounded').textContent = 'sync';

        btn.classList.remove('bg-cyan-600', 'hover:bg-cyan-500', 'shadow-cyan-900/40', 'text-black');
        btn.classList.add('bg-green-600', 'hover:bg-green-500', 'shadow-green-900/40', 'text-white');

        document.getElementById('btn-cancelar').classList.remove('hidden');
    }

    function cancelarEdicion() {
        document.getElementById('form-recurso').reset();
        document.getElementById('input-accion').value = 'crear';
        document.getElementById('input-id').value = '';

        const title = document.getElementById('form-title');
        title.innerHTML = '<span class="text-cyan-500 material-symbols-rounded">add_circle</span> NUEVO ÍTEM';
        title.classList.remove('text-green-400');

        const btn = document.getElementById('btn-submit');
        document.getElementById('btn-text').textContent = 'GUARDAR ÍTEM';
        btn.querySelector('.material-symbols-rounded').textContent = 'save';

        btn.classList.remove('bg-green-600', 'hover:bg-green-500', 'shadow-green-900/40', 'text-white');
        btn.classList.add('bg-cyan-600', 'hover:bg-cyan-500', 'shadow-cyan-900/40', 'text-black');

        document.getElementById('btn-cancelar').classList.add('hidden');
    }
</script>

<?php require_once 'Frontend/views/layouts/admin_footer.php'; ?>