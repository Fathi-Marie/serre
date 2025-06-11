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
            $idpersonne= $personne['id']; 
            session_start();
            $_SESSION['idpersonne'] = $idpersonne; 
            $_SESSION['prenom'] = $personne['prénom'];
            $data = ["erreur" => false]; 

//            if ($model->hasRole($idpersonne, 'Admin')) {
//                header('Location: ?controller=admin&action=admin');
//                return;
//            }
//            if ($model->hasRole($idpersonne, 'Propriétaire')) {
//                header('Location: ?controller=acceuil&action=acceuil');
//                return;
//            }

            header('Location: ?controller=acceuil&action=acceuilController');
        } 
        else { 
            $data = ["erreur" => true]; 
            $this->render("connexion",$data); 
            echo "E-mail ou mot de passe incorect."; 
            }
        }    
} 
?>