<?php

defined('PATH_ROOT') or die('Attempt Unauthorized, Access Denied.');


//--------------------------------------------------------------------------
// Start Session.
//--------------------------------------------------------------------------

Session::start();

//--------------------------------------------------------------------------
// Check for user authority.
//--------------------------------------------------------------------------
global $session;
$session['user_id']    = Session::get( 'user_id' );
$session['user_auth']  = Session::get( 'user_auth' );

if(Handler::check_array($session))
{
    // Request::redirect( HTML_PATH_ROOT . '404' );
    die( "Unauthorized Access! Invalid User Session.");
}

switch ($session['user_auth']) {
    case 1:
        require_once PATH_CONTROLLERS . 'Administrator.class.php';
        break;
    case 2:
        require_once PATH_CONTROLLERS . 'Restaurant.class.php';
        break;
    case 3:
        require_once PATH_CONTROLLERS . 'Delivery.class.php';
        break;
    case 4:
        require_once PATH_CONTROLLERS . 'Customer.class.php';
        break;
    default:
        die( "Unauthorized User! Invalid User Session.");
}
?>