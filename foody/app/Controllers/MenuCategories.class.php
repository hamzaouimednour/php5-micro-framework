<?php 

class MenuCategories {
    
    const primaryKey = 'id';
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $id, $restaurant_id, $menu_name, $status;
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
    public function getRestaurantId(){
        return $this->restaurant_id ;
    }
    public function setRestaurantId($pRestaurantId){
        $this->restaurant_id = $pRestaurantId ;
        return $this;
    }
    public function getMenuName(){
        return $this->menu_name ;
    }
    public function setMenuName($pMenuName){
        $this->menu_name = $pMenuName ;
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
        return $dbSchema['dishes_menu'];
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
    public function getAllByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array("restaurant_id" => ["=", $this->getRestaurantId()],
                                       "status" => ["=", $this->getStatus()])
                                )
                         ->orderBy(self::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllByRestaurantIdWOS(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array("restaurant_id" => ["=", $this->getRestaurantId()]))
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
    public function updateElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array(self::primaryKey => ["=", $this->getId()]));
    }
    public function removeElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]))
                         ->delete($this::getTable());
    }
    public function removeElementsById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["IN", $this->getId()]), true) // remove the apostrophes
                         ->delete($this::getTable());
    }
    public function removeByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array("restaurant_id" => ["=", $this->getRestaurantId()]))
                         ->delete($this::getTable());
    }
}

?>