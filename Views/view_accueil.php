<?php
require_once('Layout/view_header.php');?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="Content/css/accueil.css">
</head>
    <!-- Section principale -->
    <div class="part1">
        <h1>éclosia<br>l'art de faire éclore le vivant</h1>
        <a href="?controller=connexion&action=connexionController"><button>Ouvrir mon compte</button></a>
    </div>

    <!-- Section fonctionnalités -->
    <div class="part2">
        <div class="row">
            <div class="element">
                <img src="Content/Images/Accueil/globe.png" alt="Image 1">
                <h3>Capteur de température</h3>
            </div>
            <div class="element">
                <img src="Content/Images/Accueil/demenage.png" alt="Image 2">
                <h3>Capteur de Gaz</h3>
            </div>
            <div class="element">
                <img src="Content/Images/Accueil/charges.png" alt="Image 3">
                <h3>Capteur de luminosité</h3>
            </div>
            <div class="element">
                <img src="Content/Images/Accueil/bourse.png" alt="Image 4">
                <h3>Actionneurs</h3>
            </div>

        </div>
    </div>

    <!-- Section types de logement -->
    <div class="part3">
        <h1>Choisis le type de logement qui te convient !</h1>
        <div class="row">
            <?php foreach ($logements as $logement): ?>
                <div class="box">
                    <img src="<?php echo 'Content/Images/Accueil/' . $logement['type'] . '.jpeg'; ?>" alt="<?php echo htmlspecialchars($logement['type']); ?>">
                    <a href="?controller=pagelogement&action=pagelogement" class="icon-translate">
                        <h3><?php echo htmlspecialchars($logement["type"]); ?></h3>
                    </a>
                </div>
            <?php endforeach; ?>

        </div>
        <h1>Choisis ta ville !</h1>
        <div class="row">
            <?php foreach ($villes as $ville): ?>
                <div class=box>
                    <img src="<?php echo 'Content/Images/Accueil/' . $ville['nom_ville'] . '.jpeg'; ?>" alt="<?php echo htmlspecialchars($ville['nom_ville']); ?>">
                    <a href="?controller=pagelogement&action=pagelogement" class="icon-translate">
                        <h3><?php echo $ville['nom_ville']; ?></h3>
                    </a>
                </div>
            <?php endforeach;?>
        </div>

        <button>Lancer la recherche</button>
    </div>

    <!-- Section atouts -->
    <div class="part4">
        <div class="box-container">
            <box class="box">
                <div class="box-header">
                    <img src="Content/Images/Accueil/reactivite.png" alt="Réactivité">
                    <h3>Réactivité</h3>
                </div>
                <p>Besoin d'une réponse rapide ? Sur notre site, vous êtes informé en temps réel des nouvelles annonces correspondant à vos critères. Activez des alertes personnalisées et soyez le premier à découvrir les meilleures opportunités de logement.
                </p>
            </box>
            <div class="box">
                <div class="box-header">
                    <img src="Content/Images/Accueil/simple.png" alt="Simplicité">
                    <h3>Simplicité d'utilisation</h3>
                </div>
                <p>La recherche d'un logement ne devrait pas être compliquée. Ici, tout est fait pour vous faciliter la tâche. En quelques clics, vous trouvez, filtrez et postulez aux logements qui vous correspondent. Naviguer n’a jamais été aussi simple et intuitif !</p>
            </div>
            <div class="box">
                <div class="box-header">
                    <img src="Content/Images/Accueil/transparance.png" alt="Transparence">
                    <h3>Transparence</h3>
                </div>
                <p>Pas de surprises, que des informations claires. Chaque annonce est accompagnée de détails précis : prix total, conditions du bail, et même les avis d'anciens locataires. Vous savez exactement à quoi vous attendre avant de visiter.</p>
            </div>
            <div class="box">
                <div class="box-header">
                    <img src="Content/Images/Accueil/tel.png" alt="Accompagnement">
                    <h3>Accompagnement Personnalisé</h3>
                </div>
                <p>Vous ne savez pas par où commencer ou quels documents préparer ? Pas de panique ! Nous sommes là pour vous accompagner à chaque étape. Des conseils personnalisés et un chat en direct vous aident à poser toutes vos questions et à avancer sereinement dans votre recherche.</p>
            </div>
        </div>
    </div>
</body>
</html>
<?php
require_once('Layout/footer.php');
?>
