<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Controller_admindash extends Controller {
    public function action_default() {
        $this->action_admindash();
    }

    public function action_admindash() {
        $model = Model::getModel();
        $capteurs = $model->getCapteursWithLimites();
        $actionneurs = $model->getAllActionneursWithCurrentState();
        $data = ["erreur" => false,
            "actionneurs" => $actionneurs,
            "capteurs" => $capteurs];
        $this->render("admindash", $data);

    }

    public function action_add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $unite = $_POST['unite'] ?? '';
            $is_actif = (int)$_POST['is_actif'];
            $model = Model::getModel();
            $model->addCapteur($nom, $unite, $is_actif);
        }

        header("Location: ?controller=admindash&action=admindash");
        exit;
    }

    public function action_delete() {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $model = Model::getModel();
            $model->deleteCapteur($id);
        }

        header("Location: ?controller=admindash&action=admindash");
        exit;
    }

    public function action_add_actuator() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'] ?? '';
            $etat = isset($_POST['etat']) ? (int)$_POST['etat'] : 0;

            $model = Model::getModel();
            $model->addActuator($type, $etat);
        }

        header("Location: ?controller=admindash&action=admindash");
        exit;
    }


    public function action_delete_actuator() {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $model = Model::getModel();
            $model->deleteActuator($id);
        }

        header("Location: ?controller=admindash&action=admindash");
        exit;
    }

    public function action_get_graph_data() {
        header('Content-Type: application/json');

        if (!isset($_GET['id'])) {
            echo json_encode(['error' => 'ID du capteur manquant']);
            exit;
        }

        $capteurId = (int)$_GET['id'];

        $model = Model::getModel();

        $donnees = $model->getMesuresByCapteurId($capteurId);
        $limites = $model->getSensorLimitById($capteurId);

        if (!$donnees || count($donnees) === 0) {
            echo json_encode(['labels' => [], 'values' => [], 'lim_max' => $limites['lim_max'] ?? null]);
            exit;
        }

        $labels = array_column($donnees, 'date_heure');
        $values = array_map('floatval', array_column($donnees, 'valeur'));

        echo json_encode([
            'labels' => $labels,
            'values' => $values,
            'lim_max' => isset($limites['lim_max']) ? floatval($limites['lim_max']) : null
        ]);
        exit;
    }



    public function action_updateLimites() {
        $model = Model::getModel();
        $id_sensor = $_POST['id'];
        $lim_min = $_POST['lim_min'] !== '' ? $_POST['lim_min'] : null;
        $lim_max = $_POST['lim_max'] !== '' ? $_POST['lim_max'] : null;

        $model->updateLimites($id_sensor, $lim_min, $lim_max);
        header('Location: ?controller=admindash&action=admindash');
        exit;
    }

    public function action_getSensorData() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID manquant']);
            exit;
        }

        $model = Model::getModel();

        // Exemple : récupérer les données du capteur (date, valeur)
        $dataPoints = $model->getCapteurDataById($id);  // À créer : doit renvoyer tableau de ['date_heure', 'valeur']

        $capteurInfo = $model->getCapteurById($id); // pour récupérer lim_max

        $labels = [];
        $values = [];
        foreach ($dataPoints as $point) {
            $labels[] = $point['date_heure'];
            $values[] = (float)$point['valeur'];
        }

        header('Content-Type: application/json');
        echo json_encode([
            'labels' => $labels,
            'values' => $values,
            'lim_max' => (float)$capteurInfo['lim_max']
        ]);
        exit;
    }


}


?>