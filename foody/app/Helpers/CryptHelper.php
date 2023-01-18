<?php
/*
 * Generate a secure hash for a given password.
 */
class CryptHelper{
    /**
     * The default salt used in hash.
     * @private
     * @var string $salt
     */
    // const salt = '@Dr4gun0v&$ecurekey#';

    /**
     * CryptHelper->hash() : hash passwd by a default hash algorithm.
     * @private
     * @param string $pwd
     * @return string
     */
    static function hash($pwd){
        return hash_hmac('whirlpool', $pwd, hash('gost-crypto', serialize($pwd)));
    }
    /**
     * crypt a password by the default hash.
     *
     * @param string $pwd
     * @return string
     */
    static function crypt($pwd){
        // list possible algorithmes for crypt using : print_r(hash_algos());
        return crypt( self::hash($pwd), '$' . hash('fnv164', serialize($pwd)) );
    }
    /**
     * verify a password by a givern hash.
     *
     * @param string $password
     * @param string $hash
     * @return boolean
     */
    static function verify($pwd, $hash){
        /* 
         * Regenerating with an available hash as the options parameter should
         * produce the same hash if the same password is passed.
         */
        return crypt( self::hash($pwd), $hash ) == $hash;
    }
}

/**
 * Encryption : used allmost for Cookies.
 */
class Encryption {
    private static $skey = 'OTkxMTExMTExMDcxMDUxMDExMTUzNTQ4NDg0OQ'; // change this ASCII

    public static function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }

    public static function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public static function encode($value){ 
        if(!$value){return false;}
        $text = $value;
        // or MCRYPT_CAST_128 or MCRYPT_CAST_256 or MCRYPT_LOKI97 : change key
        $iv_size = mcrypt_get_iv_size(MCRYPT_RC2, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RC2, hash('ripemd256',self::$skey), $text, MCRYPT_MODE_ECB, $iv);
        return trim(self::safe_b64encode($crypttext)); 
    }

    public static function decode($value){
        if(!$value){return false;}
        $crypttext = self::safe_b64decode($value); 
        $iv_size = mcrypt_get_iv_size(MCRYPT_RC2, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RC2, hash('ripemd256',self::$skey), $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }
    
}

?>
