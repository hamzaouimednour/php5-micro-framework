<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */

class CityZone {
    /**
     * @var string Define a constant caontain the name of the primary key of dbTable.
     */
    const   primaryKey = "id";

    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $id, $city_id, $zone_name, $status;

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
    public function getCityId(){
        return $this->city_id ;
    }
    public function setCityId($pCityId){
        $this->city_id = $pCityId ;
        return $this;
    }
    public function getZoneName(){
        return $this->zone_name ;
    }
    public function setZoneName($zoneName){
        $this->zone_name = $zoneName ;
        return $this;
    }
    public function getStatus(){
        return $this->status ;
    }
    public function setStatus($pStatus){
        $this->status = $pStatus ;
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
        return $dbSchema['city_zone'];
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
    public function getAllByCityId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("city_id" => ["=", $this->getCityId()]) )
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
    public function removeElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()]) )
                         ->delete($this::getTable());
    }
    public function removeByCityId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("city_id" => ["=", $this->getCityId()]) )
                         ->delete($this::getTable());
    }
}
?>
