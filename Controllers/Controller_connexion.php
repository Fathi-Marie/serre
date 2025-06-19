<?php
class Controller_connexion extends Controller {
    public function action_default() {
        $this->action_connexion();
    }

    public function action_connexion() {
        $data = ["erreur" => false];
        $this->render("connexion", $data);
    }

    public function action_seconnecter() { 
        $model = Model::getModel(); 
        $email = $_POST['email']; 
        $mdp = $_POST['password']; 
        $personne = $model->personneConnexion($email);

        if  ($personne && password_verify($mdp, $personne['mdp'])) {

            if ($personne['etat'] == 'actif') {
                $data = ["erreur" => true, "message" => "Compte inactif. Veuillez contacter l'administrateur."];
                $this->render("connexion", $data);
                return;
            }

            session_start();
            $_SESSION['user'] = [
                'id' => $personne['id'],
                'prenom' => $personne['prénom'],
                'nom' => $personne['nom'],
                'email' => $personne['email'],
                'role' => $personne['role']
            ];

            header('Location: ?controller=capteur&action=?dashboardController');
        } 
        else { 
            $data = ["erreur" => true]; 
            $this->render("connexion",$data); 
            echo "E-mail ou mot de passe incorect."; 
            }
        }    
} 
?>