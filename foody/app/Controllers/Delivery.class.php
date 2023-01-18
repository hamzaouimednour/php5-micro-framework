<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */


require_once PATH_CONTROLLERS . "User.controller.php";

class Delivery extends UserController {
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $vehicle_id, $birth_date, $working_time, $availability, $partner_request;

    //--------------------------------------------------------------------------
    // Setters and Getters.
    //--------------------------------------------------------------------------
    public function getVehicleId(){
        return $this->vehicle_id ;
    }
    public function setVehicleId($pVehicle){
        $this->vehicle_id = $pVehicle ;
        return $this;
    }
    public function getBirthDate(){
        return $this->birth_date ;
    }
    public function setBirthDate($pAge){
        $this->birth_date = $pAge ;
        return $this;
    }
    public function getWorkingTime(){
        return $this->working_time ;
    }
    public function setWorkingTime($pWt){
        $this->working_time = $pWt ;
        return $this;
    }
    public function getAvailability(){
        return $this->availability ;
    }
    public function setAvailability($pAvailability){
        $this->availability = $pAvailability ;
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
        return $dbSchema['delivery'];
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
    public function getAllByAvailability(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(parent::primaryKey => ["=", $this->getUid()], 'availability' => ["=", $this->getUid()]) )
                         ->fetch(__CLASS__, $this::getTable(), ['uid']);
    }
    public function getUserByUid(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(parent::primaryKey => ["=", $this->getUid()]) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getAllByVehicleId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array( "vehicle_id" => ["=", $this->getVehicleId()]) )
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function checkUser(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(
                                         "email"     => ["=", $this->getEmail()],
                                         "passwd"    => ["=", $this->getPasswd()],
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
    public function rowCount(){
        $dbManager = new ManagerDB;
        return $dbManager->count($this::getTable(), self::primaryKey);
    }
    public function countPartnership(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array('partner_request' => ["=", $this->getPartnerRequest()]) )
                         ->count($this::getTable(), self::primaryKey);
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
    public function addUser($lastUid = NULL){
        $dbManager = new ManagerDB;
        return $dbManager->insert($this, $this::getTable(), $lastUid);
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
    public function updatePartnerRequest(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(parent::primaryKey => ["=", $this->getUid()]))
                         ->updateRows($this, $this::getTable(), ['partner_request']);
    }
    public function removeUserByUid(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getUid()]) )
                         ->delete( $this::getTable() );
    }
    public function removeByVehicleId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("vehicle_id" => ["=", $this->getVehicleId()]))
                         ->delete( $this::getTable() );
    }

}
?>
