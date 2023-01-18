<?php
defined('PATH_ROOT') or die('Attempt Unauthorized, Access Denied.');

class Cookie{
    
    public static $expiration = 0;
    public static $path = '/';
    public static $domain = NULL;
    public static $salt = 0; // Magic salt to add to the cookie
    public static $secure = false; // Only transmit cookies over secure HTTPS connections $_SERVER["HTTPS"]
    public static $httponly = false; // Only transmit cookies over HTTP, disabling Javascript access

    public static function expiration($expire){
        self::$expiration = $expire;
    }
    public static function path($path){
        self::$path = $path;
    }
    public static function domain($domain){
        self::$domain = $domain;
    }
    public static function salt($salt){
        self::$salt = $salt;
    }
    public static function secure(){
        self::$secure = true;
    }
    public static function HttpOnly(){
        self::$httponly = true;
    }
    public static function get($key, $default = NULL){
        if ( ! isset($_COOKIE[$key])){
            // The cookie does not exist
            return $default;
        }
        // Get the cookie value
        $cookie = $_COOKIE[$key];
        // Find the position of the split between salt and contents
        $split = strlen(Cookie::cipher($key, NULL));
        if (isset($cookie[$split]) AND $cookie[$split] === '.'){
            // Separate the salt and the value
            list ($hash, $value) = explode('.', $cookie, 2);
    
            if (Handler::slow_equals(Cookie::cipher($key, $value), $hash)){
                // Cookie signature is valid
                return $value;
            }
            // The cookie signature is invalid, delete it
            static::delete($key);
        }
        return $default;
    }
    
    //set($name, $value, $expiration = null, $path = null, $domain = null, $secure = null, $httponly = null)
    public static function set($name, $value, $lifetime = NULL){
        if ($lifetime === NULL){
            // Use the default expiration
            $lifetime = Cookie::$expiration;
        }
        if ($lifetime !== 0){
            // The expiration is expected to be a UNIX timestamp
            $lifetime += static::_time();
        }
        // Add the salt to the cookie value
        $value = Cookie::cipher($name, $value).'.'.$value;
        return static::_setcookie($name, $value, $lifetime, Cookie::$path, Cookie::$domain, Cookie::$secure, Cookie::$httponly);
    }

    public static function _setcookie($name, $value, $expire, $path, $domain, $secure, $httponly){
	    return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    protected static function _time(){
        return time();
    }

    public static function cipher($name, $value){
        // Require a valid salt
        if ( ! Cookie::$salt){
            return false;
        }
        // Determine the user agent
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : 'unknown';
        return hash_hmac('sha1', $agent.$name.$value.Cookie::$salt, Cookie::$salt);
    }
    
    public static function delete($name){
        // Remove the cookie
        unset($_COOKIE[$name]);
        // Nullify the cookie and make it expire
        return static::_setcookie($name, NULL, -1, Cookie::$path, Cookie::$domain, Cookie::$secure, Cookie::$httponly);
    }
    public static function read($key){
        // Remove the cookie
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : NULL;
    }

}

?>