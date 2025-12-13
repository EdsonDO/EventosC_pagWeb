<?php

if (!isset($_SESSION['reserva_temp'])) {
    header('Location: index.php?view=reservas');
    exit;
}
$temp = $_SESSION['reserva_temp'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selección de Servicios | EventosC</title>

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
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0"
        rel="stylesheet">
    <link rel="stylesheet" href="Frontend/assets/css/index.css">

    <style>
        body {
            background-color: #050505 !important;
        }

        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: #111;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #222;
            border-radius: 10px;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #39ff14;
        }


        .dark-glass {
            background: #0a0a0a;

            border: 1px solid #222;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.8);
        }


        .giant-watermark {
            position: absolute;
            right: -20px;
            bottom: -30px;
            font-size: 8rem !important;

            color: white;
            opacity: 0;

            transform: rotate(0deg) scale(0.8);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            pointer-events: none;
            z-index: 0;
        }

        .service-card.active .giant-watermark {
            opacity: 0.05;
            transform: rotate(-15deg) scale(1);
            bottom: -15px;
        }

        .big-text {
            font-size: 1.1rem;
        }
    </style>
</head>

<body>

    <?php require_once 'Frontend/views/layouts/header.php'; ?>

    <div class="main-wrapper" style="padding-top: 100px;">
        <div class="max-w-[1500px] mx-auto px-6 animate-fade-in pb-20">

            <div class="mb-10 border-b border-gray-800 pb-6">
                <h2 class="text-4xl font-brand font-bold text-white uppercase tracking-wider flex items-center gap-4">
                    <span class="material-symbols-rounded text-neon text-5xl">settings_account_box</span>
                    Servicios Integrales
                </h2>
                <p class="text-gray-400 text-lg mt-2 ml-2">Personaliza la experiencia de tu evento al máximo nivel.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                <div class="lg:col-span-4">
                    <div class="dark-glass rounded-2xl p-8 sticky top-24">
                        <h3
                            class="text-2xl font-brand text-white mb-8 border-b border-gray-800 pb-4 flex items-center gap-3">
                            <span class="material-symbols-rounded text-cyan">receipt</span> RESUMEN
                        </h3>

                        <div class="space-y-5 mb-8 text-base">
                            <div>
                                <span class="text-gray-500 block text-xs uppercase tracking-widest mb-1">Sede
                                    Seleccionada</span>
                                <span
                                    class="text-white font-bold text-xl block truncate"><?php echo htmlspecialchars($temp['nombre_sede']); ?></span>
                            </div>

                            <?php
                            $start = new DateTime($temp['hora_inicio']);
                            $end = new DateTime($temp['hora_fin']);
                            $diff = $start->diff($end);
                            $hours = $diff->h + ($diff->i / 60);
                            if ($hours <= 0)
                                $hours = 1;
                            ?>

                            <div
                                class="flex justify-between items-center bg-[#151515] p-3 rounded-lg border border-gray-800">
                                <span class="text-gray-400">Duración</span>
                                <span class="text-cyan font-brand font-bold text-lg"><?php echo $hours; ?> hrs</span>
                            </div>

                            <div class="flex justify-between items-end">
                                <span class="text-gray-400">Costo Base Sede</span>
                                <span class="text-white font-mono text-lg">S/
                                    <?php echo number_format($temp['costo_estimado'] * $hours, 2); ?></span>
                            </div>
                        </div>

                        <div id="selected-services-container"
                            class="hidden bg-[#111] rounded-xl p-4 border border-gray-800 mb-6">
                            <p
                                class="text-neon text-xs font-bold uppercase tracking-widest mb-3 border-b border-gray-800 pb-2">
                                Extras Añadidos
                            </p>
                            <div id="selected-services-list"
                                class="space-y-3 text-sm max-h-[150px] overflow-y-auto custom-scroll pr-2">
                            </div>
                        </div>

                        <div class="mt-auto">
                            <span class="text-gray-500 text-xs uppercase tracking-widest block mb-2">Total
                                Estimado</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-brand font-bold text-white">S/ </span>
                                <span class="text-5xl font-brand font-bold text-white" id="display-total">
                                    <?php echo number_format($temp['costo_estimado'] * $hours, 2); ?>
                                </span>
                            </div>
                            <span class="text-xs text-gray-600 block mt-2 text-right">*Incluye IGV</span>
                        </div>

                        <button onclick="document.getElementById('form-servicios').submit()"
                            class="w-full mt-8 bg-neon hover:bg-[#32e010] text-black font-brand font-bold text-lg py-5 rounded-xl shadow-[0_0_20px_rgba(57,255,20,0.2)] transition-all transform hover:scale-[1.02] flex justify-center items-center gap-3">
                            CONTINUAR AL PAGO <span class="material-symbols-rounded">arrow_forward</span>
                        </button>
                    </div>
                </div>

                <div class="lg:col-span-8">

                    <div class="custom-scroll overflow-y-auto max-h-[800px] pr-4 pb-10">

                        <form id="form-servicios" action="index.php?view=agregar_servicios" method="POST">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <?php if (empty($servicios)): ?>
                                    <div
                                        class="col-span-2 text-center py-32 border border-dashed border-gray-800 rounded-2xl">
                                        <span class="material-symbols-rounded text-gray-800 text-7xl mb-4">event_busy</span>
                                        <p class="text-gray-500 text-xl font-brand">No hay servicios disponibles.</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($servicios as $s): ?>

                                        <div class="service-card relative group cursor-pointer bg-[#0e0e0e] border border-gray-800 rounded-2xl p-6 transition-all duration-300 overflow-hidden hover:bg-[#141414]"
                                            id="card_<?php echo $s['id_servicio']; ?>"
                                            onclick="toggleService('<?php echo $s['id_servicio']; ?>')">

                                            <input type="checkbox" name="servicios[]" value="<?php echo $s['id_servicio']; ?>"
                                                id="check_<?php echo $s['id_servicio']; ?>" class="hidden"
                                                data-cost="<?php echo $s['costo']; ?>"
                                                data-name="<?php echo htmlspecialchars($s['nombre_servicio']); ?>">

                                            <span class="material-symbols-rounded giant-watermark">verified_user</span>

                                            <div class="relative z-10 flex flex-col h-full">

                                                <div class="flex justify-between items-start gap-4 mb-4">
                                                    <div>
                                                        <h4
                                                            class="text-white font-bold text-xl leading-tight font-brand group-hover:text-cyan transition-colors">
                                                            <?php echo htmlspecialchars($s['nombre_servicio']); ?>
                                                        </h4>
                                                        <p
                                                            class="text-gray-500 text-xs uppercase tracking-widest mt-2 font-bold">
                                                            Prov: <?php echo htmlspecialchars($s['nombre_empresa']); ?>
                                                        </p>
                                                    </div>

                                                    <div id="icon_<?php echo $s['id_servicio']; ?>"
                                                        class="w-8 h-8 rounded-full border-2 border-gray-700 flex items-center justify-center transition-all bg-[#050505]">
                                                    </div>
                                                </div>

                                                <div
                                                    class="mt-auto pt-4 border-t border-gray-800/50 flex items-center justify-between">
                                                    <span class="text-gray-500 text-sm">Costo Adicional</span>
                                                    <span class="text-neon font-brand font-bold text-2xl">
                                                        + S/ <?php echo number_format($s['costo'], 2); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>

        <script>
            const baseCost = <?php echo floatval($temp['costo_estimado']) * $hours; ?>;

            function toggleService(id) {
                const checkbox = document.getElementById('check_' + id);
                const card = document.getElementById('card_' + id);
                const iconContainer = document.getElementById('icon_' + id);

                checkbox.checked = !checkbox.checked;

                if (checkbox.checked) {

                    card.classList.add('active');
                    card.classList.replace('border-gray-800', 'border-neon');
                    card.classList.add('shadow-[0_0_30px_rgba(57,255,20,0.1)]');

                    iconContainer.classList.replace('border-gray-700', 'border-neon');
                    iconContainer.classList.replace('bg-[#050505]', 'bg-neon');
                    iconContainer.innerHTML = '<span class="material-symbols-rounded text-black font-bold">check</span>';

                } else {
                    card.classList.remove('active');
                    card.classList.replace('border-neon', 'border-gray-800');
                    card.classList.remove('shadow-[0_0_30px_rgba(57,255,20,0.1)]');

                    iconContainer.classList.replace('border-neon', 'border-gray-700');
                    iconContainer.classList.replace('bg-neon', 'bg-[#050505]');
                    iconContainer.innerHTML = '';
                }

                updateTotal();
            }

            function updateTotal() {
                const checkboxes = document.querySelectorAll('input[name="servicios[]"]:checked');
                let servicesTotal = 0;

                const container = document.getElementById('selected-services-container');
                const list = document.getElementById('selected-services-list');
                const totalDisplay = document.getElementById('display-total');

                list.innerHTML = '';

                if (checkboxes.length > 0) {
                    container.classList.remove('hidden');
                    checkboxes.forEach(cb => {
                        const cost = parseFloat(cb.dataset.cost);
                        const name = cb.dataset.name;
                        servicesTotal += cost;

                        const row = document.createElement('div');
                        row.className = 'flex justify-between items-center text-gray-300 pb-2 border-b border-gray-800 last:border-0';
                        row.innerHTML = `
                            <span class="truncate w-2/3 text-white font-medium">${name}</span>
                            <span class="font-mono text-neon font-bold">+${cost.toFixed(2)}</span>
                        `;
                        list.appendChild(row);
                    });
                } else {
                    container.classList.add('hidden');
                }

                const grandTotal = baseCost + servicesTotal;

                totalDisplay.innerText = grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
        </script>
    </div> <?php require_once 'Frontend/views/layouts/footer.php'; ?>
</body>

</html>