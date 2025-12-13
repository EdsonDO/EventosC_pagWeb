<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasarela de Pago | EventosC</title>
    <link rel="stylesheet" href="Frontend/assets/css/index.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>

    <div id="loaderOverlay" class="loader-overlay">
        <div class="spinner"></div>
        <div class="loading-text">PROCESANDO PAGO SEGURO...</div>
        <small style="color: #aaa; margin-top: 10px;">Por favor, no cierres la ventana</small>
    </div>

    <?php require_once 'Frontend/views/layouts/header.php'; ?>

    <?php
    $info = isset($_SESSION['reserva_temp']) ? $_SESSION['reserva_temp'] : [];
    if (empty($info)) {
        echo "<script>window.location='index.php?view=reservas';</script>";
        exit;
    }
    ?>

    <div class="payment-wrapper">
        <div class="payment-container">

            <div class="order-summary">
                <div class="summary-header">
                    <h2>Resumen del Pedido</h2>
                </div>

                <div class="venue-preview">
                    <span class="material-symbols-rounded venue-icon">apartment</span>
                    <div>
                        <h4><?php echo $info['nombre_sede']; ?></h4>
                        <small>Sede Principal</small>
                    </div>
                </div>
                <br>

                <div class="ticket-item">
                    <span>Evento</span>
                    <span><?php echo $info['nombre_evento']; ?></span>
                </div>
                <div class="ticket-item">
                    <span>Fecha</span>
                    <span><?php echo $info['fecha']; ?></span>
                </div>
                <div class="ticket-item">
                    <span>Horario</span>
                    <span><?php echo $info['hora_inicio'] . ' - ' . $info['hora_fin']; ?></span>
                </div>

                <div class="ticket-item total">
                    <span>TOTAL A PAGAR</span>
                    <span>S/
                        <?php echo number_format(isset($info['costo_total_final']) ? $info['costo_total_final'] : $info['costo_estimado'], 2); ?></span>
                </div>
            </div>

            <div class="payment-form-section">

                <div class="card-visual">
                    <div class="card-chip"></div>
                    <div class="card-number-display" id="displayNum">#### #### #### ####</div>
                    <div class="card-details-display">
                        <span id="displayName">NOMBRE TITULAR</span>
                        <span id="displayExp">MM/YY</span>
                    </div>
                    <div
                        style="position:absolute; bottom:20px; right:20px; font-weight:bold; font-style:italic; opacity:0.5;">
                        VISA</div>
                </div>

                <form action="index.php?view=confirmar_pago" method="POST" id="paymentForm">

                    <div class="input-group">
                        <label class="input-label">Número de Tarjeta</label>
                        <input type="text" class="cyber-input" placeholder="0000 0000 0000 0000" maxlength="19"
                            oninput="updateCardNum(this)" required>
                    </div>

                    <div class="input-group">
                        <label class="input-label">Nombre del Titular</label>
                        <input type="text" class="cyber-input" placeholder="COMO APARECE EN LA TARJETA"
                            oninput="updateCardName(this)" required>
                    </div>

                    <div class="row">
                        <div class="input-group" style="flex:1">
                            <label class="input-label">Expiración</label>
                            <input type="text" class="cyber-input" placeholder="MM/YY" maxlength="5"
                                oninput="updateCardExp(this)" required>
                        </div>
                        <div class="input-group" style="flex:1">
                            <label class="input-label">CVV</label>
                            <input type="password" class="cyber-input" placeholder="123" maxlength="3" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-pay">
                        PAGAR S/
                        <?php echo number_format(isset($info['costo_total_final']) ? $info['costo_total_final'] : $info['costo_estimado'], 2); ?>
                    </button>

                    <p style="text-align:center; color:#666; font-size:0.7rem; margin-top:15px;">
                        <span class="material-symbols-rounded"
                            style="font-size:10px; vertical-align:middle;">lock</span>
                        Pagos procesados de forma segura con encriptación SSL.
                    </p>
                </form>

            </div>

        </div>
    </div>

    <?php require_once 'Frontend/views/layouts/footer.php'; ?>

    <script>
        function updateCardNum(el) {
            let val = el.value.replace(/\s/g, '').replace(/(\d{4})/g, '$1 ').trim();
            el.value = val;
            document.getElementById('displayNum').innerText = val || '#### #### #### ####';
        }
        function updateCardName(el) {
            document.getElementById('displayName').innerText = el.value.toUpperCase() || 'NOMBRE TITULAR';
        }
        function updateCardExp(el) {
            let val = el.value.replace(/\D/g, '');
            if (val.length >= 2) val = val.substring(0, 2) + '/' + val.substring(2, 4);
            el.value = val;
            document.getElementById('displayExp').innerText = val || 'MM/YY';
        }

        document.getElementById('paymentForm').addEventListener('submit', function (e) {
            e.preventDefault();

            document.getElementById('loaderOverlay').style.display = 'flex';

            setTimeout(() => {
                this.submit();
            }, 3000);
        });
    </script>

</body>

</html>