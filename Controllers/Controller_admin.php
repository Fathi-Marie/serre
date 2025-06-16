<?php

class Controller_admin extends Controller {
    public function action_default() {
        $this->action_admin();
    }

    public function action_admin() {
        $users = $this->get_users_with_roles();
        $data = ["erreur" => false,
            "users" => $users];
        $this->render("admin", $data);
    }

    public function get_users_with_roles() {
        $model = Model::getModel();
        $users = $model->selectAllFromTable('Personne');
        $usersWithRoles = [];

        foreach ($users as $user) {
            $roles = $model->getUserRoles($user['id']);
            $user['roles'] = array_column($roles, 'nom');
            $usersWithRoles[] = $user;
        }
        return $usersWithRoles;
    }

    public function action_assign_role() {
        $model = Model::getModel();
        $data = json_decode(file_get_contents('php://input'), true);
        $model->assignRole($data['id'], $data['role']);
    }

    public function action_delete_user() {
        $model = Model::getModel();
        $data = json_decode(file_get_contents('php://input'), true);
        if (!empty($data['id'])) {
            $success = $model->disableUser($data['id'], "actif");
            if ($success) {
                http_response_code(200);
                echo json_encode(["message" => "Utilisateur désactivé"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Erreur lors de la désactivation"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID utilisateur manquant"]);
        }
    }

    public function disableUser($userId, $etat) {
        $stmt = $this->db->prepare("UPDATE Personne SET etat = :etat WHERE id = :userId");
        $stmt->execute([
            'etat' => $etat,
            'userId' => $userId
        ]);
    }

    public function action_toggle_role() {
        session_start();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['id']) || !isset($input['role'])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Données invalides."]);
            exit;
        }

        $userId = $input['id'];
        $newRole = $input['role'];

        $model = Model::getModel();

        $success = $model->updateUserRole($userId, $newRole);

        if ($success) {
            echo json_encode(["success" => true, "message" => "Rôle mis à jour avec succès."]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour du rôle."]);
        }
        exit;
    }


}

?>