<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: '#050505',
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="Frontend/assets/css/admin.css">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&display=swap" rel="stylesheet">
</head>

<body>

    <header class="main-header">
        <nav>
            <div class="nav-group">
                <a href="index.php?view=dashboard" class="nav-btn">
                    <span class="material-symbols-rounded">dashboard</span>
                    Dashboard
                </a>

                <a href="index.php?view=home" target="_blank" class="nav-btn">
                    <span class="material-symbols-rounded">public</span>
                    Ver Web
                </a>
            </div>

            <a href="index.php?view=dashboard">
                <img src="Frontend/assets/img/eventosclogo.png" alt="EventosC" class="logo-img">
            </a>

            <div class="nav-group">
                <span class="nav-btn" style="cursor: default;">
                    <span class="material-symbols-rounded">manage_accounts</span>
                    Admin
                </span>

                <a href="index.php?view=logout" class="nav-btn btn-logout">
                    <span class="material-symbols-rounded">logout</span>
                    Salir
                </a>
            </div>
        </nav>
    </header>

    <div class="admin-container">