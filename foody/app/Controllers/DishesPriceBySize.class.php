<?php 

class DishesPriceBySize {
    
    const primaryKey = 'id';
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $id, $dish_id, $size_name, $price, $status;
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
    public function setDishId($pDishId){
        $this->dish_id = $pDishId ;
        return $this;
    }
    public function getSizeName(){
        return $this->size_name ;
    }
    public function setSizeName($pSizeName){
        $this->size_name = $pSizeName ;
        return $this;
    }
    public function getPrice(){
        return $this->price ;
    }
    public function setPrice($pPrice){
        $this->price = $pPrice ;
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
    public static function __getFields(){
        return array_keys(get_class_vars(__CLASS__));
    }
    public static function getTable(){
        global $dbSchema;
        return $dbSchema['dishes_price_size'];
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
    public function getAllByDishId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array('dish_id' => ["=", $this->getDishId()]))
                         ->orderBy('price')
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getMinPriceByDishId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array('dish_id' => ["=", $this->getDishId()]))
                         ->orderBy('price')
                         ->limit(1)
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getAllByStatus(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array('status' => ["=", $this->getStatus()]))
                         ->orderBy(self::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function rowCount(){
        $dbManager = new ManagerDB;
        return $dbManager->count($this::getTable(), self::primaryKey);
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