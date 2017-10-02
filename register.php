<?php

require 'src/config.php';

class Register extends view\Page {
    public $user;
    public $pass;
    public $errs = array();

    function generate() {
        global $user;
        $this->post();
        parent::generate();
    }
    
    function posted() {
        return isset($_POST["pass"]) && isset($_POST["user"]);
    }

    
    
    function post() {
      if (isset($_POST["user"]) 
              && preg_match("/^ *[A-Za-z0-9]+ *$/",$_POST["user"])) {  
          $this->user = trim($_POST["user"]);
      }
      if (isset($_POST["pass"]) && preg_match("/[^ ]/",$_POST["pass"])) {
          $this->pass = trim($_POST["pass"]);
      }
      if ($this->posted()) {
          if ($this->userAlreadyExists()) {
              array_push($this->errs,"user already exists");
          }
          if ($this->passwordTooSimple()) {
              array_push($this->errs,"use a more complex password");
          }
          if (count($this->errs) == 0) {
              global $user;
              $user->register($this->user,$this->pass);
          }
      }
    }
    
    function body() {
        echo "<body>\n";
        $this->registerForm();
        echo "</body>\n";
    }

    function userAlreadyExists() {
        if ($this->posted()) {
            global $user;
            return $user->exists($this->user);
        } else {
            return false;
        }
    }

    function passwordTooSimple() {
        if ($this->posted()) {
            global $user;
            return ! $user->passwordOk($this->pass);
        } else {
            return false;
        }
    }
    
    function registerForm() {
        foreach ($this->errs as $err) {
            echo "<h1>$err</h1>\n";
        }
        $html = <<<END
<form method="POST" action="register.php">
  <input type="text" name="user" placeholder="User"  value="$this->user" />
  <input type="password" name="pass" placeholder="Password" />
  <input type="submit" />
</form>
END;
        echo $html;
    }
}

(new Register())->generate();
