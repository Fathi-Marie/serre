<?php
class Controller_deconnexion extends Controller {

    public function action_default() {
        $this->action_deconnexion();
    }

    public function action_deconnexion() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        error_log("Déconnexion appelée");  // Pour vérifier dans logs serveur

        // Vider les variables de session
        $_SESSION = [];

        // Supprimer le cookie de session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Détruire la session
        session_destroy();

        // Redirection vers connexion
        header('Location: ?controller=connexion&action=connexion');
        exit();
    }
}
?>
