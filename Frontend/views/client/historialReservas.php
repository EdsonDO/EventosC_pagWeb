<?php require_once 'Frontend/views/layouts/header.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Eventos | EventosC</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: { preflight: false },
            theme: {
                extend: {
                    colors: {
                        dark: '#050505',
                        card: '#0f0f0f',
                        neon: '#39ff14',
                        cyan: '#00f3ff',
                        danger: '#ff3333',
                        warning: '#ffcc00',
                    },
                    fontFamily: {
                        brand: ['Orbitron', 'sans-serif'],
                        body: ['Montserrat', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0"
        rel="stylesheet">
    <link rel="stylesheet" href="Frontend/assets/css/index.css">

    <style>
        body {
            background-color: #050505 !important;
        }

        .dark-glass {
            background-color: #0a0a0a;
            border: 1px solid #1f1f1f;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.8);
        }

        .giant-watermark {
            position: absolute;
            right: -20px;
            bottom: -30px;
            font-size: 8rem !important;
            color: white;
            opacity: 0.02;
            transform: rotate(0deg) scale(0.9);
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            pointer-events: none;
            z-index: 0;
        }

        .history-card {
            transition: all 0.3s ease;
        }

        .history-card:hover {
            border-color: #00f3ff;
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(0, 243, 255, 0.1);
        }

        .history-card:hover .giant-watermark {
            opacity: 0.08;
            transform: rotate(-15deg) scale(1.1);
            bottom: -10px;
            right: -10px;
        }

        .badge-status {
            backdrop-filter: blur(4px);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body>

    <div class="main-wrapper" style="padding-top: 100px;">
        <div class="main-container animate-fade-in"
            style="min-height: 80vh; max-width: 1400px; margin: 0 auto; padding: 20px;">

            <div class="flex justify-between items-end mb-10 border-b border-gray-800 pb-6">
                <div>
                    <h1 class="text-4xl font-brand text-white flex items-center gap-4 uppercase tracking-wider">
                        <span class="material-symbols-rounded text-cyan text-5xl">history_edu</span>
                        Historial de Eventos
                    </h1>
                    <p class="text-gray-400 text-sm mt-2 ml-1">Gestiona y revisa el estado de tus reservaciones.</p>
                </div>

                <div class="bg-[#111] border border-gray-800 rounded-xl p-4 flex flex-col items-end min-w-[150px]">
                    <span class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Total Registrados</span>
                    <span class="text-3xl font-brand text-neon font-bold"><?php echo count($eventos); ?></span>
                </div>
            </div>

            <?php if (empty($eventos)): ?>
                <div
                    class="flex flex-col items-center justify-center py-32 border border-dashed border-gray-800 rounded-3xl bg-[#080808]">
                    <div class="w-24 h-24 rounded-full bg-gray-900 flex items-center justify-center mb-6">
                        <span class="material-symbols-rounded text-gray-700 text-5xl">event_busy</span>
                    </div>
                    <h3 class="text-2xl text-white font-brand mb-2">Sin Historial Reciente</h3>
                    <p class="text-gray-500 text-sm mb-8">No hemos encontrado reservaciones asociadas a tu cuenta.</p>
                    <a href="index.php?view=reservas"
                        class="group relative overflow-hidden bg-neon text-black px-8 py-4 rounded-xl font-bold uppercase tracking-wider hover:bg-[#32e010] transition-all shadow-[0_0_20px_rgba(57,255,20,0.3)] hover:shadow-[0_0_30px_rgba(57,255,20,0.5)] flex items-center gap-2">
                        <span class="material-symbols-rounded">add_circle</span> Nuevo Evento
                    </a>
                </div>
            <?php else: ?>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($eventos as $e): ?>

                        <?php
                        $statusColor = 'text-warning border-warning/30 bg-warning/10';
                        $iconWatermark = 'schedule';
                        $glowClass = 'hover:shadow-yellow-500/10 hover:border-warning';

                        if ($e['estado'] === 'Confirmado') {
                            $statusColor = 'text-neon border-neon/30 bg-neon/10';
                            $iconWatermark = 'verified';
                            $glowClass = 'hover:shadow-[0_0_30px_rgba(57,255,20,0.15)] hover:border-neon';
                        } elseif ($e['estado'] === 'Cancelado') {
                            $statusColor = 'text-danger border-danger/30 bg-danger/10';
                            $iconWatermark = 'block';
                            $glowClass = 'hover:shadow-red-500/20 hover:border-danger';
                        }
                        ?>

                        <div
                            class="history-card relative dark-glass rounded-2xl overflow-hidden group border border-gray-800 <?php echo $glowClass; ?>">

                            <span class="material-symbols-rounded giant-watermark">
                                <?php echo $iconWatermark; ?>
                            </span>

                            <div class="relative z-10 p-6 flex flex-col h-full">

                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Fecha</span>
                                        <span class="text-white font-brand text-lg font-bold">
                                            <?php echo date('d M, Y', strtotime($e['fecha_evento'])); ?>
                                        </span>
                                    </div>

                                    <span
                                        class="badge-status px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest border <?php echo $statusColor; ?>">
                                        <?php echo $e['estado']; ?>
                                    </span>
                                </div>

                                <div class="mb-6">
                                    <h3
                                        class="text-xl font-bold text-white group-hover:text-cyan transition-colors line-clamp-2 leading-tight mb-2">
                                        <?php echo htmlspecialchars($e['nombre_evento']); ?>
                                    </h3>
                                    <div class="flex items-center gap-2 text-gray-400 text-xs uppercase tracking-wide">
                                        <span class="material-symbols-rounded text-sm">location_on</span>
                                        <span class="truncate"><?php echo htmlspecialchars($e['nombre_sede']); ?></span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 border-t border-gray-800/50 pt-4 mt-auto">
                                    <div>
                                        <span class="text-[10px] text-gray-600 uppercase block mb-1">Horario</span>
                                        <span
                                            class="text-sm text-gray-300 font-mono bg-black/20 px-2 py-1 rounded inline-block border border-gray-800">
                                            <?php echo date('H:i', strtotime($e['hora_inicio'])) . ' - ' . date('H:i', strtotime($e['hora_fin'])); ?>
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] text-gray-600 uppercase block mb-1">Costo Total</span>
                                        <span class="text-lg text-white font-brand font-bold">
                                            S/ <?php echo number_format($e['costo_total'], 2); ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-6 pt-4 border-t border-gray-800">
                                    <?php if ($e['id_reserva']): ?>
                                        <a href="index.php?view=detalles&id=<?php echo $e['id_reserva']; ?>"
                                            class="w-full flex items-center justify-center gap-2 bg-[#1a1a1a] hover:bg-cyan hover:text-black text-white py-3 rounded-lg text-xs font-bold uppercase tracking-widest transition-all border border-gray-700 hover:border-cyan group-hover:shadow-lg">
                                            <span class="material-symbols-rounded text-sm">visibility</span>
                                            Ver Detalles
                                        </a>
                                    <?php else: ?>
                                        <div class="w-full text-center py-3 bg-red-900/10 border border-red-900/20 rounded-lg">
                                            <span class="text-red-400 text-xs italic flex items-center justify-center gap-2">
                                                <span class="material-symbols-rounded text-sm">error</span> Sin ID Reserva
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php require_once 'Frontend/views/layouts/footer.php'; ?>
</body>

</html>