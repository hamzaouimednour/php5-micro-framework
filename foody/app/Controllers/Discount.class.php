<?php
// Qte acheté ou percentage20% ou par valeur -20DT date de fin status

class DiscountController{

    const  primaryKey = "id";

    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $id, $restaurant_id, $discount_item_id, $description, $start_date_time, $end_date_time, $status;

    //--------------------------------------------------------------------------
    // Setters and Getters.
    //--------------------------------------------------------------------------
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
    public function getDiscountItemId(){
        // 0 : all items else by item_id
        return $this->discount_item_id;
    }
    public function setDiscountItemId($pDiscountId){
        $this->discount_item_id = $pDiscountId;
        return $this;
    }
    public function getStatus(){
        return $this->status;
    }
    public function setStatus($pStatus){
        $this->status = $pStatus;
        return $this;
    }
    public function getStartDateTime(){
        return $this->start_date_time;
    }
    public function setStartDateTime($pStartDateTime){
        $this->start_date_time = $pStartDateTime;
        return $this;
    }
    public function getEndDateTime(){
        return $this->end_date_time;
    }
    public function setEndDateTime($pEndDateTime){
        $this->end_date_time = $pEndDateTime;
        return $this;
    }
    public function getDescription(){
        return $this->description;
    }
    public function setDescription($pDscription){
        $this->description = $pDscription;
        return $this;
    }

    //--------------------------------------------------------------------------
    // Controller Methods.
    //--------------------------------------------------------------------------
}

class QuantityPromotion extends DiscountController
{
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $purchased_qty, $free_dishes_number;

    //--------------------------------------------------------------------------
    // Setters and Getters.
    //--------------------------------------------------------------------------
    public function getPurchasedQty(){
        return $this->purchased_qty;
    }
    public function setPurchasedQty($pPurchasedQty){
        $this->purchased_qty = $pPurchasedQty;
        return $this;
    }
    public function getFreeDishesNumber(){
        return $this->free_dishes_number;
    }
    public function setFreeDishesNumber($pDishesFreeNumber){
        $this->free_dishes_number = $pDishesFreeNumber;
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
        return $dbSchema['promotion_quantity'];
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
        return $dbManager->orderBy(self::primaryKey, 1)
                         ->where(array("restaurant_id" => ["=", $this->getRestaurantId()]))
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]))
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getElementByDishId(){
        global $dateTime;
        $dbManager = new ManagerDB;
        $discountQty = $dbManager->where(array('discount_item_id' => ["=", $this->getDiscountItemId()],
                                    'status' => ["=", $this->getStatus()] ))
                                ->fetchObject(__CLASS__, $this::getTable());
        if($discountQty){
            $endDate = (new DateTime($discountQty->getEndDateTime()))->format('Y-m-d H:i:s');
            $currentDate = $dateTime->format('Y-m-d H:i:s');
            return ($endDate > $currentDate) ? $discountQty : false;
        }
        return false;
    }
    public function getElementByItemId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array('discount_item_id' => ["=", $this->getDiscountItemId()]))
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
    public function updateQtyById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]))
                         ->updateRows($this, $this::getTable(), ["purchased_qty"]);
    }
    public function updateFreeNbrById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]))
                         ->updateRows($this, $this::getTable(), ["free_dishes_number"]);
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
        return $dbManager->where(array(parent::primaryKey => ["IN", $this->getId()]), true) // remove the apostrophes
                         ->delete($this::getTable());
    }
    public function removeByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array("restaurant_id" => ["=", $this->getRestaurantId()]))
                         ->delete($this::getTable());
    }
}
//by percentage or by amount by dish or by menu
class CustomerDiscount {

    const  primaryKey = "id";

    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $id, $restaurant_id, $min_discount_price, $voucher_id, $status;
    

    //--------------------------------------------------------------------------
    // Setters and Getters.
    //--------------------------------------------------------------------------
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
    public function getMinDiscountPrice(){
        return $this->min_discount_price;
    }
    public function setMinDiscountPrice($pDiscountPrice){
        $this->min_discount_price = $pDiscountPrice; // 1 : Price, 2: Percent
        return $this;
    }
    public function getVoucherId(){
        return $this->voucher_id;
    }
    public function setVoucherId($pvoucher_id){
        $this->voucher_id = $pvoucher_id;
        return $this;
    }
    public function getStatus(){
        return $this->status;
    }
    public function setStatus($pStatus){
        $this->status = $pStatus;
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
        return $dbSchema['customer_discount'];
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
        return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]))
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
        return $dbManager->update($this, $this::getTable(), array(self::primaryKey => ["=", $this->getId()]));
    }
    public function updateDiscountValById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]))
                         ->updateRows($this, $this::getTable(), ["discount_value"]);
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
// is used 1time? or first shop? or for all clients?
class DiscountCode extends DiscountController{
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $voucher_uid, $voucher_value, $voucher_type;

    //--------------------------------------------------------------------------
    // Setters and Getters.
    //--------------------------------------------------------------------------
    public function getVoucherUid(){
        return $this->voucher_uid;
    }
    public function setVoucherUid($pVoucherUid){
        $this->voucher_uid = $pVoucherUid;
        return $this;
    }
    public function getVoucherValue(){
        return $this->voucher_value;
    }
    public function setVoucherValue($pVoucherPrice){
        $this->voucher_value = $pVoucherPrice;
        return $this;
    }
    // 'P' : price (i.e -20$) '%': percent (i.e: 20%)
    public function getVoucherType(){
        return $this->voucher_type;
    }
    public function setVoucherType($pVoucherType){
        $this->voucher_type = $pVoucherType;
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
        return $dbSchema['discount_code'];
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
    public function getElementByItemId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array('discount_item_id' => ["=", $this->getDiscountItemId()]))
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getElementByItemIdWS(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array('discount_item_id' => ["=", $this->getDiscountItemId()],
                                        'status' => ["=", $this->getStatus()]))
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function checkCodeByDishId(){
        global $dateTime;
        $dbManager = new ManagerDB;
        $discount = $dbManager->where(array('discount_item_id' => ["=", $this->getDiscountItemId()],
                                    'restaurant_id' => ["=", $this->getRestaurantId()],
                                    'status' => ["=", $this->getStatus()] ))
                                ->fetchObject(__CLASS__, $this::getTable());
        if($discount){
            $endDate = (new DateTime($discount->getEndDateTime()))->format('Y-m-d H:i:s');
            $currentDate = $dateTime->format('Y-m-d H:i:s');
            return ($endDate > $currentDate) ? $discount : false;
        }
        return false;
    }
    public function getAllByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array("restaurant_id" => ["=", $this->getRestaurantId()]))
                         ->orderBy(self::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllByPercentage(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array("voucher_type" => ["=", '%']))
                         ->orderBy(self::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllByPrice(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array("voucher_type" => ["=", '$']))
                         ->orderBy(self::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]))
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
        return $dbManager->update($this, $this::getTable(), array(self::primaryKey => ["=", $this->getId()]));
    }
    public function updateVoucherValueById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]))
                         ->updateRows($this, $this::getTable(), ["voucher_value"]);
    }
    public function removeElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]))
            ->delete($this::getTable());
    }
    public function removeElementsById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(parent::primaryKey => ["IN", $this->getId()]), true) // remove the apostrophes
                         ->delete($this::getTable());
    }
    public function removeByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array("restaurant_id" => ["=", $this->getRestaurantId()]))
            ->delete($this::getTable());
    }
}
class SpecialCustomerDiscount extends CustomerDiscount
{
    public $customer_id;

    public function getCustomerId(){
        return $this->customer_id;
    }
    public function setCustomerId($pID){
        $this->customer_id = $pID;
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
        return $dbSchema['special_customers_discount'];
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
    public function getElementByItemId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array('discount_item_id' => ["=", $this->getDiscountItemId()]))
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]))
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
        return $dbManager->update($this, $this::getTable(), array(self::primaryKey => ["=", $this->getId()]));
    }
    public function removeElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(self::primaryKey => ["=", $this->getId()]))
                         ->delete($this::getTable());
    }
    public function removeByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array("restaurant_id" => ["=", $this->getRestaurantId()]))
                         ->delete($this::getTable());
    }
    public function removeElementsById(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array(parent::primaryKey => ["IN", $this->getId()]), true) // remove the apostrophes
                         ->delete($this::getTable());
    }
    public function removeByCustomerId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(array("customer_id" => ["=", $this->getRestaurantId()]))
                         ->delete($this::getTable());
    }
}

?>