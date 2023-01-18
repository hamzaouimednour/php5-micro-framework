<?php
/*
|--------------------------------------------------------------------------
| Request
|--------------------------------------------------------------------------
|
*/

define('URL_QUERY',     Handler::replace_first('', Request::getBaseURL(), $_SERVER['REQUEST_URI']));

class Request {
    static $proto;
    static function redirect($page, $bool = FALSE)
    {
        header("Cache-Control: no-cache,no-store, must-revalidate"); // HTTP/1.1
        header("HTTP/1.1 301 Moved Permanently");
        header("location:" . $page, $bool );
        exit;
    }

    static function getDomainName()
    {
        self::$proto = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
        return self::$proto . trim($_SERVER['HTTP_HOST'], '/');
    }

    static function getBaseURL()
    {
        $base = '';
        if (!empty($_SERVER['DOCUMENT_ROOT']) && !empty($_SERVER['SCRIPT_NAME']) && empty($base)) {
            $base = str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_NAME']);
            $base = dirname($base);
        } elseif (empty($base)) {
            $base = empty($_SERVER['SCRIPT_NAME']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
            $base = dirname($base);
        }
        if (strpos($_SERVER['REQUEST_URI'], $base) !== 0) {
            $base = '/';
        } elseif ($base != '/') {
            $base = trim($base, '/');
            $base = '/' . $base . '/';
        } else {
            // Workaround for Windows Web Servers
            $base = '/';
        }
        return $base;
    }
    
    static function processRequest()
    {
        if(mb_ereg_match(".*//", URL_QUERY) || mb_strpos(URL_QUERY, '/') === 0)
        {
            Request::redirect( HTML_PATH_ROOT . '404' );
        }
        return array_filter(explode('/', URL_QUERY), function($item) {
            return ($item !== NULL && $item !== ''); 
        });
    }
    static function get($key)
    {
        return !Handler::is_empty($_GET[$key]) ? $_GET[$key] : false;
    }
    static function post($key)
    {
        return !Handler::is_empty($_POST[$key]) ? $_POST[$key] : false;
    }
}