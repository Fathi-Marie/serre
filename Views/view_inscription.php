<?php
require_once('Layout/view_header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" type="text/css" href="Content/css/pageinscription.css"/>
    <link rel="stylesheet" type="text/css" href="Content/css/index.css"/>
    <link rel="stylesheet" type="text/css" href="Content/css/stylesheet.css"/>
    <script src="Content/js/pageinscription_connexion.js" defer></script>
</head>
<body>
<h1>
    Inscription
</h1>
<div class= "formulaire">
<form action="?controller=inscription&action=sinscrire" method="POST">
    <div class="dropdown">
        <button type="button" class="dropdown-btn" id="dropdownBtn">
            Status  <span class="arrow"></span>
        </button>
        <ul class="dropdown-menu" id="dropdownMenu">
            <li data-value="Etudiant">Étudiant </li>
            <li data-value="Particuliers">Particuliers </li>
        </ul>
        <!-- Champ caché pour transmettre la valeur sélectionnée -->
        <input type="hidden" id="status" name="status" value="">
    </div>

    <br><br>
    <input type="text" id="nom" name="nom" placeholder="Nom" required>
    <input type="text" id="prenom" name="prenom" placeholder="Prénom"  required>
    <br><br>
    <div class="ContenaireTelephone">
        <label for="pays-code"></label>
        <div class="phone-input-container" id="phone-input-container">
            <select id="pays-code" name="pays-code" class="country-code-select"  >
                <option value="+33">+33</option>
                <option value="+1">+1</option>
                <option value="+44">+44</option>
                <option value="+49">+49</option>
                <option value="+91">+91</option>
                <option value="+81">+81</option>
                <option value="+86">+86</option>
                <option value="+61">+61</option>
                <option value="+34">+34</option>
            </select>
            <div class="separator"></div><!-- Séparateur -->
            <input type="tel" id="phone" name="phone" class="phone-number-input" placeholder="Numéro de téléphone" required>
        </div>
    </div>
    <br>
    <div class="input-group">
        <img src="Content/Images/email.png" alt="Email Icon" id="icon-mail" class="icon">
        <input type="mail" id="mail" name="mail" placeholder="Votre adresse mail étudiant"  >
    </div>
    <br>
    <div class="input-group">
        <img src="Content/Images/cadenas.png" alt="Lock Icon" class="icon" id="icon-lock">
        <input type="password" id="password1" name="mdp" placeholder="Votre mot de passe" >
        <img src="Content/Images/eye_icon.png" alt="Eye Icon" class="icon" id="eye-toggle" onclick="togglePassword('password1')">
    </div>
    <br>
    <div class="input-group">
        <img src="Content/Images/cadenas.png" alt="Lock Icon" class="icon" id="icon-lock">
        <input type="password" id="password2" name="mdp_confirmation" placeholder="Confirmation">
        <img src="Content/Images/eye_icon.png" alt="Eye Icon" class="icon" id="eye-toggle" onclick="togglePassword('password2')">
    </div>

    <br>

    <label>
        <div class="Donnee">
            <input type="checkbox" id="accorddonnees" name="accorddonnees"  required>
            <p>En m'inscrivant, j'accepte que AidAppart recueille et traite mes données personnelles</p>
        </div>
    </label>
    <label>
        <br>
        <div class="Condition">
            <input type="checkbox" id="accordCGU" name="accordCDU" required>
            <p>J’accepte sans réserve les Conditions Générales d’Utilisation des services AidAppart</p>
        </div>
    </label>
    <button class="button" type="submit">Je m'inscris</button>

</form>
</div>
</body>
</html>
<?php 
require_once('Layout/footer.php');
if (isset($message)) {
    afficherPopup($message);
} 
?>