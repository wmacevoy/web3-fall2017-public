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

        $sql = 'SELECT id, username, password
                FROM user
                WHERE username = :username';
		
        $prepared = $db->prepare($sql, array($db->ATTR_CURSOR => $db->CURSOR_FWDONLY));
        $prepared->execute(array(':username' => $user));
        $result = $prepared->fetchAll();

        var_dump($result);

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
