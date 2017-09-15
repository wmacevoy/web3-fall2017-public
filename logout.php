<?php

require 'src/config.php';

class Logout extends view\Page {
    function generate() {
        global $user;
        $user->logout();
        $this->redirect("index.php");
    }
}

(new Logout())->generate();