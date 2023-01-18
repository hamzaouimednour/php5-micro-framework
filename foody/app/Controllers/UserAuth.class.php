<?php 
/**
 * @author Hamzaoui Med Nour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 * @throws PDOException
 */
class UserAuth {

    /**
     * @var string Define a constant caontain the name of the primary key of dbTable.
     */
    const   primaryKey = "uaid"; // Unique Authority ID
    const   roles  = array(
                            '1' => 'Administrator' ,
                            '2' => 'Restaurant' ,
                            '3' => 'Delivery' ,
                            '4' => 'Customer'
                        );

    //--------------------------------------------------------------------------
    // @property mixed.
    //--------------------------------------------------------------------------
    public $uaid, $authority;

    //--------------------------------------------------------------------------
    // Setters and Getters.
    //--------------------------------------------------------------------------
    public function getUaid(){
        return $this->uaid ;
    }
    public function setUaid($pUaid){
        $this->uaid = $pUaid ;
    }
    public function getAuthority(){
        return $this->authority ;
    }
    public function setAuthority($pAuthority){
        $this->authority = $pAuthority ;
    }

    //--------------------------------------------------------------------------
    // Controller Methods.
    //--------------------------------------------------------------------------
    public static function __getFields(){
        return array_keys(get_class_vars(__CLASS__));
    }
    public static function getTable(){
        global $dbSchema;
        return $dbSchema['users_authority'];
    }
    public function getAll(){
        $dbManager = new ManagerDB;
        return $dbManager->orderBy(self::primaryKey)
                         ->fetch(__CLASS__, $this::getTable());
    }
    public function getUserAuthByUaid(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getUaid()]) )
                         ->fetchObject(__CLASS__, $this::getTable());
    }
    public function rowCount(){
        $dbManager = new ManagerDB;
        return $dbManager->count($this::getTable(), self::primaryKey);
    }
    public function addUserAuth(){
        $dbManager = new ManagerDB;
        return $dbManager->insert($this, $this::getTable());
    }
    public function updateByUaid(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array(self::primaryKey => ["=", $this->getUaid()]) );
    }
    public function removeByUaid(){
        $dbManager = new ManagerDB;
        return $dbManager->where( array(self::primaryKey => ["=", $this->getUaid()]) )
                         ->delete($this::getTable());
    }
}
?>