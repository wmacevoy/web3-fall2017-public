<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace view;

/**
 * Description of Page
 *
 * @author wmacevoy
 */
class Page extends Text {
    function __construct() {
        parent::__construct();
        array_push($this->parts, new TopMenu());
    }
    function generate() {
        $this->doctype();
        $this->html();
    }
    
    public $doctype = "html";
    
    function doctype() {
        echo "<!DOCTYPE $this->doctype>\n";
    }
    
    function redirect($location) {
        $this->doctype();
        echo <<<END
<head>
  <meta http-equiv="refresh" content="0; url=$location" />
</head>
END;
    }
    
    function html() {
        echo "<html>\n";
        $this->head();
        $this->body();
        echo "</html>\n";
    }
    
    function head() {
        echo "<head>\n";
        $this->title();
        echo "</head>\n";
                
    }
    
    public $title;
    
    function title() {
        if (isset($this->title)) {
            echo "<title>$this->title</title>\n";
        }
    }
    
    public $parts = array();
    
    function body() {
        echo "<body>\n";
        foreach ($this->parts as $part) {
            $part->generate();
        }
        echo "</body>\n";
    }
}
