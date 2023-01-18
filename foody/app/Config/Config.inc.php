<?php
/**
 * DB Connection established by creating instances of the PDO base class.
 * @var PDO $dbConnection
 * @throws PDOException
 */
class dbConnection{
    protected $dbConnection;
    public function openConnection(){
        try{
            // Connect to a MySQL database using defined constants.
            $this->dbConnection = new PDO(DBMS.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
            // PDO::ATTR_DEFAULT_FETCH_MODE: Set PDO default fetch mode
            $this->dbConnection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_CLASS);
            $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->dbConnection;
        }catch (PDOException $error){
            die('Connection Failed: ' . $error->getMessage());
        }
    }
    public function endConnection() {
   	    $this->dbConnection = NULL;
	}
}
?>
