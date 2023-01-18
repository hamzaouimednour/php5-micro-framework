<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */


require_once PATH_CONTROLLERS . "Order.controller.php";

class Order extends OrderController {
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $order_uid, $address_id, $restaurant_comment, $order_status, $customer_error_case;
    public $json_order, $json_google_map, $customer_id, $delivery_id, $order_num;

    //--------------------------------------------------------------------------
    // Setters and Getters.
    //--------------------------------------------------------------------------
    public function getOrderUid(){
        return $this->order_uid ;
    }
    public function setOrderUid($pOrderUid){
        $this->order_uid = $pOrderUid ;
        return $this;
    }
    public function getCustomerErrorCase(){
        return $this->customer_error_case ;
    }
    public function setCustomerErrorCase($err){
        $this->customer_error_case = $err ;
        return $this;
    }
    public function getAddressId(){
        return $this->address_id ;
    }
    public function setAddressId($pAddress){
        $this->address_id = $pAddress ;
        return $this;
    }
    public function getRestaurantComment(){
        return $this->restaurant_comment ;
    }
    public function setRestaurantComment($pCustomerComment){
        $this->restaurant_comment = $pCustomerComment ;
        return $this;
    }
    public function getJsonOrder(){
        return $this->json_order ;
    }
    public function setJsonOrder($pJ){
        $this->json_order = $pJ ;
        return $this;
    }
    public function getJsonGoogleMap(){
        return $this->json_google_map ;
    }
    public function setJsonGoogleMap($pJ){
        $this->json_google_map = $pJ ;
        return $this;
    }
    public function getCustomerId(){
        return $this->customer_id ;
    }
    public function setCustomerId($pCustomerId){
        $this->customer_id = $pCustomerId ;
        return $this;
    }
    public function getDeliveryId(){
        return $this->delivery_id ;
    }
    public function setDeliveryId($pDeliverymanId){
        $this->delivery_id = $pDeliverymanId ;
        return $this;
    }
    public function getOrderNum(){
        return $this->order_num ;
    }
    public function setOrderNum($pOrderN){
        $this->order_num = $pOrderN ;
        return $this;
    }
    public function getOrderStatus(){
        return $this->order_status ;
    }
    public function setOrderStatus($pOrderStatus){
        $this->order_status = $pOrderStatus ;
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
        return $dbSchema['orders'];
    }
    public function getAll(){
        $dbManager = new ManagerDB;
        return $dbManager->orderBy(parent::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function resetAI(){
        $dbManager = new ManagerDB;
        $dbManager->resetAutoIncrement($this::getTable());
        return $this;
    }
    public function getAllByDateTime(){
        $dbManager = new ManagerDB;
        return $dbManager->orderBy("date_time", 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()]) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getElementByOrderUid(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array('restaurant_id' => ["=", $this->getRestaurantId()], "order_uid" => ["=", $this->getOrderUid()]) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getElementByOrderUidWP(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("order_uid" => ["=", $this->getOrderUid()]) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getAllByCustomerId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("customer_id" => ["=", $this->getCustomerId()]) )
                         ->orderBy(parent::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementByCustomerId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("customer_id" => ["=", $this->getCustomerId()], "order_uid" => ["=", $this->getOrderUid()]) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getElementByCustomerIdOrderId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("customer_id" => ["=", $this->getCustomerId()], "id" => ["=", $this->getId()]) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getByCustomerId_DateTime(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("customer_id" => ["=", $this->getCustomerId()]) )
                         ->orderBy("date_time", 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllByDeliveryId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("delivery_id" => ["=", $this->getDeliveryId()]) )
                         ->orderBy(parent::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllByDeliveryIdWS(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("delivery_id" => ["=", $this->getDeliveryId()] , "status" => ["=", $this->getStatus()] ) )
                         ->orderBy(parent::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getByDeliveryId_DateTime(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("delivery_id" => ["=", $this->getDeliveryId()]) )
                         ->orderBy("date_time", 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("restaurant_id" => ["=", $this->getRestaurantId()]) )
                         ->orderBy(parent::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllByRestaurantIdWS(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("restaurant_id" => ["=", $this->getRestaurantId()], "status" => ["=", $this->getStatus()]) )
                         ->orderBy(parent::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getByRestaurantId_DateTime(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("restaurant_id" => ["=", $this->getRestaurantId()]) )
                         ->orderBy("date_time", 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getLastOrder(){
        $dbManager = new ManagerDB;
        return $dbManager->getLastId($this::getTable(), parent::primaryKey);
    }
    public function getLastOrderByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("restaurant_id" => ["=", $this->getRestaurantId()]) )
                         ->getLastId($this::getTable(), parent::primaryKey);
    }
    public function getLastOrderByDeliveryId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("delivery_id" => ["=", $this->getRestaurantId()]) )
                         ->getLastId($this::getTable(), parent::primaryKey);
    }
    public function getLastOrderByRestaurantIdWS(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("restaurant_id" => ["=", $this->getRestaurantId()], "status" => ["=", $this->getStatus()]) )
                         ->getLastId($this::getTable(), parent::primaryKey);
    }
    public function getCustomerOrderByRestaurantIdWS(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(
            "restaurant_id" => ["=", $this->getRestaurantId()], 
        "customer_id" => ["=", $this->getCustomerId()],
        "status" => ["=", $this->getStatus()],
        ) )
                         ->fetch($this::getTable(), parent::primaryKey);
    }
    public function getNewOrdersByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(
                                    "restaurant_id" => ["=", $this->getRestaurantId()], 
                                    parent::primaryKey => [">", $this->getId()]
                                ) 
                            )
                         ->orderBy(parent::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getNewOrdersByRestaurantIdWS(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(
                                    "restaurant_id" => ["=", $this->getRestaurantId()], 
                                    parent::primaryKey => [">", $this->getId()],
                                    "status" => ["=", $this->getStatus()]
                                ) 
                            )
                         ->orderBy(parent::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getCustomerNbrOrders(){
        $dbManager = new ManagerDB;
        $nbrOrders = $dbManager->where( array("customer_id" => ["=", $this->getCustomerId()]) )
                                ->count($this::getTable(), "customer_id");
        return !empty($nbrOrders) ? $nbrOrders : 0;
    }
    public function getCustomerNbrOrdersWS(){
        $dbManager = new ManagerDB;
        $nbrOrders = $dbManager->where( array("customer_id" => ["=", $this->getCustomerId()], 'status' => ["=", $this->getStatus()]) )
                                ->count($this::getTable(), "customer_id");
        return !empty($nbrOrders) ? $nbrOrders : 0;
    }
    public function getCustomerNbrOrdersByRestaurantWS(){
        $dbManager = new ManagerDB;
        $nbrOrders = $dbManager->where( array("customer_id" => ["=", $this->getCustomerId()], 
        'restaurant_id' => ["=", $this->getRestaurantId()],
        'status' => ["=", $this->getStatus()]
        ) )
                                ->count($this::getTable(), "customer_id");
        return !empty($nbrOrders) ? $nbrOrders : 0;
    }
    public function getTodayOrdersNbr(){
        global $dateTime;
        $dbManager = new ManagerDB;
        $nbrOrders = $dbManager->where( array("datetime" => ["LIKE", $dateTime->format('Y-m-d') . "%"] ) )
                                ->count($this::getTable(), parent::primaryKey);
        return !empty($nbrOrders) ? $nbrOrders : 0;
    }
    public function getTodayOrdersNbrByRestaurantId(){
        global $dateTime;
        $dbManager = new ManagerDB;
        $nbrOrders = $dbManager->where( array("restaurant_id" => ["=", $this->getRestaurantId()], "datetime" => ["LIKE", $dateTime->format('Y-m-d') . "%"] ) )
                                ->count($this::getTable(), parent::primaryKey);
        return !empty($nbrOrders) ? $nbrOrders : 0;
    }
    public function generateOrderUid(){
        $nbrOrders = $this->getCustomerNbrOrders();
        return Handler::generateOrderId($this->getCustomerId(), $nbrOrders++);
    }
    public function rowCount(){
        $dbManager = new ManagerDB;
        return $dbManager->count($this::getTable(), self::primaryKey);
    }
    public function rowCountByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("restaurant_id" => ["=", $this->getRestaurantId()]))->count($this::getTable(), self::primaryKey);
    }
    public function addElement(){
        $dbManager = new ManagerDB;
        return $dbManager->insert($this, $this::getTable());
    }
    public function updateElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array(parent::primaryKey => ["=", $this->getId()]) );
    }
    public function updateElementByOrderUid(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array("restaurant_id" => ["=", $this->getRestaurantId()], "order_uid" => ["=", $this->getOrderUid()]) );
    }
    public function updateElementByCustomerId(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array("customer_id" => ["=", $this->getCustomerId()], "id" => ["=", $this->getId()]) );
    }
    public function removeElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(parent::primaryKey => ["=", $this->getId()]) )
                         ->delete($this::getTable());
    }
    public function removeByCustomerId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("customer_id" => ["=", $this->getCustomerId()]) )
                         ->delete($this::getTable());
    }
    public function removeByDeliveryId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("delivery_id" => ["=", $this->getDeliveryId()]) )
                         ->delete($this::getTable());
    }
    public function removeByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("restaurant_id" => ["=", $this->getRestaurantId()]) )
                         ->delete($this::getTable());
    }
}
?>