<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace view;

/**
 * Description of TopMenu
 *
 * @author wmacevoy
 */
class TopMenu extends Text {
    public $items = array("Home" => "index.php", "Page One" => "one.php");
    function __construct() {
        parent::__construct();
    }
    function generate() {
        echo "<nav id=\"top_menu\">\n";
        foreach ($this->items as $name => $link) {
            echo "<a href=\"$link\">$name</a>\n";
        }
        echo "</nav>\n";
    }
    //put your code here
}
