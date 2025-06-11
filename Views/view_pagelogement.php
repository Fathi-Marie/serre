<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Content/css/pagelogement.css">
    <script src="Content/js/pagelogement.js" defer></script>
    <title>Liste des Logements</title>
</head>
<body>
<?php include 'Layout/view_header.php'; ?>
    <div class="search-selection">
        <h1> Trouver un logement étudiant </h1>
        <p> Plus de 200 logements étudiants dispos immédiatement en studio, résidence et coloc à Paris chez AidAppart, n°1 du logement </br>étudiant. À partir de 649€/mois. </p>
        <div class="search-bar">
            <input id="search-type" type="text" placeholder="Type de logement">
            <input type="text" placeholder="Surface">
            <input id="search-loyer" type="text" placeholder="Loyer max">
            <button id="recherche-log" class="button"><span class="button-text">Rechercher</span></button>
        </div>
    </div>
    <div class="row">
        <div class="filters">
            <h2>Tous les filtres</h2>
            <h3>Nombre de pièces</h3>
            <select id="nb-pieces">
                <option value="">Tous</option>
                <?php foreach ($nbPieces as $piece): ?>
                    <option value="<?php echo $piece['nb_pieces']; ?>"><?php echo $piece['nb_pieces']; ?></option>
                <?php endforeach; ?>
            </select>
            <h3>Type de logement</h3>
            <select id="type-logement" >
                <option value="">Tous</option>
                <?php foreach ($types as $type): ?>
                    <option value="<?php echo $type["type"]; ?>"><?php echo $type["type"]; ?></option>
                <?php endforeach; ?>
            </select>
            <h3>Surface</h3>
            <input type="number" id="surface-min" placeholder="<?php echo $minMaxSurface['min'] . ' ( m²)'; ?>" min="<?php echo $minMaxSurface['min']; ?>">
            <input type="number" id="surface-max" placeholder="<?php echo $minMaxSurface['max'] . ' ( m²)'; ?>" max="<?php echo $minMaxSurface['max']; ?>">
            <h3>Loyer</h3>
            <input type="number" id="loyer-min" placeholder="<?php echo $minMaxLoyer['min'] . ' (€)'; ?>" max="<?php echo $minMaxLoyer['max']; ?>">
            <input type="number" id="loyer-max" placeholder="<?php echo $minMaxLoyer['max'] . ' (€)'; ?>" min="<?php echo $minMaxLoyer['max']; ?>">
            <h3>Charges</h3>
            <input type="number" id="charges-min" placeholder="<?php echo $minMaxCharges['min'] . ' (€)'; ?>" max="<?php echo $minMaxCharges['max']; ?>">
            <input type="number" id="charges-max" placeholder="<?php echo $minMaxCharges['max'] . ' (€)'; ?>" min="<?php echo $minMaxCharges['max']; ?>">
            <li><input type="checkbox" id="meuble"> Meublé</li>
            <li><input type="checkbox" id="wifi"> WiFi</li>
            <li><input type="checkbox" id="accessible-pmr"> Accessible PMR</li>
            <li><input type="checkbox" id="parking"> Parking</li>
        </div>
        <div class="listings">
        </div>
    </div>

   <?php
   require_once('Layout/footer.php');
   ?>
</body>
</html>