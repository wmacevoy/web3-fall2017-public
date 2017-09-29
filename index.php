<?php

require 'src/config.php';

class Index extends view\Page {
    function __construct() {
        parent::__construct();
        $this->title = "Home";
        global $user;
        if ($user->authenticated()) {
            $this->add(new view\Toast("Hi " . $user->name()));
        }
    }
}

$index = new Index();


$index->generate();
