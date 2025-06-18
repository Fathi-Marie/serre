
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


}

