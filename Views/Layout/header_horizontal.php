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
    <script src="Content/js/headerHorizontal.js" defer></script>
    <link rel="stylesheet" href="Content/css/headerHorizontal.css">
</head>
<body>
<nav id="sidebarMenu" class="sidebar bg-dark text-white mr-5">
    <div class="sidebar-header d-flex justify-content-between align-items-center p-3">
        <a href="?controller=accueil&action=accueilController"><img src="Content/Images/logo.png" alt="Logo" class="img-fluid" style="max-height: 150px;"></a>
        <button id="sidebarToggle" class="btn btn-outline-light d-md-none">
            ☰
        </button>
    </div>
    <ul class="nav nav-pills flex-column mb-auto px-3">
        <li class="nav-item d-flex align-items-center mb-5 mt-5">
            <img src="Content/Images/perso.png" alt="Profil" style="width: 18px; height: 18px; margin-right: 8px;">
            <a href="?controller=profil&action=?profilController" class="nav-link text-white p-0">Profil</a>
        </li>
        <li class="nav-item d-flex align-items-center mb-5">
            <img src="Content/Images/dash.png" alt="Dashboard" style="width: 18px; height: 18px; margin-right: 8px;">
            <a href="?controller=capteur&action=dashboar" class="nav-link text-white p-0">Tableau de bord</a>
        </li>
        <?php if ($_SESSION['user']['role'] === 'Admin'): ?>
            <li class="nav-item d-flex align-items-center mb-5">
                <img src="Content/Images/gestion_perso.png" alt="Dashboard" style="width: 18px; height: 18px; margin-right: 8px;">
                <a href="?controller=admin&action=admin" class="nav-link text-white p-0">Gestion utilisateurs</a>
            </li>
            <li class="nav-item d-flex align-items-center mb-3">
                <img src="Content/Images/capteur.png" alt="Dashboard" style="width: 18px; height: 18px; margin-right: 8px;">
                <a href="?controller=admindash&action=admindash" class="nav-link text-white p-0">Gestion capteur/actionneurs</a>
            </li>
        <?php endif; ?>
    </ul>
    <div class="mt-auto p-3">
        <a href="?controller=deconnexion&action=deconnexion">
            <button class="btn w-100">Déconnexion</button>
        </a>
    </div>
</nav>

