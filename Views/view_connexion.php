<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>

    <?php require_once('Layout/view_header.php') ?>
    
    <link href="Content/css/connexion.css" rel="stylesheet">
    <script src="Content/js/oeil_mot_de_passe.js" defer></script>
</head>
<body>
    <div class="centre">
        <h1>JE ME CONNECTE</h1>

        <form action="?controller=connexion&action=seconnecter" method="POST">
            <!-- Groupe de champs email -->
            <div class="input-group">
                <img src="Content/Images/email.png" alt="Email Icon" class="icon">
                <input type="email" name="email" placeholder="Votre adresse email" required>
            </div>

            <!-- Groupe de champs mot de passe -->
            <div class="input-group">
            <img src="Content/Images/cadenas.png" alt="Lock Icon" class="icon">
            <input type="password" placeholder="Votre mot de passe" name="password" id="password" required>
            <img src="Content/Images/eye_icon.png" alt="Eye Icon" class="eye-icon" id="eye-toggle" onclick="togglePassword('password')">
            </div>

            <!-- Liens -->
            <div class="links">
                <a href="?controller=inscription&action=Controller_inscription" class="link">Première connexion ? Je crée un compte AidAppart</a>
                <a href="?controller=mdp_oublie&action=mdp_oublieController" class="link">Mot de passe oublié ?</a>
            </div>

            <!-- Bouton -->
            <button type="submit" class="button">Me Connecter</button>
        </form>
    </div>

   <?php
   require_once('Layout/footer.php');
   ?>
