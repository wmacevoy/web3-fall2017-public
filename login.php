<?php

require 'src/config.php';

class Login extends view\Page {
    public $user;
    public $pass;
    
    function generate() {
        global $user;
        $this->post();
        if ($user->authenticated()) {
            $this->redirect("index.php");
        } else {
            parent::generate();
        }
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
      if (isset($this->user) && isset($this->pass)) {
          global $user;
          if ($user->authenticate($this->user,$this->pass)) {
              $user->login($this->user);
          }
      }
      
    }
    
    function body() {
        echo "<body>\n";
        $this->loginForm();
        echo "</body>\n";
    }
    
    function loginForm() {
        if ($this->posted()) {
            echo "<h1>Please try again</h1>\n";
        }
        $html = <<<END
<form method="POST" action="login.php">
  <input type="text" name="user" placeholder="User"  value="$this->user" />
  <input type="password" name="pass" placeholder="Password" />
  <input type="submit" />
</form>
END;
        echo $html;
    }
}

(new Login())->generate();
// " /><script> alert('gotya!'); </script> <input type="text" value="