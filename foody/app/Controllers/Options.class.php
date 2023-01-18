<?php 

class Options {
    
    const primaryKey = 'option_id';
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $option_id, $option_name, $option_value;
    //--------------------------------------------------------------------------
    // Setters and Getters.
    //--------------------------------------------------------------------------
    public function getOptionId(){
        return $this->option_id ;
    }
    public function setOptionId($pId){
        $this->option_id = $pId ;
        return $this;
    }
    public function getOptionName(){
        return $this->option_name ;
    }
    public function setOptionName($option_name){
        $this->option_name = $option_name ;
        return $this;
    }
    public function getOptionValue(){
        return $this->option_value ;
    }
    public function setOptionValue($option_value){
        $this->option_value = $option_value ;
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
        return $dbSchema['options'];
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
    public function rowCount(){
        $dbManager = new ManagerDB;
        return $dbManager->count($this::getTable(), self::primaryKey);
    }
    public function getElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getOptionId()]))
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getElementByOptionName(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array('option_name' => ["=", $this->getOptionName()]))
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function addElement(){
        $dbManager = new ManagerDB;
        return $dbManager->insert($this, $this::getTable());
    }
    public function updateElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array(self::primaryKey => ["=", $this->getOptionId()]));
    }
    public function updateElementByName(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array('option_name' => ["=", $this->getOptionName()]));
    }
    public function removeElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getOptionId()]))
                         ->delete($this::getTable());
    }
    public function removeElementsById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["IN", $this->getOptionId()]), true) // remove the apostrophes
                         ->delete($this::getTable());
    }
}

?>