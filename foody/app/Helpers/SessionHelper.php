<?php
// ini_set('session.use_only_cookies', 1);
/*
|--------------------------------------------------------------------------
| Session Helper
|--------------------------------------------------------------------------
|
| Manager user Sessions, i.e : online, offline, Start/End Sessions.
| check if user session is same then get id and check session vars.
*/

//--------------------------------------------------------------------------
// Require Users Logs Controller.
//--------------------------------------------------------------------------

// error_reporting(E_ALL & ~E_NOTICE);
ini_set('session.cookie_secure', '0');

require_once  PATH_CONTROLLERS   . "UsersLogs.controller.php";

require_once  PATH_HELPERS       . "CookieHelper.php";

class Session {

    const front_user_session = "USSID";
    //--------------------------------------------------------------------------
    // Start no-expire Session for user.
    //--------------------------------------------------------------------------
    public static function start($lifetime = NULL){
        if(!is_null($lifetime)){

            ini_set('session.use_only_cookies', 1);
            $cookieParams = session_get_cookie_params();
            // session_set_cookie_params(time() + (10 * 365 * 24 * 60 * 60), $cookieParams["path"], $cookieParams["domain"], false, true);
            Cookie::_setcookie(session_name(), session_id(), time() + (10 * 365 * 24 * 60 * 60), $cookieParams["path"], $cookieParams["domain"], 0, 1);
        }
        session_start();
    }
    public static function multi_session_start($session_name, $lifetime = NULL){

        ini_set("session.use_strict_mode",true);

        session_name($session_name);
        
        $cookieParams = session_get_cookie_params();

        if (isset($_COOKIE[$session_name]))
            session_id($_COOKIE[$session_name]); // not doing this will simply reopen the first session again
        else
            session_id(sha1(mt_rand()));
        
            if(!is_null($lifetime)){
                ini_set('session.use_only_cookies', 1);
                $cookieParams = session_get_cookie_params();
                session_set_cookie_params(time()+31536000, $cookieParams["path"], $cookieParams["domain"], false, true);
                setcookie(session_name(), session_id(), time() + (10 * 365 * 24 * 60 * 60), $cookieParams["path"], $cookieParams["domain"], 0, 1);
            }else{
                session_set_cookie_params(0, $cookieParams["path"], $cookieParams["domain"], false, true);
            }
            session_start();
    }
    public static function destroy($sessionName = NULL){
        session_destroy();
        unset($_SESSION);
        if(!is_null($sessionName)){
            unset($_COOKIE[$sessionName]);
            setcookie($sessionName, '', -1);
        }
        return !isset($_SESSION);
    }
    public static function AuthUser($userAuth, $userId, $rememberMe = NULL){

        if(!is_null($rememberMe)){
            self::set('session_id', session_id());
            self::start(1);
        }else{
            self::start();
        }
        self::set('user_auth', $userAuth);
        self::set('user_id', $userId);
        $logsManager = new LogsController(TRUE); //true : ini vars.
        $logsManager->setUserId($userId)
                    ->setUserAuth($userAuth);

        if (!is_null($rememberMe)) {
            $logsManager->setSessionId(session_id());
        }
        ($logsManager->isExist()) ? $logsManager->updateByUserId() : $logsManager->addElement();
    }


    public static function AuthFrontUser($userId, $rememberMe = NULL){

        // if(!is_null($rememberMe)){
        //     setcookie('remember_me', '1', time() + (10 * 365 * 24 * 60 * 60), '/');
        //     self::multi_session_start(self::front_user_session, true);
        // }else{
        //     self::multi_session_start(self::front_user_session);
        // }
        self::start();
        self::set('customer_id', $userId);
        // session_write_close();
        $logsManager = new LogsController(TRUE); //true : ini vars.
        $logsManager->setUserId($userId)
                    ->setUserAuth('4');

        if (!is_null($rememberMe)) {
            $logsManager->setSessionId(session_id());
        }
        ($logsManager->isExist()) ? $logsManager->updateByUserId() : $logsManager->addElement();
    }
    //--------------------------------------------------------------------------
    // End Session for user.
    //--------------------------------------------------------------------------
    public static function LogoutUser($userId, $userAuth, $unsetCookie = NULL ){

        if(!is_null($unsetCookie)){
            Cookie::delete(session_name());
        }
        session_unset();	//unset($_SESSION['myvar']);
        session_regenerate_id(true);
        session_destroy();
        $logsManager = (new LogsController)
                    ->setUserId($userId)
                    ->setUserAuth($userAuth)
                    ->setOnlineStatus("OFF")
                    ->updateByUserId();

        return ($logsManager) ? true : false;
    }

    public static function LogoutFrontUser($userId){

        // if(isset($_COOKIE['remember_me'])){
        //     Cookie::delete('remember_me');
        //     Cookie::delete(self::front_user_session);
        // }

        unset($_SESSION['customer_id']);

        $logsManager = (new LogsController)
                    ->setUserId($userId)
                    ->setUserAuth('4')
                    ->setOnlineStatus("OFF")
                    ->updateByUserId();

        return ($logsManager) ? true : false;
    }


    //--------------------------------------------------------------------------
    // Start normal Session for user.
    //--------------------------------------------------------------------------
    public static function LoginUser($userAuth, $userId){
        session_start();
        $_SESSION['user_auth'] = $userAuth;
        $_SESSION['user_id'] = $userId;
        $logsManager = new LogsController(TRUE);
        $logsManager->setUserId($userId)
                    ->setUserAuth($userAuth);
        ($logsManager->isExist()) ? $logsManager->updateByUserId() : $logsManager->addElement();
    }
    //--------------------------------------------------------------------------
    public static function LoginFrontUser($userId){
        session_start();
        $_SESSION['customer_id'] = $userId;
        $logsManager = new LogsController(TRUE);
        $logsManager->setUserId($userId)
                    ->setUserAuth('4');
        ($logsManager->isExist()) ? $logsManager->updateByUserId() : $logsManager->addElement();
    }
    //--------------------------------------------------------------------------
    // retrieve data from SESSION.
    //--------------------------------------------------------------------------
    public static function get($key){
        if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
        }
        return false;
    }
    //--------------------------------------------------------------------------
    // retrieve data from SESSION Cookie.
    //--------------------------------------------------------------------------
    public static function getSessionCookieId($cookieName){
        return isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : '';
    }
    //--------------------------------------------------------------------------
    // initialize data to SESSION.
    //--------------------------------------------------------------------------
    public static function set($key, $value){
		$_SESSION[$key] = $value;
    }

    public static function remove($key){
		unset($_SESSION[$key]);
    }
}
?>