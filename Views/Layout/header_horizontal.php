<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Accueil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="Content/css/headerHorizontal.css" />
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #212529;
            color: white;
            padding-top: 1rem;
            transition: transform 0.3s ease;
            overflow-y: auto;
            z-index: 1040;
        }

        /* Cacher la sidebar sous 1798px sauf si "show" */
        @media (max-width: 1798px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
        }

        /* Main content */
        main {
            margin-left: 250px;
            padding: 1rem;
            transition: margin-left 0.3s ease;
        }

        @media (max-width: 1798px) {
            main {
                margin-left: 0;
            }
        }

        /* Hamburger button - visible dès 1798px */
        #sidebarToggle {
            display: none;
            font-size: 25px;
            background: none;
            border: none;
            color: #82CF11;
            background-color: #212811;
            padding: 2px 5px 2px 5px;
            cursor: pointer;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1050;
        }

        @media (max-width: 1798px) {
            #sidebarToggle {
                display: inline-block;
            }
        }

        /* Sidebar internal */
        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1rem 1rem 1rem;
        }

        .nav-link {
            padding-left: 0;
        }

        .nav-item img {
            margin-right: 8px;
        }

        .btn-logout {
            width: 100%;
        }

        /* Optional: overlay background when sidebar is shown */
        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.5);
            z-index: 1039;
        }

        #overlay.active {
            display: block;
        }
    </style>
</head>
<body>

<!-- Hamburger toggle button -->
<button id="sidebarToggle" aria-label="Toggle Sidebar">☰</button>
<div id="overlay"></div>

<!-- Sidebar -->
<nav id="sidebarMenu" class="sidebar bg-dark text-white">
    <div class="sidebar-header">
        <a href="?controller=accueil&action=accueilController">
            <img src="Content/Images/logo.png" alt="Logo" class="img-fluid" style="max-height: 150px;">
        </a>
    </div>
    <ul class="nav nav-pills flex-column mb-auto px-3">
        <li class="nav-item d-flex align-items-center mb-4 mt-4">
            <img src="Content/Images/perso.png" alt="Profil" style="width: 18px; height: 18px;">
            <a href="?controller=profil&action=profilController" class="nav-link text-white ps-2">Profil</a>
        </li>
        <li class="nav-item d-flex align-items-center mb-4">
            <img src="Content/Images/dash.png" alt="Dashboard" style="width: 18px; height: 18px;">
            <a href="?controller=capteur&action=dashboard" class="nav-link text-white ps-2">Tableau de bord</a>
        </li>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'Admin'): ?>
            <li class="nav-item d-flex align-items-center mb-4">
                <img src="Content/Images/gestion_perso.png" alt="Gestion utilisateurs" style="width: 18px; height: 18px;">
                <a href="?controller=admin&action=admin" class="nav-link text-white ps-2">Gestion utilisateurs</a>
            </li>
            <li class="nav-item d-flex align-items-center mb-4">
                <img src="Content/Images/capteur.png" alt="Gestion capteurs" style="width: 18px; height: 18px;">
                <a href="?controller=admindash&action=admindash" class="nav-link text-white ps-2">Gestion capteur/actionneurs</a>
            </li>
        <?php endif; ?>
    </ul>
    <div class="mt-auto p-3">
        <button class="btn btn-outline-light btn-logout" onclick="window.location.href='?controller=deconnexion&action=deconnexion';">
            Déconnexion
        </button>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('sidebarMenu');
        const toggleBtn = document.getElementById('sidebarToggle');
        const overlay = document.getElementById('overlay');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('active');
        });
    });
</script>

</body>
</html>
