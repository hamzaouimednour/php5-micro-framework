<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */

 class LogsController {
    /**
     * @var string Define a constant caontain the name of the primary key of dbTable.
     */
    const  primaryKey = "id";

    //--------------------------------------------------------------------------
    // Properties.
    //--------------------------------------------------------------------------
    public $id, $user_id, $user_auth, $user_ip, $user_browser, $date_time, $online_status = NULL, $session_id = NULL;

    public function __construct($ini = NULL){
		if(!is_null($ini)){
			global $dateTime;
			$this->setUserIp( Handler::getUserIp() );
			$this->setUserBrowser( Handler::getUserAgent() );
			$this->setDateTime( $dateTime->format('Y-m-d H:i:s') );
			$this->setOnlineStatus("ON"); //OFF
		}
    }

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
	public function getUserAuth(){
		return $this->user_auth ;
	}
	public function setUserAuth($pUserAuth){
		$this->user_auth = $pUserAuth ;
		return $this;
	}
	public function getUserIp(){
		return $this->user_ip ;
	}
	public function setUserIp($pUserIp){
		$this->user_ip = $pUserIp ;
		return $this;
	}
	public function getUserBrowser(){
		return $this->user_browser ;
	}
	public function setUserBrowser($pUserBrowser){
		$this->user_browser = $pUserBrowser ;
		return $this;
	}
	public function getDateTime(){
		return $this->date_time ;
	}
	public function setDateTime($pDateTime){
		$this->date_time = $pDateTime ;
		return $this;
	}
	public function getOnlineStatus(){
		return $this->online_status ;
	}
	public function setOnlineStatus($pOnlineStatus){
		$this->online_status = $pOnlineStatus ;
		return $this;
	}
	public function getSessionId(){
		return $this->session_id ;
	}
	public function setSessionId($pSessionId){
		$this->session_id = $pSessionId ;
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
        return $dbSchema['users_logs'];
	}
	public function getAll(){
        $dbManager = new ManagerDB;
		return $dbManager->orderBy(self::primaryKey)
						 ->fetch(__CLASS__, $this::getTable());
    }
	public function getElementById(){
        $dbManager = new ManagerDB;
		return $dbManager->where( array(self::primaryKey => ["=", $this->getId()]) )
						 ->fetchObject(__CLASS__, $this::getTable());
	}
	public function isOnline(){
        $dbManager = new ManagerDB;
		$user_online =  $dbManager->where(
									array(
											"user_id" => ["=", $this->getUserId()],
											"user_auth" => ["=", $this->getUserAuth()],
											"online_status" => ["=", "ON"]
										)
								)
						 ->fetchObject(__CLASS__, $this::getTable());
		return !empty($user_online) ? true : false;
	}
	public function rowCount(){
        $dbManager = new ManagerDB;
        return $dbManager->count($this::getTable(), self::primaryKey);
    }
	public function isExist(){
		$dbManager = new ManagerDB;
		return $dbManager->where(
									array(
											"user_id" => ["=", $this->getUserId()],
											"user_auth" => ["=", $this->getUserAuth()]
										)
								)
						 ->checkExist($this::getTable());
    }
    public function addElement(){
        $dbManager = new ManagerDB;
        return $dbManager->insert($this, $this::getTable());
    }
    public function updateElementById(){
        $dbManager = new ManagerDB;
        return $dbManager->update($this, $this::getTable(), array(self::primaryKey => ["=", $this->getId()]) );
    }
    public function updateByUserId(){
		$dbManager = new ManagerDB;
		return $dbManager->update($this, $this::getTable(),
				array(
					"user_id" => ["=", $this->getUserId()],
					"user_auth" => ["=", $this->getUserAuth()]
				)
			);
    }
    public function removeElementById(){
        $dbManager = new ManagerDB;
		return $dbManager->where( array(self::primaryKey => ["=", $this->getId()]) )
						 ->delete($this::getTable());
    }
    public function removeByUserId(){
        $dbManager = new ManagerDB;
        return $dbManager->where(
									array(
										"user_id" => ["=", $this->getUserId()],
										"user_auth" => ["=", $this->getUserAuth()]
									) 
								)
						 ->delete($this::getTable());
    }
}
?>