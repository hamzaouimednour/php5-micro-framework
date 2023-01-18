<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */

class City {
    /**
     * @var string Define a constant caontain the name of the primary key of dbTable.
     */
    const   primaryKey = "id";

    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $id, $city_name, $lat, $lng, $status;

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
    public function getCityName(){
        return $this->city_name ;
    }
    public function setCityName($pCityName){
        $this->city_name = $pCityName ;
        return $this;
    }
    public function getLat(){
        return $this->lat ;
    }
    public function setLat($pLat){
        $this->lat = $pLat ;
        return $this;
    }
    public function getLng(){
        return $this->lng ;
    }
    public function setLng($pLng){
        $this->lng = $pLng ;
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
        return $dbSchema['city'];
    }
    public function resetAI(){
        $dbManager = new ManagerDB;
        $dbManager->resetAutoIncrement($this::getTable());
        return $this;
    }
    public function getAll(){
        $dbManager = new ManagerDB;
        return $dbManager->orderBy(self::primaryKey)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllByStatus(){
        $dbManager = new ManagerDB;
        return $dbManager->orderBy(self::primaryKey)
                         ->where( array('status' => ["=", $this->getStatus()]) )
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()]) )
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

}
?>
