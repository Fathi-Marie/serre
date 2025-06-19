
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Controller_capteur extends Controller {

    public function action_construct() {
        $this->action_default();
    }

    public function action_default() {
        $this->action_dashboard();
    }
    public function action_dashboard() {
        $model = Model::getModel();

        $tempHumData = $model->getHistoricalDataByType('temperature');
        $luminositeData = $model->getHistoricalDataByType('luminosite');
        $humidite = $model->getHistoricalDataByType('humidite');
        $humidite_sol = $model->getHistoricalDataByType('humidite_sol');
        $actionneursState = $model->getActionneursState();
        $tempInterieure = $model->getDerniereTemperatureInterieure();

        $data = [
            'tempHumData' => $tempHumData,
            'luminositeData' => $luminositeData,
            'humidite' => $humidite,
            'humidite_sol' => $humidite_sol,
            'actionneursState' => $actionneursState,
            'temperatureInterieure' => $tempInterieure,
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


}

