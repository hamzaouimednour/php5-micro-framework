<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */

class OrderDishes{
    /**
     * OrderDishes() : an Order can contain many dishes so , by this class i can get the Qte and dishes id.
     * json_encode(array($dish_uid => [$unit_price, $qty]));
     */
    const  primaryKey = "id";

    public $id, $dishes_id_json ;

    public function getId(){
        return $this->id;
    }
    public function setId($pId){
        $this->id = $pId;
        return $this;
    }
    public function getDishesIdJson(){
        return json_decode($this->dishes_id_json, true);
    }
    /**
     * @todo json_encode( array($dish_uid => [$qty, $unit_price]) );
     * @param array $dishesInfo : array($dish_uid => [$qty, $unit_price])
     * @return object Json
     */
    public function setDishesIdJson(array $pJsonData){
        $this->dishes_id_json = json_encode($pJsonData);
        return $this;
    }

    public function rowCount(){
        $dbManager = new ManagerDB;
        return $dbManager->count($this::getTable(), self::primaryKey);
    }
    public static function __getFields(){
        return array_keys(get_class_vars(__CLASS__));
    }
    public static function getTable(){
        global $dbSchema;
        return $dbSchema['order_dishes'];
    }
    public function getAll(){
        $dbManager = new ManagerDB;
        return $dbManager->orderBy(parent::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(parent::primaryKey => ["=", $this->getId()]))
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function addElement(){
        $dbManager = new ManagerDB;
        return $dbManager->insert($this, $this::getTable());
    }
    public function updateElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array(parent::primaryKey => ["=", $this->getId()]));
    }
    public function removeElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(parent::primaryKey => ["=", $this->getId()]))
            ->delete($this::getTable());
    }

}

?>