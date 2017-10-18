<?php

require 'src/config.php';

class Calc extends view\Page {
    function __construct() {
        parent::__construct();
        $this->title = "Calc";
    }

    function head() {
        echo "<head>\n";
        echo '<script src="https://code.jquery.com/jquery-3.2.1.min.js"
                      integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
                      crossorigin="anonymous\"></script>';
        echo '<script src="calc.js"></script>';
        $this->title();
        echo "</head>\n";
    }

    function body() {
        echo "<body>\n";
        $this->form();
        echo "</body>\n";
    }
    
    function form() {
        $html = <<<END
<form method="POST" action="login.php">
  <input type="text" id="x" name="x" placeholder="x"  value="" />
+
  <input type="text" id="y" name="y" placeholder="y"  value="" />
=
  <input type="text" id="z" name="z" placeholder="z"  value="" />
</form>
END;
        echo $html;
    }
    

}

$page = new Calc();
$page->generate();
