<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace view;

/**
 * Description of Text
 *
 * @author wmacevoy
 */
class Toast extends Text {
    function __construct($message) 
    {
        $this->message = $message;
    }
    
    function generate() {
        echo "<div class='toast'>$this->message</div>\n";
    }
    
    function __toString() {
        ob_start();
        $this->generate();
        $value = ob_get_contents();
        ob_end_clean();
        return $value;
    }
}
