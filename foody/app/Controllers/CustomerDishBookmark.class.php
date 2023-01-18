<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */

class CustomerDishBookmark {
    
    const   primaryKey = "id"; // Unique ID
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $id, $user_id, $dish_id;

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
    public function getUserId(){
        return $this->user_id ;
    }
    public function setUserId($pUserId){
        $this->user_id = $pUserId ;
        return $this;
    }
    public function getDishId(){
        return $this->dish_id;
    }
    public function setDishId($pDishId){
        $this->dish_id = $pDishId;
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
        return $dbSchema['customer_dishes_bookmark'];
    }
    public function resetAI(){
        $dbManager = new ManagerDB;
        $dbManager->resetAutoIncrement($this::getTable());
        return $this;
    }
    // public function check($dish_id){
    //     $data = $this->getAllByUserId();
    //     $dishesIdArray = [];
    //     array_walk($data , function (&$dishesId) {
    //         $dishesIdArray[] = $dishesId->getDishId();
    //     });
    //     return in_array($dish_id, $this->getDishesIdJson());
    // }
    public function getAll(){
        $dbManager = new ManagerDB;
        return $dbManager->orderBy(self::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllByUserId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array('user_id' => ["=", $this->getUserId()]) )
                         ->orderBy(self::primaryKey, 1)
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
                         ->delete( $this::getTable() );
    }
    public function removeElementByUserId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()], 'user_id' => ["=", $this->getUserId()]) )
                         ->delete( $this::getTable() );
    }
    public function removeElementByDishId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array('dish_id' => ["=", $this->getDishId()], 'user_id' => ["=", $this->getUserId()]) )
                         ->delete( $this::getTable() );
    }

}
?>
