<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Fecha | EventosC</title>
    <link rel="stylesheet" href="Frontend/assets/css/index.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">

    <style>
        .config-wrapper {
            width: 100%;
            min-height: 100vh;
            padding-top: 120px;
            padding-bottom: 50px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            position: relative;
            z-index: 2;
        }

        .config-container {
            width: 90%;
            max-width: 1000px;
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 40px;
        }

        .venue-summary {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 30px;
            height: fit-content;
        }

        .summary-title {
            color: #aaa;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .venue-name-big {
            font-family: 'Helvetica', sans-serif;
            color: white;
            font-size: 2rem;
            margin-bottom: 20px;
            line-height: 1.1;
        }

        .venue-spec {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
            margin: 20px 0;
        }

        .config-form-box {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 40px;
        }

        .form-section {
            margin-bottom: 30px;
        }

        .form-label {
            display: block;
            color: white;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 1rem;
        }

        .input-elegant {
            width: 100%;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: white;
            font-family: 'Helvetica', sans-serif;
            font-size: 1rem;
            outline: none;
            transition: 0.3s;
        }

        .input-elegant.calendar-input {
            cursor: pointer;
            background-image: url('data:image/svg+xml;utf8,<svg fill="white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 20px;
        }

        .input-elegant:focus {
            border-color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .input-elegant option {
            background: #111;
            color: white;
        }

        .row {
            display: flex;
            gap: 20px;
        }

        .col {
            flex: 1;
        }

        .btn-continue {
            width: 100%;
            padding: 15px;
            background: white;
            color: black;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1.1rem;
            cursor: pointer;
            transition: 0.3s;
            text-transform: uppercase;
            margin-top: 10px;
        }

        .btn-continue:hover {
            background: #eee;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
        }

        @media (max-width: 768px) {
            .config-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <?php require_once 'Frontend/views/layouts/header.php'; ?>

    <div class="config-wrapper">
        <div class="config-container">

            <div class="venue-summary">
                <p class="summary-title">Espacio Seleccionado</p>
                <h1 class="venue-name-big"><?php echo $sedeInfo['nombre']; ?></h1>

                <div class="venue-spec">
                    <span class="material-symbols-rounded">location_on</span>
                    <?php echo $sedeInfo['direccion']; ?>
                </div>
                <div class="venue-spec">
                    <span class="material-symbols-rounded">groups</span>
                    Capacidad: <?php echo $sedeInfo['capacidad']; ?> personas
                </div>

                <div class="divider"></div>

                <p style="color: #aaa; font-size: 0.9rem; line-height: 1.5;">
                    Estás a un paso de reservar este espacio. Utiliza el calendario para verificar disponibilidad.
                </p>
            </div>

            <div class="config-form-box">
                <h2 style="color: white; margin-bottom: 30px;">Detalles de la Reserva</h2>

                <form action="index.php?view=procesar_reserva" method="POST" id="formConfig">
                    <input type="hidden" name="id_sede" value="<?php echo $sedeInfo['id_sede']; ?>">
                    <input type="hidden" name="nombre_sede_hidden" value="<?php echo $sedeInfo['nombre']; ?>">
                    <input type="hidden" name="nombre_evento" id="input_nombre_evento">

                    <div class="form-section">
                        <label class="form-label">Tipo de Evento</label>
                        <select id="select_tipo" name="id_tipo_evento" class="input-elegant" required
                            onchange="actualizarNombre()">
                            <option value="" disabled selected>Selecciona el tipo...</option>
                            <?php foreach ($tiposEvento as $tipo): ?>
                                <option value="<?php echo $tipo['id_tipo_evento']; ?>"
                                    data-nombre="<?php echo htmlspecialchars($tipo['nombre_tipo']); ?>">
                                    <?php echo htmlspecialchars($tipo['nombre_tipo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-section">
                        <label class="form-label">Fecha del Evento</label>
                        <input type="text" name="fecha" id="input_fecha" class="input-elegant calendar-input"
                            placeholder="Selecciona una fecha..." required>
                    </div>

                    <div class="row form-section">
                        <div class="col">
                            <label class="form-label">Hora Inicio</label>
                            <input type="time" name="hora_inicio" class="input-elegant" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Hora Fin</label>
                            <input type="time" name="hora_fin" class="input-elegant" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-continue">
                        Continuar a la pantalla de Servicios
                    </button>
                </form>
            </div>

        </div>
    </div>

    <?php require_once 'Frontend/views/layouts/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

    <script>
        flatpickr("#input_fecha", {
            locale: "es",
            minDate: "today",
            dateFormat: "Y-m-d",
            theme: "dark",
            onChange: function (selectedDates, dateStr, instance) {
                actualizarNombre();
            }
        });

        function actualizarNombre() {
            const select = document.getElementById('select_tipo');
            const tipo = select.options[select.selectedIndex].getAttribute('data-nombre');
            const fecha = document.getElementById('input_fecha').value;
            const sede = "<?php echo $sedeInfo['nombre']; ?>";

            if (tipo && fecha) {
                const nombreAuto = `${tipo} en ${sede} (${fecha})`;
                document.getElementById('input_nombre_evento').value = nombreAuto;
            }
        }
    </script>

</body>

</html>