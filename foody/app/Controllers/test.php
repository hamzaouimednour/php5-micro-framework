<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 

<?php
// FIRST CALLED FILE
include "ManagerDB.class.php";
include "/opt/lampp/htdocs/web/Project_Build/WebCoding/WebBuild/app/Helpers/HandlerHelper.php";

class Adm2{
    public $test2;
    function getTest2(){
        return $this->test2;
    }
    function setTest2($p){
        $this->test2 = $p;
    }
}
class Adm extends Adm2{
    const primaryKey = "id";
    public $id, $lib,$test;
    public function __construct(){
    }
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
    public function getUpdatedRows(){
        $db = new ManagerDB;
        return $db->where(array('id' => ["=", $this->getId()] ))->fetchObject(__CLASS__, 'test');
    }
    public function update(){
        print_r($this);
        $db = new ManagerDB;
        return $db->where(array('id' => ["=", $this->getId()] ))->updateRows($this, 'test', ['lib']);
    }
    public function getAll(){
        $db = new ManagerDB;
        return $db->fetch(__CLASS__, 'test');
    }
    public static function getRows(){
        $db = new ManagerDB;
        return $db->fetch(__CLASS__, "test", "id");
    }
    public function getLastRow(){
        $db = new ManagerDB;
        return $db->getLastRow('test', 'id');
    }
    public function add(){
        $db = new ManagerDB;
        return $db->insert($this, "test");
    }
    // public function resetIgnoredRows(array $ignoredRows){
    //     if(in_array(self::primaryKey, $ignoredRows)){
    //         $ignoredRows = array_diff($ignoredRows, [self::primaryKey]);
    //     }
    //     foreach (self::__getFields() as $propriety) {
    //         if(!in_array($propriety, $ignoredRows)){
    //             // call_user_func( array($object, 'get'.Handler::getMethod($property))
    //             call_user_func_array(array($this, 'set'.Handler::getMethod($property)), [NULL]);
    //         }
    //     }        
    // }
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
echo"<pre>";
$db = new ManagerDB();
// var_dump($db);exit;

print_r($db->getAttributes("test"));
$adm = new Adm();
var_dump($adm);
$db->resetAutoIncrement("test");
// call_user_func_array(array($adm, "setId"), [16]);
// $adm = $adm->getUpdatedRows();
// $adm->setId(16);
// $adm->setLib(16);
// print_r($adm->update());
// $adm->getAll();
// echo "ned";
$adm = $adm->getAll();
foreach ($adm as $value) {
   var_dump($value->getTest2());
}
exit;
// if($adm->add())
// echo "inseted";
// else
// echo $db->getExceptionMessage();
// exit;
// $a = $db->fetch(get_class($adm), 'test', Handler::sqli_escape(Handler::escape('lib\'')) );
echo "<table border=1 id='tab'>
<tr>
<td>ID</td>
<td>Lib</td>
</tr>";
$i = 0;
foreach($adm->getRows() as $admin){
    $i++;
    $id = !($i==1) ?:$admin->getId(); 
    echo "
    <tr>
    <td>".$admin->getId()."</td>
    <td>".$admin->getLib()."</td>
    </tr>";
}
echo "</table>
<input type='hidden' id='lastid' value='".$adm->getLastRow()."'>";
?>
<script>
$(document).ready(function(){
    $.ajaxSetup({cache: false});
    setInterval(function(){
        var lastid = $("#lastid").val();
        $.post( "test2.php", { lastuid : lastid})
        .done(function( data ) {
            $("#lastid").val(data.newLastId);
            $('#tab tr:first').after(data.data); //'<tr><td>hhh</td><td>hhh</td><td>hhh</td></tr>'
        });
    }, 5000);
});
</script>