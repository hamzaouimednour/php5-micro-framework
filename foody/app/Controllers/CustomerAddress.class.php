<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */
//apartment bloc  street neighborhood residence city postal_code
class CustomerAddress {

    const   primaryKey = 'id';
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $id, $customer_id, $address_id, $address_name, $street, $building, $apartment_bloc, $floor, $instructions, $status;

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
    public function getAddressId(){
        return $this->address_id ;
    }
    public function setAddressId($pAId){
        $this->address_id = $pAId ;
        return $this;
    }
    public function getAddressName(){
        return $this->address_name ;
    }
    public function setAddressName($pAddr){
        $this->address_name = $pAddr ;
        return $this;
    }
    public function getStreet(){
        return $this->street ;
    }
    public function setStreet($pStreet){
        $this->street = $pStreet ;
        return $this;
    }
    public function getBuilding(){
        return $this->building ;
    }
    /**
     * O: Office or Home; A: Apartment
     *
     * @param [string] $building
     * @return void
     */
    public function setBuilding($building){
        $this->building = $building ;
        return $this;
    }
    public function getApartmentBloc(){
        return $this->apartment_bloc ;
    }
    public function setApartmentBloc($pApartmentName){
        $this->apartment_bloc = $pApartmentName ;
        return $this;
    }
    public function getFloor(){
        return $this->floor ;
    }
    public function setFloor($pFloor){
        $this->floor = $pFloor ;
        return $this;
    }
    public function getInstructions(){
        return $this->instructions ;
    }
    public function setInstructions($instruction){
        $this->instructions = $instruction ;
        return $this;
    }
    public function getStatus(){
        return $this->status ;
    }
    public function SetStatus($pStatus){
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
        return $dbSchema['customer_address'];
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
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementsByUserIdWS(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(
                            "customer_id" => ["=", $this->getCustomerId()],
                            "status" => ["=", $this->getStatus()]
                        ) )
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementByUserAddressId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array( 'customer_id' => ["=", $this->getCustomerId()], 'address_id' => ["=", $this->getAddressId()])  )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getElementByUserAddressIdWS(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array( 'customer_id' => ["=", $this->getCustomerId()], 
        'address_id' => ["=", $this->getAddressId()],
        'status' => ["=", $this->getStatus()])  )
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
        return $dbManager->update($this, $this::getTable(), array("user_id" => ["=", $this->getUserId()]));
    }
    public function updateByAddressId(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array("address_id" => ["=", $this->getAddressId()], "customer_id" => ["=", $this->getCustomerId()]));
    }
    public function removeElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()]) )
                         ->delete($this::getTable());
    }
}
?>
