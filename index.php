<?php

require 'src/config.php';

class Index extends view\Page {
    function __construct() {
        parent::__construct();
        $this->title = "Home";
    }
}

$index = new Index();


$index->generate();
