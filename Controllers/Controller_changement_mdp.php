<?php
class Controller_changement_mdp extends Controller {
    public function action_default() {
        $this->action_changement_mdp();
    }

    public function action_changement_mdp() {
        $data = ["erreur" => false];
        $this->render("changement_mdp", $data);
    }
}
?>