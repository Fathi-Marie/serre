<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Content/css/con.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <title>Title</title>
</head>
<body>
<div class="container-fluid h-100">
    <div class="row h-100 g-0">
        <div class="col-12 col-md-5 left-side d-flex flex-column justify-content-center  px-0" style="height: 100vh;">
            <h1 class="ps-3">
                <a href="?controller=accueil&action=accueilController" class="no-style-link">Eclosia</a>
            </h1>
            <p class="ps-3">Vous êtes nouveau ici ? Veuillez vous inscrire en cliquant sur le bouton ci-dessous.</p>
            <a href="?controller=inscription&action=Controller_inscription" class="link"><button type="button" class="btn btn-light btn-lg ms-3">Inscription</button></a>
        </div>

        <div class="col-12 col-md-7 right-side d-flex flex-column justify-content-center p-5">
            <form  action="?controller=connexion&action=seconnecter" method="POST" class="login-form w-100" style="max-width: 400px; margin: auto;">
                <h2 class="mb-4 text-center">Se connecter</h2>

                <div class="mb-3 input-group">
                        <span class="input-group-text" id="email-icon">
                            <i class="fas fa-envelope"></i>
                        </span>
                    <input type="email" class="form-control" id="email" placeholder="Email" name="email" aria-label="Email" aria-describedby="email-icon" required>
                </div>

                <div class="mb-4 input-group">
                        <span class="input-group-text" id="password-icon">
                            <i class="fas fa-lock"></i>
                        </span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" aria-label="Mot de passe" aria-describedby="password-icon" required>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-light btn-lg">Connexion</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>