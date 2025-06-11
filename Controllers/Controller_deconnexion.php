<?php
class Controller_deconnexion extends Controller {
    public function action_default() {
        $this->action_deconnexion();
    }
  
    public function action_deconnexion() {
        if (!isset($_SESSION)) {
            session_start();
        }
        session_destroy();
        $data = ["erreur" => false];
        header('Location: ?controller=accueil&action=accueil');
    }     
} 
?>