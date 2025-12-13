<?php require_once 'Frontend/views/layouts/admin_header.php'; ?>

<title>Dashboard | EventosC Admin</title>

<div style="margin-bottom: 40px;">
    <h1 class="dashboard-title">Panel de Control</h1>
    <p class="dashboard-subtitle">Seleccione un módulo para gestionar el sistema.</p>
</div>

<div class="modules-grid">
    
    <a href="index.php?view=eventos" class="module-card card-purple">
        <span class="material-symbols-rounded module-bg-icon">calendar_month</span>
        
        <div class="module-content">
            <div class="module-icon">
                <span class="material-symbols-rounded">calendar_month</span>
            </div>
            <div>
                <h3 class="module-title">Eventos</h3>
                <p class="module-desc">Gestión del cronograma, estados de pago y organización.</p>
            </div>
        </div>
    </a>

    <a href="index.php?view=clientes" class="module-card card-blue">
        <span class="material-symbols-rounded module-bg-icon">group</span>
        <div class="module-content">
            <div class="module-icon">
                <span class="material-symbols-rounded">group</span>
            </div>
            <div>
                <h3 class="module-title">Clientes</h3>
                <p class="module-desc">Base de datos de usuarios, historial y registro.</p>
            </div>
        </div>
    </a>

    <a href="index.php?view=sedes" class="module-card card-green">
        <span class="material-symbols-rounded module-bg-icon">location_city</span>
        <div class="module-content">
            <div class="module-icon">
                <span class="material-symbols-rounded">location_city</span>
            </div>
            <div>
                <h3 class="module-title">Sedes</h3>
                <p class="module-desc">Administración de auditorios, capacidades y aforos.</p>
            </div>
        </div>
    </a>

    <a href="index.php?view=recursos" class="module-card card-cyan">
        <span class="material-symbols-rounded module-bg-icon">inventory_2</span>
        <div class="module-content">
            <div class="module-icon">
                <span class="material-symbols-rounded">inventory_2</span>
            </div>
            <div>
                <h3 class="module-title">Recursos</h3>
                <p class="module-desc">Control de inventario y stock de equipos.</p>
            </div>
        </div>
    </a>

    <a href="index.php?view=proveedores" class="module-card card-amber">
        <span class="material-symbols-rounded module-bg-icon">local_shipping</span>
        <div class="module-content">
            <div class="module-icon">
                <span class="material-symbols-rounded">local_shipping</span>
            </div>
            <div>
                <h3 class="module-title">Proveedores</h3>
                <p class="module-desc">Directorio de socios externos y contactos.</p>
            </div>
        </div>
    </a>

    <a href="index.php?view=servicios" class="module-card card-rose">
        <span class="material-symbols-rounded module-bg-icon">design_services</span>
        <div class="module-content">
            <div class="module-icon">
                <span class="material-symbols-rounded">design_services</span>
            </div>
            <div>
                <h3 class="module-title">Servicios</h3>
                <p class="module-desc">Catálogo de servicios y configuración de costos.</p>
            </div>
        </div>
    </a>

</div>

<?php require_once 'Frontend/views/layouts/admin_footer.php'; ?>