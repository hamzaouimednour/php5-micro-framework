<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */

require_once PATH_CONTROLLERS . "User.controller.php";

class Administrator extends UserController {
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $authority;

    //--------------------------------------------------------------------------
    // Setters and Getters.
    //--------------------------------------------------------------------------
    public function getAuthority(){
        return $this->authority ;
    }
    public function setAuthority($pAuthority){
        $this->authority = $pAuthority ;
        return $this;
    }

    //--------------------------------------------------------------------------
    // Controllers.
    //--------------------------------------------------------------------------
    /**
     * __getFields : return attributes of current class.
     * @static __getFields()
     * @todo This static method should be declared in every class (REQUIRED).
     * @return array
     */
    public static function __getFields(){
        return array_keys(get_class_vars(__CLASS__));
    }
    public static function getTable(){
        global $dbSchema;
        return $dbSchema['administrator'];
    }
    public function resetAI(){
        $dbManager = new ManagerDB;
        $dbManager->resetAutoIncrement($this::getTable());
        return $this;
    }
    public function getAll(){
        $dbManager = new ManagerDB;
        return $dbManager->orderBy(parent::primaryKey)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getUserByUid(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(parent::primaryKey => ["=", $this->getUid()]) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function checkUser(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(
                                         "email"     => ["=", $this->getEmail()],
                                         "passwd"    => ["=", $this->getPasswd()]
                                        ) 
                                )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function checkUserPasswd(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(
                                        parent::primaryKey     => ["=", $this->getUid()],
                                        "passwd"    => ["=", $this->getPasswd()]
                                        ) 
                                )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function checkUsername(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("username"     => ["=", $this->getUsername()]))
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function checkEmail(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("email"     => ["=", $this->getEmail()]) 
                                )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function rowCount(){
        $dbManager = new ManagerDB;
        return $dbManager->count($this::getTable(), parent::primaryKey);
    }
    public function addUser(){
        $dbManager = new ManagerDB;
        return $dbManager->insert($this, $this::getTable());
    }
    public function updateUserByUid(){
        $dbManager = new ManagerDB;
        // we need to remove conditions from updated fields.
        return $dbManager->update($this, $this::getTable(), array(parent::primaryKey => ["=", $this->getUid()]) );
    }
    public function updateUserStatus(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(parent::primaryKey => ["=", $this->getUid()]))
                         ->updateRows($this, $this::getTable(), ['user_status']);
    }
    public function removeUserByUid(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getUid()]) )
                         ->delete($this::getTable());
    }
}
?>
