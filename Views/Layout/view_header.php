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
    <link rel="stylesheet" href="Content/css/index.css" />
    <link rel="stylesheet" href="Content/css/footer.css" />
    <link rel="icon" href="Content/Images/logo.png" type="image/x-icon" />
</head>
<body>
<nav class="navbar">
    <div class="navbar-container">
        <!-- Logo -->
        <a href="?controller=accueil&action=accueilController" class="navbar-logo">
            <img src="Content/Images/AidAppart%20PNG.png" alt="Logo" />
        </a>

        <!-- Toggle Button -->
        <button class="navbar-toggle" id="navbarToggle" aria-label="Menu">☰</button>

        <div class="navbar-menu" id="navbarMenu">
            <ul class="navbar-links">
                <?php if (isset($_SESSION['idpersonne'])): ?>
                    <li><a href="?controller=capteur&action=dashboardController">Profil</a></li>
                    <li><a href="?controller=capteur&action=dashboardController">Tableau de bord</a></li>
                <?php endif; ?>
            </ul>

            <div class="navbar-right">
                <?php if (isset($_SESSION['admin'])): ?>
                    <a href="?controller=deconnexion&action=deconnexionController" class="btn btn-light btn-account">
                        Déconnexion
                    </a>
                    <a href="?controller=admin&action=admin" class="icon-translate" title="Profil admin">
                        <img src="Content/Images/Accueil/profile.png" alt="Profil admin" />
                    </a>
                <?php elseif (isset($_SESSION['prenom'])): ?>
                    <a href="?controller=deconnexion&action=deconnexionController" class="btn btn-light btn-account">
                        Déconnexion
                    </a>
                <?php else: ?>
                    <a href="?controller=connexion&action=connexionController" class="btn btn-light btn-account">
                        Connexion
                    </a>
                <?php endif; ?>
            </div>
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
