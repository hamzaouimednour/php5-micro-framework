<?php



include "ManagerDB.class.php";
include "Handler.class.php";
$db = new ManagerDB();
class Adm{
    private $id, $lib;
    function getId(){
        return $this->id;
    }
    function getLib(){
        return $this->lib;
    }
    function setId($p){
        $this->id = $p;
    }
    function setLib($p){
        $this->lib = $p;
    }
    public static function __getFields(){
        return array_keys(get_class_vars(__CLASS__));
    }
    public function getUpdatedRows($c){
        global $db;
        return $db->fetchCondition(__CLASS__, 'test', array('id' => array("operator" => ">", "value" => $c))); //DESC
    }
    public function getRows(){
        global $db;
        return $db->fetch(__CLASS__, 'test');
    }
    public function getLastRow(){
        global $db;
        return $db->getLastRow('test', 'id');
    }
}
class dbConnection{
    protected $dbConnection;
    public function openConnection(){
        try{
            // Connect to a MySQL database using defined constants.
            $this->dbConnection = new PDO('mysql:host=localhost;dbname=website', 'root', '');
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
$adm = new Adm();
// if($db->insert($adm, 'test'))
// echo "inseted";
// else
// echo $db->getExceptionMessage();
// exit;
// $a = $db->fetch(get_class($adm), 'test', Handler::sqli_escape(Handler::escape('lib\'')) );
$v = "";
foreach($adm->getUpdatedRows($_POST['lastuid']) as $admin){
    $v.= "<tr><td style='background-color: #f1f1c1;'>".$admin->getId()."</td><td style='background-color: #f1f1c1;'>".$admin->getLib()."</td></tr>";
}
$result['newLastId'] = $adm->getLastRow();
$result['data'] = $v;
header('Content-type: application/json'); 
echo json_encode($result);
?>