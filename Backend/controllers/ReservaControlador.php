<?php

require_once __DIR__ . '/../models/Sede.php';
require_once __DIR__ . '/../models/Reserva.php';

class ReservaControlador
{
    public function index()
    {
        $sedeModel = new Sede();
        $listaSedes = $sedeModel->listarSedes();
        require_once 'Frontend/views/client/reservas.php';
    }

    public function historial()
    {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?view=login');
            exit;
        }

        $reservaModel = new Reserva();
        $eventos = $reservaModel->listarPorCliente($_SESSION['id_usuario']);

        require_once 'Frontend/views/client/historialReservas.php';
    }

    public function servicios()
    {
        if (!isset($_SESSION['reserva_temp'])) {
            header('Location: index.php?view=reservas');
            exit;
        }

        require_once __DIR__ . '/../models/Servicio.php';
        $servicioModel = new Servicio();
        $servicios = $servicioModel->listarServicios();

        require_once 'Frontend/views/client/serviciosReserva.php';
    }

    public function configurar()
    {
        if (!isset($_GET['id_sede'])) {
            header('Location: index.php?view=reservas');
            exit;
        }
        $id_sede = $_GET['id_sede'];

        $sedeModel = new Sede();
        $sedeInfo = $sedeModel->obtenerSedePorId($id_sede);

        require_once __DIR__ . '/../models/TipoEvento.php';
        $tipoEventoModel = new TipoEvento();
        $tiposEvento = $tipoEventoModel->listar();

        if (!$sedeInfo) {
            header('Location: index.php?view=reservas');
            exit;
        }
        require_once 'Frontend/views/client/configReserva.php';
    }

    public function procesar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $sedeModel = new Sede();
            $sedeInfo = $sedeModel->obtenerSedePorId($_POST['id_sede']);
            $costoBase = $sedeInfo ? $sedeInfo['precio_base'] : 0;

            $_SESSION['reserva_temp'] = [
                'nombre_evento' => $_POST['nombre_evento'],
                'id_sede' => $_POST['id_sede'],
                'id_tipo_evento' => $_POST['id_tipo_evento'],
                'nombre_sede' => $_POST['nombre_sede_hidden'],
                'fecha' => $_POST['fecha'],
                'hora_inicio' => $_POST['hora_inicio'],
                'hora_fin' => $_POST['hora_fin'],
                'costo_estimado' => $costoBase
            ];
            header('Location: index.php?view=servicios_reserva');
            exit;
        }
    }

    public function agregarServicios()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['reserva_temp'])) {
            $serviciosSeleccionados = $_POST['servicios'] ?? [];
            $_SESSION['reserva_temp']['servicios'] = $serviciosSeleccionados;

            $servicesTotal = 0;
            if (!empty($serviciosSeleccionados)) {
                require_once __DIR__ . '/../models/Servicio.php';
                $servicioModel = new Servicio();
                foreach ($serviciosSeleccionados as $idServicio) {
                    $s = $servicioModel->obtenerServicioPorId($idServicio);
                    if ($s) {
                        $servicesTotal += floatval($s['costo']);
                    }
                }
            }

            $start = new DateTime($_SESSION['reserva_temp']['hora_inicio']);
            $end = new DateTime($_SESSION['reserva_temp']['hora_fin']);
            $diff = $start->diff($end);
            $hours = $diff->h + ($diff->i / 60);
            if ($hours <= 0)
                $hours = 1;

            $venueTotal = $_SESSION['reserva_temp']['costo_estimado'] * $hours;

            $_SESSION['reserva_temp']['costo_total_final'] = $venueTotal + $servicesTotal;

            header('Location: index.php?view=pagos');
            exit;
        } else {
            header('Location: index.php?view=reservas');
            exit;
        }
    }

    public function pagos()
    {
        if (!isset($_SESSION['reserva_temp'])) {
            header('Location: index.php?view=reservas');
            exit;
        }
        require_once 'Frontend/views/client/pagos.php';
    }

    public function confirmarPago()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_SESSION['reserva_temp'])) {
                $data = $_SESSION['reserva_temp'];
                $id_usuario = $_SESSION['id_usuario'] ?? 1;

                $datosInsertar = [
                    'id_usuario' => $id_usuario,
                    'id_sede' => $data['id_sede'],
                    'id_tipo_evento' => $data['id_tipo_evento'],
                    'nombre_evento' => $data['nombre_evento'],
                    'fecha' => $data['fecha'],
                    'hora_inicio' => $data['hora_inicio'],
                    'hora_fin' => $data['hora_fin'],
                    'costo' => $data['costo_total_final'],
                    'servicios' => $data['servicios'] ?? []
                ];

                $reservaModel = new Reserva();
                $id_reserva = $reservaModel->registrar($datosInsertar);

                if ($id_reserva) {
                    unset($_SESSION['reserva_temp']);
                    header("Location: index.php?view=detalles&id=$id_reserva");
                    exit;
                } else {
                    echo "<script>alert('Error fatal al guardar la reserva'); window.history.back();</script>";
                }
            } else {
                header('Location: index.php?view=reservas');
                exit;
            }
        }
    }

    public function detalles()
    {
        $id_reserva = $_GET['id'] ?? 0;
        $reservaModel = new Reserva();
        $ticket = $reservaModel->obtenerDetalle($id_reserva);
        require_once 'Frontend/views/client/detallesreserva.php';
    }
}
?>