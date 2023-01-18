<?php
/**
 *  user can submit a comment and rating on an received order.
 */
class DeliveryFeedback
{

    const primaryKey = "id";

    public $id, $order_id, $delivery_comment, $restaurant_comment, $rating, $date_time, $status;

    public function getId(){
        return $this->id;
    }
    public function setId($pId){
        $this->id = $pId;
        return $this;
    }
    public function getOrderId(){
        return $this->order_id;
    }
    public function setOrderId($pOrderId){
        $this->order_id = $pOrderId;
        return $this;
    }
    public function getDeliveryComment(){
        return $this->delivery_comment;
    }
    public function setDeliveryComment($pDeliveryComment){
        $this->delivery_comment = $pDeliveryComment;
        return $this;
    }
    public function getRestaurantComment(){
        return $this->restaurant_comment;
    }
    public function setRestaurantComment($pRestaurantComment){
        $this->restaurant_comment = $pRestaurantComment;
        return $this;
    }
    public function getRating(){
        return $this->rating;
    }
    public function setRating($pRating){
        $this->rating = $pRating;
        return $this;
    }
    public function getDateTime(){
        return $this->date_time;
    }
    public function setDateTime($pDateTime){
        $this->date_time = $pDateTime;
        return $this;
    }
    public function getStatus(){
        return $this->status;
    }
    public function setStatus($pStatus){
        $this->status = $pStatus;
        return $this;
    }

    public static function __getFields(){
        return array_keys(get_class_vars(__CLASS__));
    }
    public static function getTable(){
        global $dbSchema;
        return $dbSchema['customer_feedback'];
    }
    public function resetAI(){
        $dbManager = new ManagerDB;
        $dbManager->resetAutoIncrement($this::getTable());
        return $this;
    }
    public function getAll(){
        $dbManager = new ManagerDB;
        return $dbManager->orderBy(parent::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]))
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getAllByOrderId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array("order_id" => ["=", $this->getOrderId()]))
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
    public function updateElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array(self::primaryKey => ["=", $this->getId()]));
    }
    public function updateByOrderId(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array("order_id" => ["=", $this->getOrderId()]));
    }
    public function removeElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]))
                         ->delete($this::getTable());
    }
    public function removeByOrderId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array("order_id" => ["=", $this->getOrderId()]))
                         ->delete($this::getTable());
    }
}
