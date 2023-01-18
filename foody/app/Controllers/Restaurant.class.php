<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */

require_once PATH_CONTROLLERS . "User.controller.php";

class Restaurant extends UserController {
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $restaurant_name, $delivery_type, $logo, $partner_request, $cover_photo;

    //--------------------------------------------------------------------------
    // Setters and Getters.
    //--------------------------------------------------------------------------
    public function getRestaurantName(){
        return $this->restaurant_name ; // 1: own delivery, 2: foody delivery
    }
    public function setRestaurantName($pRestaurantName){
        $this->restaurant_name = $pRestaurantName ;
        return $this;
    }
    public function getDeliveryType(){
        return $this->delivery_type ; // 1: own delivery, 2: foody delivery
    }
    public function setDeliveryType($pDeliveryType){
        $this->delivery_type = $pDeliveryType ;
        return $this;
    }
    public function getLogo(){
        return $this->logo ;
    }
    public function setLogo($pLogo){
        $this->logo = $pLogo ;
        return $this;
    }
    public function getCoverPhoto(){
        return $this->cover_photo ;
    }
    public function setCoverPhoto($pLogo){
        $this->cover_photo = $pLogo ;
        return $this;
    }
    public function getPartnerRequest(){
        return $this->partner_request ;
    }
    public function setPartnerRequest($pVal){
        //P: pending | A : accepted | R: refused	
        $this->partner_request = $pVal;
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
        return $dbSchema['restaurant'];
    }
    public function resetAI(){
        $dbManager = new ManagerDB;
        $dbManager->resetAutoIncrement($this::getTable());
        return $this;
    }
    public function getAll(){
        $dbManager = new ManagerDB;
        return $dbManager->orderBy(parent::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getUsersByUid(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["IN", $this->getUid()]), true)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getUsersByUidWS(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["IN", $this->getUid()], 
                                'user_status' => ["=", $this->getUserStatus()],
                                'partner_request' => ["=", $this->getPartnerRequest()]), true)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getUserByUid(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(parent::primaryKey => ["=", $this->getUid()]) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function countPartnership(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array('partner_request' => ["=", $this->getPartnerRequest()]) )
                         ->count($this::getTable(), self::primaryKey);
    }
    public function rowCount(){
        $dbManager = new ManagerDB;
        return $dbManager->count($this::getTable(), self::primaryKey);
    }
    public function checkUser(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(
                                         "email"            => ["=", $this->getEmail()],
                                         "passwd"           => ["=", $this->getPasswd()],
                                         "user_status"    => ["=", $this->getUserStatus()]
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
    public function addUser($lastUid = NULL){
        $dbManager = new ManagerDB;
        return $dbManager->insert($this, $this::getTable(), $lastUid);
    }
    public function checkEmail(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("email"     => ["=", $this->getEmail()]) 
                                )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function updateUserByUid(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array(parent::primaryKey => ["=", $this->getUid()]) );
    }
    public function updateUserStatus(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(parent::primaryKey => ["=", $this->getUid()]))
                         ->updateRows($this, $this::getTable(), ['user_status']);
    }
    public function updatePartnerRequest(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(parent::primaryKey => ["=", $this->getUid()]))
                         ->updateRows($this, $this::getTable(), ['partner_request']);
    }
    public function removeUserByUid(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(parent::primaryKey => ["=", $this->getUid()]) )
                         ->delete($this::getTable());
    }
}
?>
