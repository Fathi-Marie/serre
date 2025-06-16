<?php
require_once('Layout/header_horizontal.php');
$user = $_SESSION['user'];
$initial = strtoupper(substr($user['prenom'], 0, 1));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="Content/css/profil.css" rel="stylesheet">
    <script src="Content/js/profil.js" defer></script>
</head>
<body>
    <div class="container mt-5">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- Affichage des messages -->
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <!-- Bloc profil utilisateur -->
        <div class="d-flex align-items-center mb-4 p-3 bg-light rounded shadow-sm">
            <div class="me-3 d-flex justify-content-center align-items-center"
                 style="width: 60px; height: 60px; background-color:  #212811; color:  #a9ca59; border-radius: 50%; font-size: 24px;">
                <?= $initial ?>
            </div>
            <div>
                <h4 class="mb-0"><?= htmlspecialchars($user['prenom']) . ' ' . htmlspecialchars($user['nom']) ?></h4>
                <small class="text-muted"><?= htmlspecialchars($user['email']) ?></small>
            </div>
        </div>

        <!-- Les deux cartes -->
        <div class="row">
            <!-- Modifier mot de passe -->
            <div class="col-md-6 mb-3 pass">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Modifier le mot de passe</h5>
                        <form action="?controller=profil&action=updatePassword" method="POST">
                            <div class="mb-3">
                                <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Mot de passe actuel" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Nouveau mot de passe" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
                            </div>
                            <button type="submit" class="btn update">Mettre à jour</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Désactiver le compte -->
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Désactiver le compte</h5>
                        <p class="card-text">Cette action rendra votre compte inactif. Vous ne pourrez plus vous connecter.</p>
                        <button class="btn update" onclick="deleteUser()">Désactiver</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>