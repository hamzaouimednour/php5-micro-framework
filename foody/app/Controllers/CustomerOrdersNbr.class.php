<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */

class CustomerOrdersNbr {
    /**
     * 
     */
    const  primaryKey = "id";
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $id, $customer_id, $nbr_orders;

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
    public function setCustomerId($pCustomerId){
        $this->customer_id = $pCustomerId ;
        return $this;
    }
    public function getNbrOrders(){
        return $this->nbr_orders ;
    }
    public function setNbrOrders($pNbrOrders){
        $this->nbr_orders = $pNbrOrders ;
        return $this;
    }

    //--------------------------------------------------------------------------
    // Controller Methods.
    //--------------------------------------------------------------------------
    public static function __getFields(){
        return array_keys(get_class_vars(__CLASS__));
    }
    public static function getTable(){
        global $dbSchema;
        return $dbSchema['customer_orders_nbr'];
    }

    public function getAll(){
        $dbManager = new ManagerDB;
        return $dbManager->orderBy(self::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()]) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getByCustomerId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(  array("customer_id" => ["=", $this->getCustomerId()]) )
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
    public function incrementNbrOrders(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()]) )
                         ->updateRows($this, $this::getTable(), ['nbr_orders']);
    }
    public function updateElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array(self::primaryKey => ["=", $this->getId()]) );
    }
    public function removeElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()]) )
                         ->delete($this::getTable());
    }
    public function removeByCustomerId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("customer_id" => ["=", $this->getCustomerId()]) )
                         ->delete($this::getTable());
    }
}
?>