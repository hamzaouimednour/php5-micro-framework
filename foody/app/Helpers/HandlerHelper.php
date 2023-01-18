<?php
/**
 *
 */
class Handler{

    static function randomString($length){
    	$randomStr = '';
    	$ascii = array_merge(array_merge(range(46,57), range(64,90)),range(97,122));
    	for($i=1; $i <= $length; $i++){
    	    $byte = rand($i, sizeof($ascii)-1);
    	    $randomStr .= chr($ascii[$byte]);
    	}
    	return $randomStr;
    }
    /**
     * generateOrderId : generate an unique Order ID (e.g: transaction ID, payment ...) .
     * 
     * @param [int] $uid
     * @param [int] $NbrCmd
     * @return string
     */
    static function generateOrderId($uid, $NbrOrder){
        $orderFormat = date('ymd').'.'.$uid.'.'.$NbrOrder; // i.e : 190222.50.3
    	return mb_strtoupper(hash('fnv132', $orderFormat));
    }
    /**
     * getHash : generate hash using the hash() function with an optional key (e.g: img name...).
     *
     * @param [string] $key
     * @return string
     */
    static function generateAuthKey($key = NULL){
    	// return md5(time().uniqid(rand()).microtime());
    	$key = !is_null($key) ?: (time().uniqid(rand()).microtime());
    	return hash('fnv1a32',time().uniqid(rand()).microtime()).hash('adler32',$key).'_'.hash('crc32',$key);
    }
    static function generateNameKey(){
    	// return md5(time().uniqid(rand()).microtime());
    	return hash('crc32',(time().uniqid(rand()).microtime()));
    }
    static function hashCookie($data){
    	return hash_hmac('md5', $data, serialize($data));
    }
    /**
     * require_files() : call require_once() of many files.
     * @param string $path : associated path to scan.
     * @var array $scandir : array contain all the scaned files.
     * @static accessible without instantiation of the class.
     */
    static function require_files($path){
        $scandir = array_diff(scandir($path), array('..', '.'));
        foreach($scandir as $file){
            (is_file($file) && substr($file, -3) === "php") ? require_once($path.$file) : NULL;
        }
    }
    static function directoryList($path){
        $scandir = array_diff(scandir($path), array('..', '.'));
        foreach($scandir as $file){
            if (is_dir( $path . $file)) {
                $dirs[] = lcfirst($file);
            }
        }
        return (!empty($dirs)) ? $dirs : false;
    }
    static function fileList($path){
        $scandir = array_diff(scandir($path), array('..', '.'));
        foreach($scandir as $file){
            if (is_file( $path . $file) && substr($file, -3) === "php" ) {
                $files[] = preg_replace('/\\.[^.\\s]{3,4}$/', '', lcfirst($file));
            }
        }
        return (!empty($files)) ? $files : false;
    }
    static function size($size){
        $size = max(0, (int)$size);
        $units = array( ' B', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, '.', ',') . $units[$power];
    }
    static function wrap($string, $limit, $end = '...'){
        // Get truncated string with specified width.
        return empty($string) ? '' : mb_strimwidth($string, 0, $limit, $end);
    }

    static function check_array(array $array){
        // Returns the key of any empty case in the array, FALSE otherwise.
        return array_search(NULL, $array);
    }
    static function array_check(array $array, array $fields, $zero = null){
        $check_array = array();
            // Returns the key if founded in the array, FALSE otherwise.
        foreach ($array as $key => $value) {
            if (in_array($key, $fields) && ($value === '' or  $value === null) ){
                $check_array[] = $key;
            }
            if(!is_null($zero)){
                if (in_array($key, $fields) && empty($value) ){
                    $check_array[] = $key;
                }
            }
        }
        return !empty($check_array); //true : empty field; false : none empty fields
    }
    static function array_equal(array $a, array $b) {
        return (
            is_array($a) 
            && is_array($b) 
            && count($a) == count($b) 
            && serialize($a) === serialize($b)
        );
    }
    static function check_object($object){
        if(!is_object($object)){
            $_ERROR['011'] = "passed variable is not an object";
            return $_ERROR;
        }
    }
    static function is_email($email){
        return filter_var(trim(mb_strtolower($email)), FILTER_VALIDATE_EMAIL);
    }
    static function getNumber($data){
        return mb_ereg_replace("[^0-9]", '', $data);
    }
    static function getNumberMap($data){
        return mb_ereg_replace("[^0-9],", '', $data);
    }
    static function getString($data){
        return mb_ereg_replace("[^A-Za-z ]", '', mb_strtolower(trim(strip_tags($data))) );
    }
    static function getDateTime(){
        $dateTime = new DateTime(NULL, new DateTimeZone('Africa/Tunis'));
        return $dateTime->format('Y-m-d H:i:s');
    }
    static function getElapsedTime($time_1, $time_2){
        $first  = new DateTime( $time_1 );
        $second = new DateTime( $time_2 );
        
        $diff = $first->diff( $second );
        
        return $diff->format( '%H:%I:%S' ); // -> 00:25:25
    }
    static function getMinutes($time){
        $time = explode(':', $time); // <- 00:25:25
        return round(($time[0]*60) + ($time[1]) + ($time[2]/60)); // -> 25
    }
    static function getMinutesAVG($time1, $time2){
        $avg = round(($time1 + $time2) / 2);
        return round($avg); // -> 25
    }
    static function stripWhitespace($str){
        return preg_replace('/\s{2,}/u', ' ', preg_replace('/[\n\r\t]+/', '', $str) );
    }
    static function escape($data){
        return mb_ereg_replace("[^A-Za-z0-9\.\-_@ ]", '', mb_strtolower(trim(strip_tags($data))) );
    }
    static function sqli_escape($data){
        // SQLInjection-escape : addslashes();
        return filter_var(trim($data), FILTER_SANITIZE_MAGIC_QUOTES);
    }
    static function html_escape($data){
        // HTML-escape : htmlspecialchars($data, ENT_QUOTES, CHARSET);
        return filter_var(self::stripWhitespace($data), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    static function is_length($exp, $min, $max){
        return ((mb_strlen($exp, 'utf-8') <= $max) && (mb_strlen($exp, 'utf-8') >= $min));
    }
    static function is_number($val, $len){
        // Check if phone number include $len chars.
        return mb_ereg_match('^[0-9]{'.$len.','.$len.'}$', $val);
    }
    static function is_url($url){
		return filter_var($url, FILTER_SANITIZE_URL);
    }
    static function is_empty($var){
        return (is_null($var) || $var === '' ); 
    }
    static function removeSlashes($data){
		return stripslashes(trim($data));
    }
    /**
     * use this function when u calculate with currency_format()
     *
     * @param [type] $number
     * @param integer $decimals
     * @return string
     */
    static function toFixed($number, $decimals = 3) {
        return number_format($number, $decimals, '.', "");
    }  
    static function currency_format($price){
        return number_format(($price/1000),3);
    }
    static function currency_format_reverse($price){
        return (int) self::getNumber($price);
    }
    static function reverse_currency_format($price){
        return $price * 1000;
    }
    static function percentage($total, $discount_amount){
        return ($total - ($total * ($discount_amount / 100)));
    }
    static function getPriceByDiscount($main_price, $discount_type, $discount_amount){
        if($discount_type == '%'){
            return self::percentage($main_price, $discount_amount);
        }
        if($discount_type == '$'){
            return self::currency_format(($main_price - self::currency_format($discount_amount)));
        }
    }

    static function is_utf8($str) {
        return (bool) preg_match('//u', $str);
    }
    static function replace_first($replace, $find, $subject) {
        return implode($replace, explode($find, $subject, 2));
    }
    static function getEncoding($str) {
        return mb_detect_encoding($str, mb_list_encodings());
    }
    /**
     * Format the name of method (i.e: user_id => UserId, uid => Uid)
     */
    static function getMethod($str) {
        $method = '';
        if(!empty($str) and mb_strpos($str, "_") != FALSE){
            foreach(mb_split("_", $str) as $word){
                $method .= ucfirst(mb_strtolower($word));
            }
        }else{
            $method = ucfirst($str);
        }
        return $method;
    }
    static function getHeaders() {
        if(function_exists('apache_request_headers')) {
            return apache_request_headers();
        }
        $headers = array();
        $keys = preg_grep('{^HTTP_}i', array_keys($_SERVER));
        foreach($keys as $val) {
            $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($val, 5)))));
            $headers[$key] = $_SERVER[$val];
        }
        return $headers;
    }
    static function getUserAgent(){
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			return $_SERVER['HTTP_USER_AGENT'];
		}
		return 'UNKNOWN';
    }
    static function getUserIp(){
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		} else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if(isset($_SERVER['HTTP_X_FORWARDED'])) {
			return $_SERVER['HTTP_X_FORWARDED'];
		} else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_FORWARDED_FOR'];
		} else if(isset($_SERVER['HTTP_FORWARDED'])) {
			return $_SERVER['HTTP_FORWARDED'];
		} else if(isset($_SERVER['REMOTE_ADDR'])) {
			return $_SERVER['REMOTE_ADDR'];
		}
		return 'UNKNOWN';
    }
    static function html2rgb($color){
		if ($color[0] == '#'){
			$color = substr($color, 1);
		}
		if (strlen($color) == 6){
			list($red, $green, $blue) = array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5]);
		} elseif (strlen($color) == 3) {
			list($red, $green, $blue) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
		} else {
			return false;
		}
		$red = hexdec($red);
		$green = hexdec($green);
		$blue = hexdec($blue);
		return array($red, $green, $blue);
    }
    static function htmlEntities($str){
		$output = "";
		for ($i = 0; $i < strlen($str); $i++){
			$output .= '&#' . ord($str[$i]) . ';';
		}
		return $output;
    }
    public static function slow_equals($a, $b){
        $diff = strlen($a) ^ strlen($b);
        for($i = 0; $i < strlen($a) AND $i < strlen($b); $i++){
            $diff |= ord($a[$i]) ^ ord($b[$i]);
        }
        return $diff === 0; //true or false
    }
    static function getCdnImage($userUid, $img, $method, $w, $h){
        $userUid = ($userUid == 0) ? 0 : Handler::encryptInt($userUid);
        list($img, $ext) = explode('.', $img);
        $ext = array_search($ext, ['jpeg', 'jpg', 'png', 'gif']);
        return CDN . $userUid . "/$img/$ext/$method/$w/$h" ;
    }
    static function getCdnCoverImage($userUid, $img){
        $userUid = ($userUid == 0) ? 0 : Handler::encryptInt($userUid);
        return CDN . $userUid . "/$img" ;
    }
    /**
     * encryptInt(0) = 966
     * decryptInt(966) = 0
     * @param integer $var
     * @return int
     */
    static function encryptInt($var = 0){
        return (($var + 180) * 6);
    }
    static function decryptInt($var = 966){
        return (($var / 6) - 180);
    }

    static function getGMapJson(array $data, $api = "AIzaSyCkG1aDqrbOk28PmyKjejDwWZhwEeLVJbA"){
        ini_set("allow_url_fopen", 1);
        $params = array( 'key' => $api, 'origins' => $data['origins'], 'destinations' => $data['destinations'], 'mode' => 'driving', 'language' => 'fr-FR');
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?" . http_build_query($params);
        return json_decode(file_get_contents($url));
        // ->rows[0]->elements[0]->distance->value
    }
    static function deliveryFee($distance, $deliveryFeeInit, $distanceInit, $deliveryFeeUp) {
        $deliveryFee = $deliveryFeeInit;
        if ($distance > $distanceInit) { // distanceInit = 4.000 [4KM]
            $distanceUp = $distance - $distanceInit;
            $decimal = abs($distanceUp) - floor(abs($distanceUp));
            $distanceUpNew = floor($distanceUp) . '.' . floor($decimal * 10) . '00';
            // $quotient = floor($distanceUp / 1.0) + 1;
            // var remainder = mDis % 0.5;
            // var deliveryFeeUp = 0.500;
            $deliveryFee = $deliveryFeeInit + ($distanceUpNew * $deliveryFeeUp);
        }
        if ($deliveryFee < $deliveryFeeInit) {
            $deliveryFee = $deliveryFeeInit;
        }

        return self::toFixed($deliveryFee);
    }
    static function format_date_fr($date){
        $month = array(null, 'Janvier','F&eacute;vrier','Mars','Avril','Mai','Juin','Juillet','Ao&ucirc;t','Septembre','Octobre','Novembre','D&eacute;cembre');
        $day = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
        $date = new DateTime($date);
        $dayNum     =  $date->format('w');
        $monthNum   =  $date->format('n');
        $dayMonth   =  $date->format('d');
        $year       =  $date->format('Y');
        $time       =  $date->format('H:i:s');
        return $day[$dayNum] . ', ' . $dayMonth . ' ' . $month[$monthNum] . ' ' . $year . ' à ' . $time;
    }
    static function counter($id, $jsonf, $hidden=null){

		$json = file_get_contents($jsonf);
		$json = json_decode($json, true);
		if(is_null($json[$id])){
			$json[$id] = "0";
			file_put_contents($jsonf, json_encode($json));
		}
		$json[$id]++;
		file_put_contents($jsonf, json_encode($json));
		if(!is_null($hidden)){
			echo $json[$id];
		}
	}
    static function counter_specs($spec_id, $usr_id, $jsonf, $hidden=null){

		$json = file_get_contents($jsonf);
        $json = json_decode($json, true);
        if(is_array($json)){
            if(!array_key_exists($spec_id, $json)){
                $json[$spec_id][$usr_id] = "0";
                file_put_contents($jsonf, json_encode($json));
                // $json = file_get_contents($jsonf);
                // $json = json_decode($json, true);
            }
        }else{
            $json = array();
            if(!array_key_exists($spec_id, $json)){
                $json[$spec_id][$usr_id] = "0";
                file_put_contents($jsonf, json_encode($json));
            }
        }
        
		$json[$spec_id][$usr_id]++;
		file_put_contents($jsonf, json_encode($json));
		if(!is_null($hidden)){
			echo $json[$spec_id][$usr_id];
		}
	}
}

?>
