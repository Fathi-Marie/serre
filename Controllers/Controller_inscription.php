<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class Controller_inscription extends Controller {

    private $verificationCode;

    public function action_default() {
        $this->action_inscription();
    }

    public function action_inscription() {
        $data = ["erreur" => false];
        $this->render("inscription", $data);
    }
    

    public function action_sinscrire() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = Model::getModel();
            // Récupération des données POST
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $telephone = $_POST['phone'];
            $actif = True; // Par défaut
            $email = $_POST['mail'];
            $mdp = $_POST['mdp'];
            $mdp_confirmation=$_POST["mdp_confirmation"];

            // Validation des champs obligatoires
            if (empty($nom) || empty($prenom) || empty($telephone) || empty($email) || empty($mdp)) {
                $data = ["message" => "Tous les champs sont obligatoires."];
                $this->render("inscription", $data);
                exit;
            }
            
            // Vérification de la sécurité du mot de passe
            if (!preg_match('/[A-Z]/', $mdp)) { // Au moins une majuscule
                $data = ["message" => "Le mot de passe doit contenir au moins une lettre majuscule."];
                $this->render("inscription", $data);
                exit;
            }

            if (strlen($mdp) < 8) { // Longueur minimale de 8 caractères
                $data = ["message" => "Le mot de passe doit contenir au moins 8 caractères."];
                $this->render("inscription", $data);
                exit;
            }
            if (!preg_match('/[0-9]/', $mdp)) { // Au moins un chiffre
                $data = ["message" => "Le mot de passe doit contenir au moins un chiffre."];
                $this->render("inscription", $data);
                exit;
            }

            // Vérification de l'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data = ["message" => "L'adresse email est invalide."];
                $this->render("inscription", $data);
                exit;
            }

            // Vérification que les mots de passe soit identique
            if ($mdp_confirmation!=$mdp) {
                $data = ["message"=> "Les deux mots de passe doivent être identique"];
                $this->render("inscription", $data);
                exit;
            }

            // Vérification de l'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data = ["message" => "L'adresse email est invalide."];
                $this->render("inscription", $data);
                exit;
            }

            // Téléphone
            if (strlen($telephone) != 9 && strlen($telephone) != 10) {
                $data = ["message" => "Le numéro de téléphone doit contenir le bon nombre de chiffres."];
                $this->render("inscription", $data);
                exit;
            }

            // Vérification de l'existence d'un utilisateur
            $UtilisateurExistant = $model->doublon($email, $telephone);
            if ($UtilisateurExistant) {
                $data = ["message" => "Email ou téléphone déjà existant."];
                $this->render("inscription", $data);
                exit;
            }

            // Insertion dans la base de données
            $reussie = $model->insertPersonne($nom, $prenom, $email, $actif, $telephone, $mdp);
            if ($reussie) {
                session_start();
                $_SESSION['idpersonne'] = $model->getIdPersonne($email);
                $_SESSION['prenom'] = $prenom;
                header('Location: ?controller=capteur&action=dashboardController');
            } else {
                echo "Erreur lors de l'inscription.";
            }
        }
    }
}   

?>