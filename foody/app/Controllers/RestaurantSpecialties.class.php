<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */

class RestaurantSpecialties {

    const primaryKey = 'id';

    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $id, $restaurant_id, $specialty_id;

    //--------------------------------------------------------------------------
    // Setters and Getters.
    //--------------------------------------------------------------------------
    public function getId(){
        return $this->id ; // 1: own delivery, 2: foody delivery
    }
    public function setId($pID){
        $this->id = $pID ;
        return $this;
    }
    public function getRestaurantId(){
        return $this->restaurant_id ;
    }
    public function setRestaurantId($pId){
        $this->restaurant_id = $pId ;
        return $this;
    }
    public function getSpecialtyId(){
        return $this->specialty_id ;
    }
    public function setSpecialtyId($pId){
        $this->specialty_id = $pId ;
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
        return $dbSchema['restaurant_specialties'];
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
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getAllByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("restaurant_id" => ["=", $this->getRestaurantId()]) )
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementsByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array("restaurant_id" => ["IN", $this->getRestaurantId()]), true)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllBySpecialtyId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("specialty_id" => ["=", $this->getSpecialtyId()]) )
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllRestaurantsByCitySpecialty($CityId){
        $dbManager = new ManagerDB;
        return $dbManager->innerJoin(
                            'fd_address', 
                            array('fd_address.user_id' => ['=', 'fd_restaurant_specialties.restaurant_id']),
                            TRUE,
                            TRUE
                            )
                        ->where( array(
                            'fd_address.user_auth' => ['=', '2'],
                            'fd_address.city_id' => ['=', $CityId],
                            'fd_restaurant_specialties.specialty_id' => ['=',  $this->getSpecialtyId()]
                            ))
                        ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllBySpecialitiesId(){
        $dbManager = new ManagerDB;
        return $dbManager->fetchDistinct(__CLASS__, $this::getTable(), "specialty_id");
    }
    public function getElementsById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["IN", $this->getId()]), true)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function rowCount(){
        $dbManager = new ManagerDB;
        return $dbManager->count($this::getTable(), self::primaryKey);
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
        return $dbManager->update($this, $this::getTable(), array(self::primaryKey => ["=", $this->getId()]) );
    }
    public function updateByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array("restaurant_id" => ["=", $this->getRestaurantId()]) );
    }
    public function removeElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()]) )
                         ->delete($this::getTable());
    }
    public function removeAllByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("restaurant_id" => ["=", $this->getRestaurantId()]) )
                         ->delete($this::getTable());
    }
    public function removeAllBySpecialtyId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("specialty_id" => ["=", $this->getSpecialtyId()]) )
                         ->delete($this::getTable());
    }
}
?>
