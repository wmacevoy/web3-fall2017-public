<?php

namespace model;

/**
 * Description of User
 *
 * @author wmacevoy
 */
class User {
    private $id = -1;
    private $groups = array();
    
    function setId(int $value) {
        $this->id=$value;
    }
    
    function getId() {
        return $this->id;
    }
    
    function addGroups($groups) {
        foreach ($groups as $group => $belongs) {
            if ($belongs) { 
                $this->groups[$group] = True;
            }
        }
    }
    
    function removeGroups($groups) {
        foreach ($groups as $group => $belongs) {
            if ($belongs) { 
                if (array_key_exists($group,$this->groups)) {
                    $this->groups[$group] = False;
                }
            }
        }
    }

    function clearGroups() {
        $this->groups=array();
    }
    
    function setGroups($groups) {
        $this->clearGroups();
        $this->addGroups($groups);
    }

    function addGroup($group) {
        $this->groups[$group] = True;
    }
    
    function removeGroup($group) {
        $this->groups[$group] = False;
    }

    function inGroup($group) {
        return array_key_exists($group,$this->groups) && $this->group[$group];
    }
    
    function authenticate($user,$pass) {
        global $db;

        $sql = "SELECT id, username, password, hash, nonce
                FROM user
                WHERE username = :username";
		
        $prepared = $db->prepare($sql, array($db->ATTR_CURSOR => $db->CURSOR_FWDONLY));
        $prepared->execute(array(':username' => $user));
        $result = $prepared->fetchAll();

        if (count($result) != 1) return false;

        if (strlen($result[0]['password']) != 0) {
            $this->setPassword($result[0]['id'],
                               $result[0]['password']);
            return strcmp($result[0]['password'],$pass) == 0;                  
        }
        $nonce=$result[0]['nonce'];
        $hash=$this->hash($user,$pass,$nonce);
        return strcmp($hash,$result[0]['hash']) == 0;
    }

    function setPassword($id, $password) {
        global $db;

        $sql = "SELECT username
                FROM user
                WHERE id = :id";
		
        $prepared = $db->prepare($sql, array($db->ATTR_CURSOR => $db->CURSOR_FWDONLY));
        $prepared->execute(array(':id' => $id));
        $result = $prepared->fetchAll();

        if (count($result) != 1) return;

        $user = $result[0]["username"];
        $nonce = $this->nonce();
        $hash = $this->hash($user,$password,$nonce);

        $sql = "UPDATE user 
                SET password=:password, hash=:hash, nonce=:nonce
                WHERE id = :id";

        $prepared = $db->prepare($sql, array($db->ATTR_CURSOR => $db->CURSOR_FWDONLY));
        $prepared->execute(array(':id' => $id,
                                 ':password'=>"", ':hash'=>$hash, ':nonce'=>$nonce));

    }
    
    function authenticated() {
        return isset($_SESSION["user"]);
    }
    
    function name() {
        if ($this->authenticated()) {
            return $_SESSION["user"]["username"];
        } else {
            return "anonymous";
        }
    }
    
    function login($user) {
        global $db;

        $sql = "SELECT id, username
                FROM user
                WHERE username = :username";
		
        $prepared = $db->prepare($sql, array($db->ATTR_CURSOR => $db->CURSOR_FWDONLY));
        $prepared->execute(array(':username' => $user));
        $result = $prepared->fetchAll();

        $_SESSION["user"]=array("user" => $user,
                                "username" => $result[0]['username'],
                                "id" => $result[0]['id']);
    }
    
    function logout() {
        unset($_SESSION["user"]);
    }

    function exists($user) {
        global $db;

        $sql = "SELECT id
                FROM user
                WHERE username = :username";
		
        $prepared = $db->prepare($sql, array($db->ATTR_CURSOR => $db->CURSOR_FWDONLY));
        $prepared->execute(array(':username' => $user));
        $result = $prepared->fetchAll();

        return count($result) == 1;
    }

    function passwordOk($password) {
        //        return (strlen($password) >= 4);
        $ok = true;
        if (strlen($password) < 4) {
            $ok = false;
        }
        if (!preg_match('/[A-Z]/',$password)) {
            $ok = false;
        }
        if (!preg_match('/[a-z]/',$password)) {
            $ok = false;
        }
        if (!preg_match('/[0-9]/',$password)) {
            $ok = false;
        }
        if (!preg_match('/[^A-Za-z0-9].*[^A-Za-z0-9]/',$password)) {
            $ok = false;
        }

        return $ok;
    }

    function nonce() {
        $bytes = 16;
        $fd = fopen("/dev/urandom","rb");
        $data = fread($fd,$bytes);
        fclose($fd);
        return bin2hex($data);
    }

    function hash($username, $password, $nonce) {
        $ans = "";
        $count = 500000;
        for ($i=0; $i<$count; ++$i) {
            $message = "$ans/$username/$password/$nonce";
            $ans=hash("sha256",$message);
        }
        return $ans;
    }

    function register($username, $password) {
        global $db;

        $nonce = $this->nonce();
        $hash = $this->hash($username,$password,$nonce);

        $sql = "INSERT INTO user (username, password, hash, nonce)
                VALUES (:username, :password, :hash, :nonce)";
		
        $prepared = $db->prepare($sql, array($db->ATTR_CURSOR => $db->CURSOR_FWDONLY));
        $status = $prepared->execute(array(
            ':username' => $username, 
            ':password' => "", 
            ':hash' => $hash, 
            ':nonce' => $nonce));
    }
    
    function __construct() {
        
    }
}
