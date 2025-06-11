<?php

class Controller_accueil extends Controller {
    public function action_default() {
        $this->action_accueil();
    }

    public function action_accueil() {
        //$model = Model::getModel();

        $this->render("accueil");

    }
}


?>