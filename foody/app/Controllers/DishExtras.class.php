<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */

class DishExtras{
    const primaryKey = 'id';
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $id, $dish_id, $extra_id;
    
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
    public function getDishId(){
        return $this->dish_id ;
    }
    public function setDishId($pdish_id){
        $this->dish_id = $pdish_id ;
        return $this;
    }
    public function getExtraId(){
        return $this->extra_id;
    }
    public function setExtraId($pId){
        $this->extra_id = $pId;
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
        return $dbSchema['dishes_extras'];
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
    public function countByExtraId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("extra_id" => ["=", $this->getExtraId()]) )
                         ->count($this::getTable(), self::primaryKey);
    }
    public function countByDishId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("dish_id" => ["=", $this->getDishId()]) )
                         ->count($this::getTable(), self::primaryKey);
    }
    public function getAll(){
        $dbManager = new ManagerDB;
        return $dbManager->orderBy(self::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllByDishId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("dish_id" => ["=", $this->getDishId()]) )
                         ->orderBy(self::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]))
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function addElement(){
        $dbManager = new ManagerDB;
        return $dbManager->insert($this, $this::getTable());
    }
    public function addElements(){
        $dbManager = new ManagerDB;
        return $dbManager->multiInsert($this, $this::getTable());
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
    public function removeByDishId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array("dish_id" => ["=", $this->getDishId()]))
                         ->delete($this::getTable());
    }
}
?>