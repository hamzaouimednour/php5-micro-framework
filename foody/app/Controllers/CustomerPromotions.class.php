<?php 
//add promotions for specific client
/**
 * {"CODE":"ABCDE"}
 * {"%":"20","DISH":[1,2,3,4,5]}
 * {"VALUE":"20","DISH":['ALL']}
 */
class CustomerPromotions {

    const   primaryKey = "id"; // Unique ID

    public static   $promoTypes = ["CODE", "%", "PRICE"]; // Unique ID
        
    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $id, $customer_id, $restaurant_id, $promotion_type_json, $start_date_time, $end_date_time, $status;

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
	public function getCustomerId(){
		return $this->customer_id ;
	}
	public function setCustomerId($pCustomerId){
		$this->customer_id = $pCustomerId ;
		return $this;
	}
	public function getRestaurantId(){
		return $this->restaurant_id ;
	}
	public function setRestaurantId($pRestaurantId){
		 $this->restaurant_id = $pRestaurantId ;
		return $this;
	}
	public function getPromotionTypeJson(){
		return $this->promotion_type_json ;
	}
	public function setPromotionTypeJson($pPromotionType){
		$this->promotion_type_json = $pPromotionType ;
		return $this;
	}
	public function getStartDateTime(){
		return $this->start_date_time ;
	}
	public function setStartDateTime($pStartDateTime){
		$this->start_date_time = $pStartDateTime ;
		return $this;
	}
	public function getEndDateTime(){
		return $this->end_date_time ;
	}
	public function setEndDateTime($pEndDateTime){
		$this->end_date_time = $pEndDateTime ;
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
        return $dbSchema['customer_promotion'];
    }
    public function getAll(){
        $dbManager = new ManagerDB;
        return $dbManager->orderBy(parent::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()]) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function getByCustomerId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("customer_id" => ["=", $this->getCustomerId()]) )
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("restaurant_id" => ["=", $this->getRestaurantId()]) )
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
        return $dbManager->update($this, $this::getTable(), array(self::primaryKey => ["=", $this->getId()]) );
    }
    public function removeElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()]) )
                         ->delete( $this::getTable() );
    }
    public function removeByCustomerId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("customer_id" => ["=", $this->getCustomerId()]) )
                         ->delete( $this::getTable() );
    }
    public function removeByRestaurantId(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array("restaurant_id" => ["=", $this->getRestaurantId()]) )
                         ->delete( $this::getTable() );
    }
}
?>