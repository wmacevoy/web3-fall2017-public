<?php

require 'src/config.php';

class ChangePassword extends view\Page {
    public $oldpass;
    public $newpass;    
    
    function generate() {
        global $user;
        $this->post();
        parent::generate();
    }
    
    function posted() {
        return isset($_POST["oldpass"]) && isset($_POST["newpass"])
            && isset($_POST["secret"]);
    }
    
    function post() {
        $this->secret=
                     isset($_POST["secret"])
                     && strcmp($_POST["secret"],$_SESSION["user"]["secret"])==0;

        if (isset($_POST["oldpass"]) && preg_match("/[^ ]/",$_POST["oldpass"])) {
            $this->oldpass = trim($_POST["oldpass"]);
        }
        if (isset($_POST["newpass"]) && preg_match("/[^ ]/",$_POST["newpass"])) {
            $this->newpass = trim($_POST["newpass"]);
        }
        if ($this->secret && isset($this->oldpass) && isset($this->newpass)) {
            global $user;
            if ($user->authenticate($_SESSION["user"]["username"],$this->oldpass)) {
                $user->setPassword($_SESSION["user"]["id"],$this->newpass);
                echo "changed<br/>";
            }
        }
    }
    
    function body() {
        echo "<body>\n";
        $this->form();
        echo "</body>\n";
    }
    
    function form() {
        $secret = $_SESSION["user"]["secret"];
        $html = <<<END
<form method="POST" action="changepassword.php">
  <input type="password" name="oldpass" placeholder="Old Password" />
  <input type="password" name="newpass" placeholder="New Password" />
  <input type="hidden" name="secret" value="$secret" />
  <input type="submit" />
</form>
END;
        echo $html;
    }
}

(new ChangePassword())->generate();
