<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="Content/css/con.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="Content/js/pageinscription_connexion.js" defer></script>
</head>
<body>
    <div class="container-fluid h-100">
        <div class="row h-100 g-0">
            <div class="col-12 col-md-5 left-side d-flex flex-column justify-content-center  px-0" style="height: 100vh;">
                <h1 class="ps-3">
                    <a href="?controller=accueil&action=accueilController" class="no-style-link">Eclosia</a>
                </h1>
                <p class="ps-3">Vous avez déjà un compte ? Connectez-vous en cliquant sur le bouton ci-dessous.</p>
                <a href="?controller=connexion&action=Controller_connexion" class="link"><button type="button" class="btn btn-light btn-lg ms-3">Connexion</button></a>
            </div>

        <div class="col-12 col-md-7 right-side d-flex flex-column justify-content-center p-5">
            <form action="?controller=inscription&action=sinscrire" method="POST" class="login-form w-100" style="max-width: 500px; margin: auto;">
                <h2 class="mb-4 text-center">Inscription</h2>

                <!-- Nom et Prénom sur la même ligne -->
                <div class="row g-3 mb-3">
                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom" required>
                        </div>
                    </div>
                </div>

                <!-- Téléphone -->
                <div class="mb-3">
                    <div class="input-group">
                        <select id="pays-code" name="pays-code" class="form-select" style="max-width: 100px;" required>
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
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Numéro de téléphone" required>
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3 input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" id="mail" name="mail" placeholder="Votre adresse mail étudiant" required>
                </div>

                <!-- Mot de passe -->
                <div class="mb-3 input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="password1" name="mdp" placeholder="Mot de passe" required>
                    <span class="input-group-text">
            <i class="fas fa-eye" style="cursor: pointer;" onclick="togglePassword('password1')"></i>
        </span>
                </div>

                <!-- Confirmation mot de passe -->
                <div class="mb-3 input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="password2" name="mdp_confirmation" placeholder="Confirmation" required>
                    <span class="input-group-text">
            <i class="fas fa-eye" style="cursor: pointer;" onclick="togglePassword('password2')"></i>
        </span>
                </div>

                <div class="form-check d-flex align-items-start mb-2">
                    <input class="form-check-input mt-1 me-1" type="checkbox" id="accorddonnees" name="accorddonnees" required>
                    <label class="form-check-label" for="accorddonnees">
                        En m'inscrivant, j'accepte que Eclosia recueille et traite mes données personnelles
                    </label>
                </div>

                <div class="form-check d-flex align-items-start mb-4">
                    <input class="form-check-input mt-1 me-2" type="checkbox" id="accordCGU" name="accordCDU" required>
                    <label class="form-check-label" for="accordCGU">
                        J’accepte sans réserve les Conditions Générales d’Utilisation des services Eclosia
                    </label>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-light btn-lg">Je m'inscris</button>
                </div>
            </form>

        </div>
</div>
</body>
</html>
