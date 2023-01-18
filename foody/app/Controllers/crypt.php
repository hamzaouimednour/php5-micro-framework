<?php
session_start();
var_dump($_SESSION);
if($_COOKIE[session_name()]){
    if(isset($_SESSION['admin'])){
        die("die : hi ".$_SESSION['admin']);
    }
}
if($_GET['user'] == 'admin'){
    $cookieParams = session_get_cookie_params();
    ini_set('session.use_only_cookies', 1);
    session_set_cookie_params(time()+31536000, $cookieParams["path"], $cookieParams["domain"], false, true);
    setcookie(session_name(), session_id(), time() + (10 * 365 * 24 * 60 * 60) , $cookieParams["path"], $cookieParams["domain"], false, true);
    $_SESSION['admin'] = 'admin';
    echo "new : hi ".$_SESSION['admin'];
}else {
    echo "error";
}






exit;

class Encryption {
    var $skey = "9911111110710510111535484849"; // change this ASCII

    public  function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }

    public function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public  function encode($value){ 
        if(!$value){return false;}
        $text = $value;
        // or MCRYPT_CAST_128 or MCRYPT_CAST_256 or MCRYPT_LOKI97 : change key
        $iv_size = mcrypt_get_iv_size(MCRYPT_RC2, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RC2, hash('ripemd256',$this->skey), $text, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext)); 
    }

    public function decode($value){
        if(!$value){return false;}
        $crypttext = $this->safe_b64decode($value); 
        $iv_size = mcrypt_get_iv_size(MCRYPT_RC2, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RC2, hash('ripemd256',$this->skey), $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }
    
}

?>
<?php
error_reporting(E_ALL);
ini_set('display_errors',E_ALL);

ini_set('session.use_only_cookies', 1);
$cookieParams = session_get_cookie_params();
if(isset($_GET['logout'])){
	session_set_cookie_params(0, $cookieParams["path"], $cookieParams["domain"], false, true);
	session_start();
	session_unset();	//unset($_SESSION['myvar']);
	session_regenerate_id(true);
	session_destroy();
	
	header("Location: http://". $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
	exit();
	
}elseif(isset($_GET['login'])){
	session_set_cookie_params(time()+31536000, $cookieParams["path"], $cookieParams["domain"], false, true);
	session_start();
	$_SESSION['myvar']="user 1 Logged in";
	// header("Location: http://". $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
	// exit();
	
}else{
	session_set_cookie_params(0, $cookieParams["path"], $cookieParams["domain"], false, true);
	session_start();
	
	if(isset($_SESSION['myvar'])){
		setcookie(session_name(), session_id(), time() + (10 * 365 * 24 * 60 * 60) , $cookieParams["path"], $cookieParams["domain"], false, true);
	}
	
}

echo session_id()."<br />";

if(isset($_SESSION['myvar'])){
	echo "myvar: ".$_SESSION['myvar']."<br />";
	echo "<a href='?logout=true'>logout</a><br />";
}else{
	echo "<a href='?login=true'>login</a><br />";
}
?>

