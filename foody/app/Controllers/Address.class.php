<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */

class Address {

    const   primaryKey = 'id';
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $id, $user_auth, $user_id, $address, $latitude, $longtitude, $city_id;

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
    public function getUserAuth(){
        return $this->user_auth ;
    }
    public function setUserAuth($pUserAuth){
        $this->user_auth = $pUserAuth ;
        return $this;
    }
    public function getUserId(){
        return $this->user_id ;
    }
    public function setUserId($pUserId){
        $this->user_id = $pUserId ;
        return $this;
    }
    public function getAddress(){
        return $this->address ;
    }
    public function setAddress($pAddress){
        $this->address = $pAddress ;
        return $this;
    }
    public function getLatitude(){
        return $this->latitude ;
    }
    public function setLatitude($pLatitude){
        $this->latitude = $pLatitude ;
        return $this;
    }
    public function getLongtitude(){
        return $this->longtitude ;
    }
    public function setLongtitude($pLongtitude){
        $this->longtitude = $pLongtitude ;
        return $this;
    }
    public function getCityId(){
        return $this->city_id ;
    }
    public function setCityId($CityId){
        $this->city_id = $CityId ;
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
        return $dbSchema['address'];
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
    public function getAllDelivery(){
        $dbManager = new ManagerDB;
        return $dbManager->innerJoin(
                            'fd_delivery', 
                            array('fd_address.user_id' => ['=', 'fd_delivery.uid']),
                            TRUE,
                            TRUE
                            )
                        ->where( array(
                            'fd_address.user_auth' => ['=', $this->getUserAuth()],
                            'fd_address.city_id' => ['=', $this->getCityId()],
                            'fd_delivery.availability' => ['=', '1'],
                            'fd_delivery.user_status' => ['=', '1'],
                            ))
                        ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllRestaurantsByCity(){
        $dbManager = new ManagerDB;
        return $dbManager->innerJoin(
                            'fd_restaurant', 
                            array('fd_address.user_id' => ['=', 'fd_restaurant.uid']),
                            TRUE,
                            TRUE
                            )
                        ->where( array(
                            'fd_address.user_auth' => ['=', $this->getUserAuth()],
                            'fd_address.city_id' => ['=', $this->getCityId()],
                            'fd_restaurant.user_status' => ['=', '1']
                            ))
                        ->fetch(__CLASS__, $this::getTable());
    }

    public function getElementByUserId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(
                            "user_id" => ["=", $this->getUserId()],
                            "user_auth" => ["=", $this->getUserAuth()]
                        ) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getElementsByUserId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(
                            "user_id" => ["=", $this->getUserId()],
                            "user_auth" => ["=", $this->getUserAuth()]
                        ) )
                         ->fetch(__CLASS__, $this::getTable());
    }

    public function getElementByAddressId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(
                            "user_id" => ["=", $this->getUserId()],
                            "id" => ["=", $this->getId()],
                            "user_auth" => ["=", $this->getUserAuth()]
                        ) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()]) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function countByUserId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("user_auth" => ["=", $this->getUserAuth()], "user_id" => ["=", $this->getUserId()]) )
                         ->count($this::getTable(), self::primaryKey);
    }
    public function rowCount(){
        $dbManager = new ManagerDB;
        return $dbManager->count($this::getTable(), self::primaryKey);
    }
    public function addElement($lastUid = NULL){
        $dbManager = new ManagerDB;
        return $dbManager->insert($this, $this::getTable(), $lastUid);
    }
    public function updateElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array(self::primaryKey => ["=", $this->getId()]) );
    }
    public function updateByUserAuthId(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), 
            array(
                    "user_id" => ["=", $this->getUserId()],
                    "user_auth" => ["=", $this->getUserAuth()]
                )
        );
    }
    public function updateByAddressId(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), 
            array(
                    self::primaryKey => ["=", $this->getId()],
                    "user_id" => ["=", $this->getUserId()],
                    "user_auth" => ["=", $this->getUserAuth()]
                )
        );
    }
    public function removeElementById(){
    $dbManager = new ManagerDB;
    return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]) )
                     ->delete($this::getTable());
    }
    public function removeByUserAuthId(){
    $dbManager = new ManagerDB;
    return $dbManager->where( array(
                            "user_id" => ["=", $this->getUserId()],
                            "user_auth" => ["=", $this->getUserAuth()]
                            ) )
                     ->delete($this::getTable());
    }
}
?>
