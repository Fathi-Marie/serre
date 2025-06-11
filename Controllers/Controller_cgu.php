<?php
class Controller_cgu extends Controller {
    public function action_default() {
        $this->action_cgu();
    }

    public function action_cgu() {
        $data = ["erreur" => false];
        $this->render("cgu", $data);
    }
}
?>
    
