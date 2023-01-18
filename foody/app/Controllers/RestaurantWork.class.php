<?php 

/*
// Working days : {1,2,3,4,5,6,7} , workTime : {1 : {'08:00','23:00'},2 : {'10:00','00:00'} ... }
// {2,3,4,5,6}, workingTime : {2 : {'08:00','23:00'},3 : {'10:00','00:00'} ... }
$times =  json_encode([1 => ['08:00','23:00'], 2 => ['08:00','23:00']]);
$p = json_decode($times, true); //true for return array
*/
class RestaurantWork {

    const  primaryKey = "id";
    //--------------------------------------------------------------------------
    // Main Properties.
    //--------------------------------------------------------------------------
    public $id, $delivery_type, $restaurant_id, $delivery_fee, $init_distance, $description, $work_times;

    //--------------------------------------------------------------------------
    // Fee Properties..
    //--------------------------------------------------------------------------
    public $up_distance, $up_fee, $min_delivery, $max_delivery;

    // Time Properties.
    public $preparation_time_min, $preparation_time_max, $delivery_time_min, $delivery_time_max;

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
    public function getDeliveryType(){
        return $this->delivery_type ;
    }
    public function setDeliveryType($pId){
        $this->delivery_type = $pId ;
        return $this;
    }
    public function getRestaurantId(){
        return $this->restaurant_id ;
    }
    public function setRestaurantId($pRestaurantId){
        $this->restaurant_id = $pRestaurantId ;
        return $this;
    }
    public function getDeliveryFee(){
        return $this->delivery_fee ;
    }
    public function setDeliveryFee($pDeliveryPrice){
        $this->delivery_fee = $pDeliveryPrice ;
        return $this;
    }
    public function getInitDistance()
    {
        return $this->init_distance;
    }
    public function setInitDistance($pInitDistance)
    {
        $this->init_distance = $pInitDistance;
        return $this;
    }
    public function getUpFee()
    {
        return $this->up_fee;
    }
    public function setUpFee($pUpFee)
    {
        $this->up_fee = $pUpFee;
        return $this;
    }
    public function getUpDistance()
    {
        return $this->up_distance;
    }
    public function setUpDistance($pUpDistance)
    {
        $this->up_distance = $pUpDistance;
        return $this;
    }
    public function getMinDelivery(){
        return $this->min_delivery ;
    }
    public function setMinDelivery($pMinDelivery){
        $this->min_delivery = $pMinDelivery ;
        return $this;
    }
    public function getMaxDelivery(){
        return $this->max_delivery ;
    }
    public function setMaxDelivery($pMaxDelivery){
        $this->max_delivery = $pMaxDelivery ;
        return $this;
    }
    public function getPreparationTimeMin(){
        return $this->preparation_time_min ;
    }
    public function setPreparationTimeMin($pPreparationTimeMin){
        $this->preparation_time_min = $pPreparationTimeMin ;
        return $this;
    }
    public function getPreparationTimeMax(){
        return $this->preparation_time_max ;
    }
    public function setPreparationTimeMax($pPreparationTimeMax){
        $this->preparation_time_max = $pPreparationTimeMax ;
        return $this;
    }
    public function getDeliveryTimeMin(){
        return $this->delivery_time_min ;
    }
    public function setDeliveryTimeMin($pDeliveryTimeMin){
        $this->delivery_time_min = $pDeliveryTimeMin ;
        return $this;
    }
    public function getDeliveryTimeMax(){
        return $this->delivery_time_max ;
    }
    public function setDeliveryTimeMax($pDeliveryTimeMax){
        $this->delivery_time_max = $pDeliveryTimeMax ;
        return $this;
    }
    /**
     * i.e : [1 => ['08:00','23:00'], ...]
     * @return array
     */
    public function getWorkTimes($decode =  NULL){
        if(is_null($decode)){
            return $this->work_times;
        }
        return json_decode($this->work_times, TRUE) ;
    }
    /**
     * @param array $pWorkTimes : [$dayWeekNbr => [openTime, closeTime]]
     * @return void
     */
    public function setWorkTimes($pWorkTimes){
        $this->work_times = json_encode($pWorkTimes) ;
        return $this;
    }

    public function getDescription(){
        return $this->description ;
    }
    public function setDescription($desc){
        $this->description = $desc ;
        return $this;
    }
    
    public function getWorkDays(){
        // [1,2,3,4,5,6,7]
        return (!is_null($this->work_times)) ? array_keys($this->work_times) : NULL ;
    }
    public function setWorkDayTime(){
        $this->work_times = $this->getWorkTimes(true);
    }
    public function getWorkOpenTime($day){
        if(is_array($this->work_times)){
            if(in_array($day, array_keys($this->work_times))){
                return $this->work_times[$day][0];
            }
        }
        
        return null;
    }
    public function getWorkCloseTime($day){
        if(is_array($this->work_times)){
            if(in_array($day, array_keys($this->work_times))){
                return $this->work_times[$day][1];
            }
        }
        return null;
    }

    //--------------------------------------------------------------------------
    // Controller Methods.
    //--------------------------------------------------------------------------
    public static function __getFields(){
        return array_keys(get_class_vars(__CLASS__));
    }
    public static function getTable(){
        global $dbSchema;
        return $dbSchema['restaurant_work'];
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
    public function getElementByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array("restaurant_id" => ["=", $this->getRestaurantId()]))
                         ->orderBy(self::primaryKey, 1)
                         ->fetchObject(__CLASS__, $this::getTable());
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
    public function updateElementByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array("restaurant_id" => ["=", $this->getRestaurantId()]) );
    }
    public function removeElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()]) )
                         ->delete($this::getTable());
    }
    
}
?>