<?php 
/**
 * 
 * 
 */

 class UsersRequests {
    const primaryKey = "id";

    public $id, $user_id, $user_auth, $request, $description, $date_time;

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
    public function setUserId($pId){
        $this->user_id = $pId ;
        return $this;
    }
    public function getUserAuth(){
        return $this->user_auth ;
    }
    public function setUserAuth($pId){
        $this->user_auth = $pId ;
        return $this;
    }
    public function getRequest(){
        return $this->request ;
    }
    public function setRequest($pRequest){
        $this->request = $pRequest ;
        return $this;
    }
    public function getDescription(){
        return $this->description ;
    }
    public function setDescription($pDescription){
        $this->description = $pDescription ;
        return $this;
    }
    public function getDateTime(){
        return $this->date_time ;
    }
    public function setDateTime($pDateTime){
        $this->date_time = $pDateTime ;
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
        return $dbSchema['users_requests'];
    }
    public function resetAI(){
        $dbManager = new ManagerDB;
        $dbManager->resetAutoIncrement($this::getTable());
        return $this;
    }
    public function getAll(){
        $dbManager = new ManagerDB;
        return $dbManager->orderBy($this::primaryKey, 1)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getAllByUser(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array('user_id' => ["=", $this->getUserId()], 'user_auth' => ["=", $this->getUserAuth()]) )
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array($this::primaryKey => ["=", $this->getId()]) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function rowCount(){
        $dbManager = new ManagerDB;
        return $dbManager->count($this::getTable(), self::primaryKey);
    }
    public function addElement($lastId = null){
        $dbManager = new ManagerDB;
        return (is_null($lastId)) ? $dbManager->insert($this, $this::getTable()) : $dbManager->insert($this, $this::getTable(), true);
    }
    public function removeElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getId()]) )
                         ->delete($this::getTable());
    }
    public function removeElementByUser(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array('user_id' => ["=", $this->getUserId()], 'user_auth' => ["=", $this->getUserAuth()]) )
                         ->delete($this::getTable());
    }
 }
 
?>