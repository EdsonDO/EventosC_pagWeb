<?php

if (!isset($_GET['id']) || empty($_GET['id'])) {
  echo "<script>
            alert('❌ Error: No se especificó el ID de la reserva.');
            window.location.href = 'index.php?view=dashboard';
          </script>";
  exit();
}

$id_reserva = intval($_GET['id']);

try {
  $adminModel = new Admin();

  $resultado = $adminModel->reactivarEvento($id_reserva);

  if ($resultado) {
    echo "<script>
                alert('✅ ¡Evento reactivado exitosamente!\\n\\nEl evento #" . $id_reserva . " ha sido reactivado.');
                window.location.href = 'index.php?view=dashboard';
              </script>";
  } else {
    echo "<script>
                alert('❌ Error: No se pudo reactivar el evento.\\n\\nIntenta nuevamente.');
                window.location.href = 'index.php?view=dashboard';
              </script>";
  }

} catch (Exception $e) {
  error_log("Error en reactivar_reserva.php: " . $e->getMessage());
  echo "<script>
            alert('❌ Error del sistema: " . addslashes($e->getMessage()) . "');
            window.location.href = 'index.php?view=dashboard';
          </script>";
}
?>