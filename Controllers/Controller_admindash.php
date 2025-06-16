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
        $actionneurs = $model->selectAllFromTable('actuators');
        $data = ["erreur" => false,
            "actionneurs" => $actionneurs,
            "capteurs" => $capteurs];
        $this->render("admindash", $data);

    }

    public function action_add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'] ?? '';
            $name = $_POST['name'] ?? '';
            $unit = $_POST['unit'] ?? '';

            $model = Model::getModel();
            $model->addCapteur($type, $name, $unit);
        }

        header("Location: ?controller=admindash&action=admindash");
        exit;
    }

    public function action_delete() {
        if (isset($_GET['id_sensor'])) {
            $id = intval($_GET['id_sensor']);
            $model = Model::getModel();
            $model->deleteCapteur($id);
        }

        header("Location: ?controller=admindash&action=admindash");
        exit;
    }

    public function action_add_actuator() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'] ?? '';
            $name = $_POST['name'] ?? '';
            $state = $_POST['state'] ?? '';

            $model = Model::getModel();
            $model->addActuator($type, $name, $state);
        }

        header("Location: ?controller=admindash&action=admindash");
        exit;
    }

    public function action_delete_actuator() {
        if (isset($_GET['id_actuator'])) {
            $id = intval($_GET['id_actuator']);
            $model = Model::getModel();
            $model->deleteActuator($id);
        }

        header("Location: ?controller=admindash&action=admindash");
        exit;
    }

    public function action_get_graph_data() {
        header('Content-Type: application/json');

        if (!isset($_GET['id_sensor'])) {
            echo json_encode(['error' => 'ID du capteur manquant']);
            exit;
        }

        $id_sensor = (int)$_GET['id_sensor'];

        $model = Model::getModel();
        $donnees = $model->getDataBySensorId($id_sensor);
        $limites = $model->getSensorLimitById($id_sensor);

        if (!$donnees || count($donnees) === 0) {
            echo json_encode(['labels' => [], 'values' => [], 'lim_max' => null]);
            exit;
        }

        $labels = array_column($donnees, 'date');
        $values = array_column($donnees, 'value');

        echo json_encode([
            'labels' => $labels,
            'values' => $values,
            'lim_max' => isset($limites['lim_max']) ? floatval($limites['lim_max']) : null
        ]);
        exit;
    }


    public function action_updateLimites() {
        $model = Model::getModel();
        $id_sensor = $_POST['id_sensor'];
        $lim_min = $_POST['lim_min'] !== '' ? $_POST['lim_min'] : null;
        $lim_max = $_POST['lim_max'] !== '' ? $_POST['lim_max'] : null;

        $model->updateLimites($id_sensor, $lim_min, $lim_max);
        header('Location: ?controller=admindash&action=admindash');
        exit;
    }

}


?>