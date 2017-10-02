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

        $sql = "SELECT id, username, password
                FROM user
                WHERE username = :username";
		
        $prepared = $db->prepare($sql, array($db->ATTR_CURSOR => $db->CURSOR_FWDONLY));
        $prepared->execute(array(':username' => $user));
        $result = $prepared->fetchAll();

        return (count($result) == 1 && strcmp($result[0]['password'],$pass) == 0);
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

        $sql = "SELECT id, username, password
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

    function register($username, $password) {
        global $db;

        $sql = "INSERT INTO user (username, password)
                VALUES (:username, :password)";
		
        $prepared = $db->prepare($sql, array($db->ATTR_CURSOR => $db->CURSOR_FWDONLY));
        $status = $prepared->execute(array(':username' => $username, ':password' => $password));

        var_dump($status);
    }
    
    function __construct() {
        
    }
}
