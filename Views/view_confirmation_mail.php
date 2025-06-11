<?php
require_once('Layout/view_header.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de votre e-mail</title>
</head>
<body>
    <h1>Un e-mail a été envoyé !</h1>
    <p>Veuillez vérifier votre boîte mail et entrer le code reçu pour confirmer votre adresse.</p>
    <form action="controller=inscription&action=validerCodeVerification" method="POST">
        <label for="verification_code">Code de vérification :</label>
        <input type="text" name="verification_code" id="verification_code" required>
        <button type="submit">Vérifier</button>
    </form>
<?php
require_once('Layout/footer.php');
?>