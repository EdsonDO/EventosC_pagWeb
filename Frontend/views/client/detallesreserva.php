<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva Confirmada | EventosC</title>
    <link rel="stylesheet" href="Frontend/assets/css/index.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>

    <?php require_once 'Frontend/views/layouts/header.php'; ?>

    <div class="success-wrapper">
        <div class="success-card">

            <div class="icon-success">
                <span class="material-symbols-rounded" style="font-size: 80px;">check_circle</span>
            </div>

            <h1 class="success-title">¡Pago Exitoso!</h1>
            <p class="success-subtitle">Tu reserva ha sido procesada correctamente.</p>

            <div class="ticket-details">
                <?php if (isset($ticket) && $ticket && is_array($ticket)): ?>
                    <div class="detail-row">
                        <span>Código de Reserva</span>
                        <strong
                            style="color:#39ff14;">#<?php echo str_pad($ticket['id_reserva'], 6, '0', STR_PAD_LEFT); ?></strong>
                    </div>
                    <div class="detail-row">
                        <span>Evento</span>
                        <strong><?php echo htmlspecialchars($ticket['nombre_evento']); ?></strong>
                    </div>
                    <div class="detail-row">
                        <span>Sede</span>
                        <strong><?php echo htmlspecialchars($ticket['nombre_sede']); ?></strong>
                    </div>
                    <div class="detail-row">
                        <span>Dirección</span>
                        <small><?php echo htmlspecialchars($ticket['direccion']); ?></small>
                    </div>
                    <div class="detail-row">
                        <span>Fecha</span>
                        <strong><?php echo $ticket['fecha_evento']; ?></strong>
                    </div>
                    <div class="detail-row">
                        <span>Horario</span>
                        <strong><?php echo substr($ticket['hora_inicio'], 0, 5) . ' - ' . substr($ticket['hora_fin'], 0, 5); ?></strong>
                    </div>
                    <div class="detail-row">
                        <span>Monto Pagado</span>
                        <strong>S/ <?php echo number_format($ticket['monto_pagado'], 2); ?></strong>
                    </div>
                <?php else: ?>
                    <div
                        style="background: rgba(255,0,0,0.1); border: 1px solid red; padding: 15px; border-radius: 5px; text-align: left;">
                        <h4 style="color: #ff5555; margin-bottom: 5px;">Error de Visualización</h4>
                        <p style="font-size: 0.9rem; color: #ddd;">La reserva se guardó, pero no pudimos recuperar el
                            ticket.</p>
                        <hr style="border-color: rgba(255,255,255,0.1); margin: 10px 0;">
                        <small style="color: #aaa;">ID Buscado:
                            <strong><?php echo isset($_GET['id']) ? $_GET['id'] : 'Ninguno'; ?></strong></small>
                        <br>
                        <small style="color: #aaa;">Posible Causa: Integridad de base de datos (Usuario/Sede
                            faltante).</small>
                    </div>
                <?php endif; ?>
            </div>

            <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                <a href="index.php?view=home" class="btn-home">Volver al Inicio</a>
                <a href="index.php?view=historial" class="btn-history">Ver Mis Reservas</a>
            </div>

        </div>
    </div>

    <?php require_once 'Frontend/views/layouts/footer.php'; ?>

</body>

</html>