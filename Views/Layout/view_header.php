<?php
// Démarrer la session proprement une seule fois
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Eclosia - Navigation</title>
    <link rel="stylesheet" href="Content/css/stylesheet.css" />
    <link rel="stylesheet" href="Content/css/footer.css" />
    <link rel="icon" href="Content/Images/logo.png" type="image/x-icon" />
</head>
<body>
<nav class="navbar">
    <div class="navbar-container">

        <!-- Logo à gauche -->
        <a href="?controller=accueil&action=accueilController" class="navbar-logo">
            <img src="Content/Images/logo.png" alt="Logo" style="width: 100px; height: 100px" />
        </a>

        <!-- Liens au centre -->
        <ul class="navbar-links">
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'Admin'): ?>
                <li><a href="?controller=capteur&action=dashboardController">Profil</a></li>
                <li><a href="?controller=capteur&action=dashboardController">Tableau de bord</a></li>
            <?php endif; ?>
        </ul>

        <!-- Connexion/Déconnexion à droite -->
        <div class="navbar-right">
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'Admin'): ?>
                <a href="?controller=deconnexion&action=deconnexionController" class="btn btn-light btn-account">Déconnexion</a>
            <?php elseif (isset($_SESSION['prenom'])): ?>
                <a href="?controller=deconnexion&action=deconnexionController" class="btn btn-light btn-account">Déconnexion</a>
            <?php else: ?>
                <a href="?controller=connexion&action=connexionController" class="btn btn-light btn-account">Connexion</a>
            <?php endif; ?>
        </div>

    </div>
</nav>

<script>
    document.getElementById('navbarToggle').addEventListener('click', function () {
        const menu = document.getElementById('navbarMenu');
        menu.classList.toggle('active');
    });
</script>
</body>
</html>
