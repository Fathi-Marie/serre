<?php
require_once('Layout/view_header.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('Layout/view_header.php') ?>
    <link href="Content/css/connexion.css" rel="stylesheet">
    <link rel="stylesheet" href="Content/css/index.css"/>
    <script src="Content/js/oeil_mot_de_passe.js" defer></script>
</head>
<title>Mot de passe oublié</title>
<body>
    <div class="centre">
        <h1>Mot de passe oublié</h1>
        <form action="?controller=mdp_oublie&action=reset_mdp" method="POST">
            <!-- Groupe de champs email -->
            <div class="input-group">
                <img src="Content/Images/email.png" alt="Email Icon" class="icon">
                <input type="email" name="email" placeholder="Renseignez votre adresse mail" required>
            </div>
            <!-- Liens -->
            <div class="links">
                <a href="?controller=inscription&action=inscriptionController" class="link">Première connexion ? Je crée un compte AidAppart</a>
                <a href="?controller=connexion&action=connexionController" class="link">Retour à la connexion</a>
            </div>
            <!-- Bouton -->
            <button type="submit" class="button">Me Connecter</button>
        </form>
    </div>
    <?php
    require_once('Layout/footer.php');
    ?>
</html>
