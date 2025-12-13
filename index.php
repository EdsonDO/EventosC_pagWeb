<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();


define('BASE_PATH', __DIR__);

require_once 'Backend/config/Conexion.php';

spl_autoload_register(function ($class_name) {
    $file = 'Backend/models/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

$view = isset($_GET['view']) ? $_GET['view'] : 'home';


switch ($view) {

    case 'home':

        require_once 'Frontend/views/client/home.php';
        break;

    case 'login':
        require_once 'Frontend/views/login.php';
        break;

    case 'register':
        require_once 'Frontend/views/register.php';
        break;

    case 'logout':

        header('Location: index.php?view=login');
        exit;



    case 'reservas':
        require_once 'Backend/controllers/ReservaControlador.php';
        $controller = new ReservaControlador();
        $controller->index();
        break;

    case 'configReserva':
        require_once 'Backend/controllers/ReservaControlador.php';
        $controller = new ReservaControlador();
        $controller->configurar();

    case 'procesar_reserva':
        require_once 'Backend/controllers/ReservaControlador.php';
        $controller = new ReservaControlador();
        $controller->procesar();
        break;

    case 'servicios_reserva':
        require_once 'Backend/controllers/ReservaControlador.php';
        $controller = new ReservaControlador();
        $controller->servicios();
        break;

    case 'agregar_servicios':
        require_once 'Backend/controllers/ReservaControlador.php';
        $controller = new ReservaControlador();
        $controller->agregarServicios();
        break;

    case 'pagos':
        require_once 'Backend/controllers/ReservaControlador.php';
        $controller = new ReservaControlador();
        $controller->pagos();
        break;

    case 'confirmar_pago':
        require_once 'Backend/controllers/ReservaControlador.php';
        $controller = new ReservaControlador();
        $controller->confirmarPago();
        break;

    case 'detalles':
        require_once 'Backend/controllers/ReservaControlador.php';
        $controller = new ReservaControlador();
        $controller->detalles();
        break;

    case 'historial':
    case 'historialReservas':
        require_once 'Backend/controllers/ReservaControlador.php';
        $controller = new ReservaControlador();
        $controller->historial();
        break;



    case 'dashboard':
        require_once 'Frontend/views/admin/dashboard.php';
        break;

    case 'clientes':
        require_once 'Frontend/views/admin/crudClientes.php';
        break;

    case 'proveedores':
        require_once 'Frontend/views/admin/crudProveedores.php';
        break;

    case 'recursos':
        require_once 'Frontend/views/admin/crudRecursos.php';
        break;

    case 'servicios':
        require_once 'Frontend/views/admin/crudServicios.php';
        break;

    case 'sedes':
        require_once 'Frontend/views/admin/crudSedes.php';
        break;

    case 'eventos':
        require_once 'Frontend/views/admin/crudEventos.php';
        break;


    default:
        require_once 'Frontend/views/client/home.php';
        break;
}
?>