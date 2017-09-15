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
        if (strcmp($user,"alice") == 0) {
            if (strcmp($pass,"pass") === 0) {
                return true;
            }
        }
        if (strcmp($user,"bob") == 0) {
            if (strcmp($pass,"pop") === 0) {
                return true;
            }
        }
        return false;
    }
    
    function authenticated() {
        return isset($_SESSION["user"]);
    }
    
    function login($user) {
        $_SESSION["user"]=$user;
    }
    
    function logout() {
        unset($_SESSION["user"]);
    }
    
    function __construct() {
        
    }
}
