<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */
//apartment bloc  street neighborhood residence city postal_code
class CustomerVerification {

    const   primaryKey = 'id';
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $id, $customer_id, $verification_id, $verification_status;

    //--------------------------------------------------------------------------
    // Setters and Getters.
    //--------------------------------------------------------------------------
    public function getId(){
        return $this->id ;
    }
    public function setId($pId){
        $this->id = $pId ;
        return $this;
    }
    public function getCustomerId(){
        return $this->customer_id ;
    }
    public function setCustomerId($pUserId){
        $this->customer_id = $pUserId ;
        return $this;
    }
    public function getVerificationId(){
        return $this->verification_id ;
    }
    public function setVerificationId($pAId){
        $this->verification_id = $pAId ;
        return $this;
    }
    public function getVerificationStatus(){
        return $this->verification_status ;
    }
    public function SetVerificationStatus($pStatus){
        $this->verification_status = $pStatus ;
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
        return $dbSchema['customer_verification'];
    }
    public function resetAI(){
        $dbManager = new ManagerDB;
        $dbManager->resetAutoIncrement($this::getTable());
        return $this;
    }
    public function getAll(){
        $dbManager = new ManagerDB;
        return $dbManager->orderBy(self::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()]) )
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementByUserId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array('customer_id' => ["=", $this->getCustomerId()]) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getElementByIDUser(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(
                                    'verification_id' => ["=", $this->getVerificationId()],
                                    'customer_id' => ["=", $this->getCustomerId()],
                                    'verification_status'=> ["=", $this->getVerificationStatus()] ) 
                                )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function rowCount(){
        $dbManager = new ManagerDB;
        return $dbManager->count($this::getTable(), self::primaryKey);
    }
    public function addElement(){
        $dbManager = new ManagerDB;
        return $dbManager->insert($this, $this::getTable());
    }
    public function updateElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array(self::primaryKey => ["=", $this->getId()]) );
    }
    public function updateByUserId(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array("customer_id" => ["=", $this->getCustomerId()]));
    }
    public function removeElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()]) )
                         ->delete($this::getTable());
    }
    public function removeElementByUserId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("customer_id" => ["=", $this->getCustomerId()]) )
                         ->delete($this::getTable());
    }
}
?>
