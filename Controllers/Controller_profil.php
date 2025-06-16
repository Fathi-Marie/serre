<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Controller_profil extends Controller {

    public function action_default() {
        $this->action_profil();
    }

    public function action_profil() {
        $this->render("profil");
    }

    public function action_updatePassword() {
        session_start();
        $model = Model::getModel();

        if (!isset($_SESSION['user']['id'])) {
            // L'utilisateur n'est pas connecté
            header("Location: ?controller=connexion&action=connexionController");
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $errors = [];

        // Vérification des champs vides
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $errors[] = "Tous les champs sont requis.";
        }

        // Vérification de la confirmation
        if ($newPassword !== $confirmPassword) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

        // Récupération de l'utilisateur
        $user = $model->getUserById($userId);

        if (!$user || !password_verify($currentPassword, $user['mdp'])) {
            $errors[] = "Mot de passe actuel incorrect.";
        }

        // En cas d'erreur, on renvoie à la vue avec message
        if (!empty($errors)) {
            $this->render("profil", ["errors" => $errors]);
            return;
        }

        // Mise à jour du mot de passe (hashé)
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateSuccess = $model->updateUserPassword($userId, $hashedPassword);

        if ($updateSuccess) {
            $this->render("profil", ["success" => "Mot de passe mis à jour avec succès."]);
        } else {
            $this->render("profil", ["errors" => ["Une erreur est survenue lors de la mise à jour."]]);
        }
    }

    public function action_delete_user() {
        session_start();
        $model = Model::getModel();

        if (!empty($_SESSION["user"]["id"])) {
            $userId = $_SESSION["user"]["id"];

            // Désactiver l'utilisateur connecté
            $success = $model->disableUser($userId, "actif");

            if ($success) {
                session_destroy();
                http_response_code(200);
                echo json_encode([
                    "message" => "Compte désactivé avec succès.",
                    "redirect" => "?controller=connexion&action=connexionController"
                ]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Échec de la désactivation."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Utilisateur non connecté."]);
        }
    }
}

?>