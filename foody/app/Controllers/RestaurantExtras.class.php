<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */


require_once PATH_CONTROLLERS . "Order.controller.php";

class RestaurantExtras extends OrderController {
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $extra_name;
    
    //--------------------------------------------------------------------------
    // Setters and Getters.
    //--------------------------------------------------------------------------
    public function getExtraName(){
        return $this->extra_name ;
    }
    public function setExtraName($pExtraName){
        $this->extra_name = $pExtraName ;
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
        return $dbSchema['restaurant_extras'];
    }
    public function resetAI(){
        $dbManager = new ManagerDB;
        $dbManager->resetAutoIncrement($this::getTable());
        return $this;
    }
    public function rowCount(){
        $dbManager = new ManagerDB;
        return $dbManager->count($this::getTable(), self::primaryKey);
    }
    public function getAll(){
        $dbManager = new ManagerDB;
        return $dbManager->orderBy(self::primaryKey, 1)
            ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array("restaurant_id" => ["=", $this->getRestaurantId()]))
                         ->orderBy(self::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]))
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getElementsById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["IN", $this->getId()]), true)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function addElement(){
        $dbManager = new ManagerDB;
        return $dbManager->insert($this, $this::getTable());
    }
    public function updateElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array(self::primaryKey => ["=", $this->getId()]));
    }
    public function removeElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]))
                         ->delete($this::getTable());
    }
    public function removeByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array("restaurant_id" => ["=", $this-> getRestaurantId()]))
                         ->delete($this::getTable());
    }
    public function removeElementsById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["IN", $this->getId()]), true) // remove the apostrophes
                         ->delete($this::getTable());
    }
}
