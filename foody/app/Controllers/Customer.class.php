<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */

require_once PATH_CONTROLLERS . "User.controller.php";

class Customer extends UserController {
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $credibility;

    //--------------------------------------------------------------------------
    // Setters and Getters.
    //--------------------------------------------------------------------------
    public function getCredibility(){
        return $this->credibility ;
    }
    public function setCredibility($pCredibility){
        $this->credibility = ($pCredibility == 1) ? 1 : 0;
        return $this;
    }
    //--------------------------------------------------------------------------
    // Controller Methods.
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
        return $dbSchema['customer'];
    }
    public function getAll(){
        $dbManager = new ManagerDB;
        // orderBy() : 1 => DESC
        return $dbManager->orderBy(parent::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllByOrderRestaurantId($restaurantUid){
        $dbManager = new ManagerDB;
        return $dbManager->innerJoin(Order::getTable(), array($this::getTable() . '.uid' => ["=", Order::getTable() . '.customer_id']), true, true)
                         ->where( array(Order::getTable() . '.restaurant_id' => ["=", $restaurantUid]) )
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getUserByUid(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getUid()]) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function rowCount(){
        $dbManager = new ManagerDB;
        return $dbManager->count($this::getTable(), self::primaryKey);
    }
    public function resetAI(){
        $dbManager = new ManagerDB;
        $dbManager->resetAutoIncrement($this::getTable());
        return $this;
    }
    public function checkUserByEmail(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(
                                         "email"     => ["=", $this->getEmail()],
                                         "passwd"    => ["=", $this->getPasswd()],
                                         "user_status"    => ["=", $this->getUserStatus()]
                                        ) 
                                )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function checkUserByEmailWOS(){
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
    public function checkUserByPhone(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(
                                         "phone"     => ["=", $this->getPhone()],
                                         "passwd"    => ["=", $this->getPasswd()],
                                         "user_status"    => ["=", $this->getUserStatus()]
                                        ) 
                                )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function checkUserByPhoneWOS(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(
                                         "phone"     => ["=", $this->getPhone()],
                                         "passwd"    => ["=", $this->getPasswd()]
                                        ) 
                                )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function checkPhone(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("phone"     => ["=", $this->getPhone()]))
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function checkEmail(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("email"     => ["=", $this->getEmail()]) 
                                )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function addUser($userId = null){
        $dbManager = new ManagerDB;
        return $dbManager->insert($this, $this::getTable(), $userId);
    }
    public function updateUserByUid(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array(self::primaryKey => ["=", $this->getUid()]) );
    }
    public function updateUserStatus(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(parent::primaryKey => ["=", $this->getUid()]))
                         ->updateRows($this, $this::getTable(), ['user_status']);
    }
    public function removeUserByUid(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getUid()]) )
                         ->delete( $this::getTable() );
    }

}
?>
