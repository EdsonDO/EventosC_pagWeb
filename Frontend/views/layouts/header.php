<header class="main-header">
    <nav>
        <div class="nav-group group-left">
            <a href="index.php?view=reservas">
                <button type="button">
                    <span class="material-symbols-rounded">travel_explore</span>
                    Explorar
                </button>
            </a>

            <a href="index.php?view=recursos">
                <button type="button">
                    <span class="material-symbols-rounded">inventory_2</span>
                    Recursos
                </button>
            </a>
        </div>

        <a href="index.php?view=home" class="logo-link">
            <img src="Frontend/assets/img/eventosclogo.png" alt="EventosC" class="logo-img">
        </a>

        <div class="nav-group group-right">
            <a href="index.php?view=historial">
                <button type="button">
                    <span class="material-symbols-rounded">event_available</span>
                    Mis Eventos
                </button>
            </a>

            <a href="index.php?view=dashboard">
                <button type="button" class="btn-admin">
                    <span class="material-symbols-rounded">admin_panel_settings</span>
                    Panel Admin
                </button>
            </a>

            <div class="nav-divider"></div>

            <?php if (isset($_SESSION['id_usuario'])): ?>
                <a href="index.php?view=logout" title="Cerrar Sesión">
                    <button type="button" class="btn-logout">
                        <span class="material-symbols-rounded">logout</span>
                        Salir
                    </button>
                </a>
            <?php else: ?>
                <a href="index.php?view=login">
                    <button type="button" class="btn-login">
                        <span class="material-symbols-rounded">login</span>
                        Acceder
                    </button>
                </a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<style>
    .btn-admin {
        background: linear-gradient(135deg, #e74c3c, #c0392b) !important;
        color: white !important;
        font-weight: 600;
    }

    .btn-admin:hover {
        background: linear-gradient(135deg, #c0392b, #a93226) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
    }
</style>