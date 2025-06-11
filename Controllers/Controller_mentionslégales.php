<?php
class Controller_mentionslégales extends Controller {
    public function action_default() {
        $this->action_mentionslégales();
    }

    public function action_mentionslégales() {
        $data = ["erreur" => false];
        $this->render("mentionslégales", $data);
    }
}
?>
    
