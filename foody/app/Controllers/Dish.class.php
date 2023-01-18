<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */

require_once PATH_CONTROLLERS . "Order.controller.php";

class Dish extends OrderController {
    
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $menu_id, $dish_name, $price_by_size, $description, $dish_image;

    //--------------------------------------------------------------------------
    // Setters and Getters.
    //--------------------------------------------------------------------------
    public function getMenuId(){
        return $this->menu_id ;
    }
    public function setMenuId($pMenuId){
        $this->menu_id = $pMenuId ;
        return $this;
    }
    public function getDishName(){
        return $this->dish_name ;
    }
    public function setDishName($pDishName){
        $this->dish_name = $pDishName ;
        return $this;
    }
    public function getPriceBySize(){
        return $this->price_by_size ;
    }
    public function setPriceBySize($pEnum){
        $this->price_by_size = $pEnum ; //T,F
        return $this;
    }
    public function getDescription(){
        return $this->description ;
    }
    public function setDescription($pDescription){
        $this->description = $pDescription ;
        return $this;
    }
    public function getDishImage(){
        return $this->dish_image ;
    }
    public function setDishImage($pDishImage){
        $this->dish_image = $pDishImage ;
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
        return $dbSchema['dishes'];
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
    public function getAllByLowPrice(){
        $dbManager = new ManagerDB;
        return $dbManager->cast("price", "Float")
                         ->orderBy()
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllByHightPrice(){
        $dbManager = new ManagerDB;
        return $dbManager->cast("price", "Float")
                        ->orderBy(NULL, 1)
                        ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()]) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getElementByIdWS(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()], "status" => ["=", $this->getStatus()]) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getElementByRestaurantIdWS(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()],
                                        "restaurant_id" => ["=", $this->getRestaurantId()] ,
                                        "status" => ["=", $this->getStatus()]) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getAllByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("restaurant_id" => ["=", $this->getRestaurantId()]) )
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllByRestaurantIdWS(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("restaurant_id" => ["=", $this->getRestaurantId()] , "status" => ["=", $this->getStatus()]) )
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllByMenuId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("menu_id" => ["=", $this->getMenuId()]) )
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function countByMenuId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("menu_id" => ["=", $this->getMenuId()]) )
                         ->count($this::getTable(), self::primaryKey);
    }
    public function countByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("restaurant_id" => ["=", $this->getRestaurantId()]) )
                         ->count($this::getTable(), self::primaryKey);
    }
    public function rowCount(){
        $dbManager = new ManagerDB;
        return $dbManager->count($this::getTable(), self::primaryKey);
    }
    public function searchByDishName(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["LIKE", "%".$this->getDishName()."%"]) )
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function addElement($lastId = null){
        $dbManager = new ManagerDB;
        return (is_null($lastId)) ? $dbManager->insert($this, $this::getTable()) : $dbManager->insert($this, $this::getTable(), true);
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
    public function removeByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("restaurant_id"=> ["=", $this->getRestaurantId()])  )
                         ->delete($this::getTable());
    }
}
?>