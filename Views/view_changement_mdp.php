<?php
require_once('Layout/view_header.php');?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changement de mot de passe</title>

    <?php require_once('Layout/view_header.php') ?>
    
    <link href="Content/css/connexion.css" rel="stylesheet">
    <link rel="stylesheet" href="Content/css/index.css"/>
</head>
<body>
    <div class="centre">
        <h1>Changer mon mot de passe</h1>

        <!-- Groupe de champs nouveau mot de passe -->
        <div class="input-group">
                <img src="Content/Images/cadenas.png" alt="Lock Icon" class="icon">
                <input type="password" name="password" placeholder="Nouveau mot de passe" id="password" required>
                <img src="Content/Images/eye_icon.png" alt="Eye Icon" class="eye-icon" id="eye-toggle" onclick="togglePassword()">
            </div>

        <!-- Groupe de champs confirmer le mot de passe -->
        <div class="input-group">
                <img src="Content/Images/cadenas.png" alt="Lock Icon" class="icon">
                <input type="password" name="password" placeholder="Confirmer le mot de passe" id="password" required>
                <img src="Content/Images/eye_icon.png" alt="Eye Icon" class="eye-icon" id="eye-toggle" onclick="togglePassword()">
            </div>
        <!-- Bouton de sauvegarde -->
        <button class="button">Sauvegarder</button>

        <!-- Lien retour à la connexion -->
        <a href="view_connexions.php" class="link">Retour à la connexion</a>
    </div>

<?php
require_once('Layout/footer.php');
?>
