
<?php
class Controller_capteur extends Controller {

    public function action_construct() {
        $this->action_default();
    }

    public function action_default() {
        $this->action_dashboard();
    }
    public function action_dashboard() {
        $model = Model::getModel();

        $tempHumData = $model->getHistoricalDataByType('Température/Humidité');
        $luminositeData = $model->getHistoricalDataByType('Luminosité');
        $gazData = $model->getHistoricalDataByType('Gaz');
        $actionneursState = $model->getActuatorsState();
        $tempInt = $model->getDerniereTemperatureInterieure();

        $data = [
            'tempHumData' => $tempHumData,
            'luminositeData' => $luminositeData,
            'gazData' => $gazData,
            'actionneursState' => $actionneursState,
            'temperatureInterieure' => $tempInt,
            'erreur' => false
        ];

        $this->render('capteur', $data);
    }



    public function get_capteurs_with_last_values()
    {
        $model = Model::getModel();
        $capteurs = $model->selectAllFromTable('capteurs');

        foreach ($capteurs as &$capteur) { // <-- note le &
            $lastValue = $model->getLastValue($capteur['id_sensor']);
            if ($lastValue === false) {
                $capteur['last_value'] = null;
            } else {
                $capteur['last_value'] = $lastValue;
            }
        }
        return $capteurs;
    }

    public function action_temperatureTest() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $model = Model::getModel();
        $tempInt = $model->getDerniereTemperatureInterieure();

        if ($tempInt === null) {
            echo "Impossible de récupérer la température intérieure.";
        } else {
            echo "Température intérieure : " . $tempInt . " °C";
        }
    }

}

