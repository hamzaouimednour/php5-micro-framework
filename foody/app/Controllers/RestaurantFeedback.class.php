<?php 
/**
 * 
 */
class RestaurantFeedback {
    
    const primaryKey = "id";

    public $id, $restaurant_id, $customer_id, $rating, $comment, $date_time, $status;
    
    public function getId(){
        return $this->id;
    }
    public function setId($pId){
        $this->id = $pId;
        return $this;
    }
    public function getRestaurantId(){
        return $this->restaurant_id;
    }
    public function setRestaurantId($pRestaurantId){
        $this->restaurant_id = $pRestaurantId;
        return $this;
    }
    public function getCustomerId(){
        return $this->customer_id;
    }
    public function setCustomerId($pCustomerId){
        $this->customer_id = $pCustomerId;
        return $this;
    }
    public function getRating(){
        return $this->rating;
    }
    public function setRating($pRating){
        $this->rating = $pRating;
        return $this;
    }
    public function getComment(){
        return $this->comment;
    }
    public function setComment($pComment){
        $this->comment = $pComment;
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

    public static function __getFields()
    {
        return array_keys(get_class_vars(__CLASS__));
    }
    public static function getTable()
    {
        global $dbSchema;
        return $dbSchema['restaurant_feedback'];
    }
    public function resetAI(){
        $dbManager = new ManagerDB;
        $dbManager->resetAutoIncrement($this::getTable());
        return $this;
    }
    public function getAll()
    {
        $dbManager = new ManagerDB;
        return $dbManager->orderBy(self::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementById()
    {
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]))
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getElementByRestaurantId()
    {
        $dbManager = new ManagerDB;
        return $dbManager->where(array("restaurant_id" => ["=", $this->getRestaurantId()], self::primaryKey => ["=", $this->getId()]))
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getAllByRestaurantId()
    {
        $dbManager = new ManagerDB;
        return $dbManager->where(array("restaurant_id" => ["=", $this->getRestaurantId()]))
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getRatingByRestaurantId()
    {
        $dbManager = new ManagerDB;
        return $dbManager->where(array("restaurant_id" => ["=", $this->getRestaurantId()]))
                         ->getRoundAvg($this::getTable(), "rating");
    }
    public function getAllByCustomerId()
    {
        $dbManager = new ManagerDB;
        return $dbManager->where(array("customer_id" => ["=", $this->getCustomerId()]))
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function rowCount()
    {
        $dbManager = new ManagerDB;
        return $dbManager->count($this::getTable(), self::primaryKey);
    }
    public function rowCountByRestaurantId()
    {
        $dbManager = new ManagerDB;
        return $dbManager->where(array("restaurant_id" => ["=", $this->getRestaurantId()]))
                        ->count($this::getTable(), self::primaryKey);
    }
    public function checkCustomerNote()
    {
        $dbManager = new ManagerDB;
        return $dbManager->where(array("customer_id" => ["=", $this->getCustomerId()]))
        ->count($this::getTable(), 'customer_id');
    }
    public function getNbrVotes()
    {   //it's ignoring NULL by default.
        $dbManager = new ManagerDB;
        return $dbManager->count($this::getTable(), "comment");
    }
    public function addElement()
    {
        $dbManager = new ManagerDB;
        return $dbManager->insert($this, $this::getTable());
    }
    public function updateElementById()
    {
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array(self::primaryKey => ["=", $this->getId()]));
    }
    public function updateElementByCustomerId()
    {
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array("customer_id" => ["=", $this->getCustomerId()]));
    }
    public function removeElementById()
    {
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]))
                         ->delete($this::getTable());
    }
    public function removeByCustomerId()
    {
        $dbManager = new ManagerDB;
        return $dbManager->where(array("customer_id" => ["=", $this->getCustomerId()]))
                         ->delete($this::getTable());
    }
    public function removeByRestaurantId()
    {
        $dbManager = new ManagerDB;
        return $dbManager->where(array("restaurant_id" => ["=", $this->getRestaurantId()]))
                         ->delete($this::getTable());
    }
}

?>