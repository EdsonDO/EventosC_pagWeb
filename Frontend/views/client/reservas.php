<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Espacio | EventosC</title>
    <link rel="stylesheet" href="Frontend/assets/css/index.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>
<body>

    <?php require_once 'Frontend/views/layouts/header.php'; ?>

    <div class="venues-wrapper">
        
        <div class="section-header">
            <h1 class="section-title">Nuestros Espacios</h1>
            <p class="section-subtitle">Explora nuestra colección de escenarios exclusivos. Desliza para ver más.</p>
        </div>

        <div class="carousel-container-relative">
            
            <button class="nav-btn nav-prev" onclick="scrollCarousel(-1)">
                <span class="material-symbols-rounded">arrow_back_ios_new</span>
            </button>

            <div class="venues-carousel" id="myCarousel">
                
                <?php 
                if (!isset($listaSedes) || !is_array($listaSedes)) {
                    $listaSedes = []; 
                }
                
                if (empty($listaSedes)): ?>
                    <p style="color: white; width: 100%; text-align: center;">No hay sedes disponibles en este momento.</p>
                <?php else: ?>
                    
                    <?php foreach ($listaSedes as $sede): ?>
                        <div class="venue-card">
                            <div class="venue-img-container">
                                <?php $bgHue = rand(200, 260); ?>
                                <div class="venue-img" style="background: linear-gradient(135deg, hsl(<?php echo $bgHue; ?>, 40%, 20%), hsl(<?php echo $bgHue; ?>, 40%, 10%)); display:flex; align-items:center; justify-content:center;">
                                    <span class="material-symbols-rounded" style="font-size: 60px; color: rgba(255,255,255,0.2);">apartment</span>
                                </div>
                                
                                <div class="capacity-badge">
                                    <span class="material-symbols-rounded" style="font-size: 16px;">groups</span>
                                    <?php echo $sede['capacidad']; ?>
                                </div>
                            </div>

                            <div class="venue-body">
                                <h2 class="venue-name"><?php echo $sede['nombre']; ?></h2>
                                <div class="venue-address">
                                    <span class="material-symbols-rounded" style="font-size:20px;">location_on</span>
                                    <?php echo $sede['direccion']; ?>
                                </div>

                                <p class="venue-description">
                                    Espacio exclusivo diseñado para eventos de alto nivel. Consultar disponibilidad para fechas especiales.
                                </p>

                                <div class="amenities">
                                    <span class="amenity"><span class="material-symbols-rounded" style="font-size:20px">wifi</span> Wifi</span>
                                    <span class="amenity"><span class="material-symbols-rounded" style="font-size:20px">ac_unit</span> A/C</span>
                                </div>

                                <a href="index.php?view=configReserva&id_sede=<?php echo $sede['id_sede']; ?>" class="btn-book">
                                    Reservar Fecha
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>
                
                <div style="min-width: 20px;"></div>
            </div>

            <button class="nav-btn nav-next" onclick="scrollCarousel(1)">
                <span class="material-symbols-rounded">arrow_forward_ios</span>
            </button>

        </div>
    </div>

    <?php require_once 'Frontend/views/layouts/footer.php'; ?>

    <script>
        function scrollCarousel(direction) {
            const container = document.getElementById('myCarousel');
            const cardWidth = 350 + 30;
            const scrollAmount = cardWidth * 1;
            
            container.scrollBy({
                left: direction * scrollAmount,
                behavior: 'smooth'
            });
        }
    </script>

</body>
</html>