<?php
class Controller_mdp_oublie extends Controller {
    public function action_default() {
        $this->action_mdp_oublie();
    }

    public function action_mdp_oublie() {
        // Afficher la page de demande de réinitialisation de mot de passe
        $data = ["erreur" => false];
        $this->render("mdp_oublie", $data);
    }

    public function action_reset_mdp() {
        // Vérifiez si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Connexion au modèle
            $model = Model::getModel();

            // Récupérer l'email envoyé par le formulaire et valider l'email
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

            // Si l'email n'est pas valide
            if (!$email) {
                echo "Adresse e-mail invalide.";
                return;
            }

            // Vérifier si l'email existe dans la base de données via le modèle
            $userId = $model->email_exist($email);
            
            if ($userId) {
                // Générer un jeton unique pour la réinitialisation
                /*
                $token = bin2hex(random_bytes(16));
                $resetLink = "https://AidAppart.com/reset_password.php?token=$token";
                */
                try {
                    // Enregistrer le jeton dans la base de données avec une expiration (par exemple, 1 heure)
                    /*
                    $stmt = $model->getDb()->prepare(
                        "INSERT INTO Password_Reset_Tokens (user_id, token, expires_at) VALUES (?, ?, ?)"
                    );
                
                    $expiresAt = date("Y-m-d H:i:s", strtotime('+1 hour'));
                    $stmt->execute([$userId, $token, $expiresAt]);
                    */

                    // Préparer le message
                    $subject = "Réinitialisation de votre mot de passe";
                    $message = "Bonjour,\n\nCliquez sur le lien suivant pour réinitialiser votre mot de passe : \n\nCe lien est valable pendant 1 heure.\n\nMerci.";
                    $headers = "From: hippolyte.danre@gmail.com";
                    print_r($email);
                    print_r($subject);
                    print_r($message);
                    print_r($headers);

                    // Envoyer l'email
                    if (mail($email, $subject, $message, $headers)) {
                        echo "Un email de réinitialisation a été envoyé à $email.";
                    } else {
                        echo "Échec de l'envoi de l'email.";
                    }

                } catch (PDOException $e) {
                    echo "Erreur lors de l'enregistrement du jeton : " . $e->getMessage();
                }
            } else {
                // Si l'email n'existe pas dans la base de données
                echo "Cet email n'existe pas dans notre base de données.";
            }
        } else {
            echo "Aucune donnée reçue.";
        }
    }
}
?>