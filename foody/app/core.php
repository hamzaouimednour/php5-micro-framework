<?php
/**
 * Core configuration
 *
 * @author Hamzaoui MedNour - hamzaouimohamednour@gmail.com
 * @version 1.0
 */

/*
|--------------------------------------------------------------------------
| Application Debug Mode
|--------------------------------------------------------------------------
|
| When your application is in debug mode, detailed error messages with
| stack traces will be shown on every error that occurs within your
| application. If disabled, a simple generic error page is shown.
|
*/
// define('SAFE_MODE', false); // Test Mode.
define('SAFE_MODE', true);
if (SAFE_MODE) {
	error_reporting(~E_ALL);
	ini_set('error_log', 0);
	ini_set('log_errors', 0);
	ini_set("html_errors", 0);
	ini_set("display_errors", 0);
	ini_set('allow_url_fopen',0);
	ini_set('display_startup_errors',0);
} else {
	error_reporting(E_ALL);
}

//--------------------------------------------------------------------------
// MySQL Database Configuration.
//--------------------------------------------------------------------------

define('DBMS', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'smarthom_foody');
define('DB_USER', 'smarthom_foody');
define('DB_PASS', 'c21hcnRob21fZm9vZHlfZGI');
define('DB_PREFIX', 'fd_');

//--------------------------------------------------------------------------
// Internal Character Encoding.
//--------------------------------------------------------------------------

ini_set('default_charset', 'UTF-8' );

define('CHARSET', 'UTF-8');
mb_internal_encoding(CHARSET);
mb_regex_encoding(CHARSET);

//--------------------------------------------------------------------------
// Set Core Path Routing.
//--------------------------------------------------------------------------

// avoid to use this : define('PATH_ROOT', $_SERVER['DOCUMENT_ROOT']);

define('PATH_CONFIG', 		PATH_APP	. 'Config'		 	. DS);
define('PATH_CONTROLLERS', 	PATH_APP 	. 'Controllers' 	. DS);
define('PATH_MODULES', 		PATH_APP	. 'Modules'	 		. DS);
define('PATH_HELPERS', 		PATH_APP	. 'Helpers'	 		. DS);
define('PATH_MODELS', 		PATH_APP	. 'Models'		 	. DS);
define('PATH_VIEWS', 		PATH_APP	. 'Views'			. DS);
define('PATH_TEMPLATES', 	PATH_APP	. 'Templates'		. DS);

define('PATH_BACKEND', 		PATH_MODULES . 'Backend' 		. DS);
define('PATH_FRONTEND', 	PATH_MODULES . 'Index' 			. DS);
define('PATH_PUBLIC', 		PATH_ROOT	 . 'public' 		. DS);
// ---- change this after upload website
define('PATH_BACKEND_TPL', 	PATH_PUBLIC	 . 'backend/html' 	. DS);

//--------------------------------------------------------------------------
// Require Database Connection script.
//--------------------------------------------------------------------------

require_once PATH_CONFIG	. 'Config.inc.php';

//--------------------------------------------------------------------------
// Require Handler script.
//--------------------------------------------------------------------------

require_once PATH_HELPERS	. 'HandlerHelper.php';

//--------------------------------------------------------------------------
// Handle the routing Request.
//--------------------------------------------------------------------------

require_once PATH_CONFIG	. 'Request.php';

//--------------------------------------------------------------------------
// Manager Database.
//--------------------------------------------------------------------------

require_once PATH_CONTROLLERS	. 'ManagerDB.class.php';

//--------------------------------------------------------------------------
// Manager Database.
//--------------------------------------------------------------------------

require_once  PATH_HELPERS       . "SessionHelper.php";

//--------------------------------------------------------------------------
// Define Media Directory CDN (Subdomain).
//--------------------------------------------------------------------------

define('PATH_HOME', 		dirname(PATH_ROOT) . DS);
define('DOMAIN', 			Request::getDomainName());
//change it while hosting
// define('CDN', 				'cdn.foody.tn' 	. DS . 'static' . DS);
define('CDN', 				dirname(Request::getBaseURL()) . DS . 'cdn.foody.tn' 	. DS . 'static' . DS);
define('PATH_CDN', 			'//'	. CDN);
// define('PATH_CDN_ROOT', 	PATH_HOME		. CDN); // work on hosting dont forget to change it
// define('PATH_CDN_ROOT', 	PATH_ROOT		. CDN);
define('PATH_CDN_ROOT', 	dirname(PATH_ROOT)	. DS . 'cdn.foody.tn' 	. DS . 'static' . DS);
define('PATH_CDN_THUMBS', 	PATH_CDN		. 'thumbs' . DS);

//--------------------------------------------------------------------------
// Set Back-End Path Routing.
//--------------------------------------------------------------------------


define('HTML_PATH_ROOT', 			Request::getBaseURL());
define('HTML_PATH_PUBLIC', 			HTML_PATH_ROOT		. 'public/');
define('HTML_PATH_TPL_BACKEND', 	HTML_PATH_PUBLIC	. 'backend/');
define('HTML_PATH_BACKEND', 		HTML_PATH_ROOT		. 'backend/');
define('HTML_PATH_VENDOR', 			HTML_PATH_PUBLIC	. 'vendor/');
define('HTML_PATH_CSS',				HTML_PATH_PUBLIC	. 'css/');
define('HTML_PATH_JS',				HTML_PATH_PUBLIC	. 'js/');
define('HTML_PATH_IMG',				HTML_PATH_PUBLIC	. 'img/');

//--------------------------------------------------------------------------
// Database Information Schema.
//--------------------------------------------------------------------------

$dbSchema = array();
$dbSchema['options']					= DB_PREFIX . 'options';
$dbSchema['administrator']				= DB_PREFIX . 'administrator';
$dbSchema['usersLogs']					= DB_PREFIX . 'users_logs';
$dbSchema['meta']						= DB_PREFIX . 'meta';
$dbSchema['address']					= DB_PREFIX . 'address';
$dbSchema['city'] 						= DB_PREFIX . 'city';
$dbSchema['city_zone'] 					= DB_PREFIX . 'city_zone';
$dbSchema['customer']					= DB_PREFIX . 'customer';
$dbSchema['customer_discount']			= DB_PREFIX . 'customer_discount';
$dbSchema['customer_address'] 			= DB_PREFIX . 'customer_address';
$dbSchema['customer_dishes_bookmark']	= DB_PREFIX . 'customer_dishes_bookmark';
$dbSchema['customer_feedback'] 			= DB_PREFIX . 'customer_feedback';
$dbSchema['customer_orders_nbr']		= DB_PREFIX . 'customer_orders_nbr';
$dbSchema['customer_promotion']			= DB_PREFIX . 'customer_promotion';
$dbSchema['customer_verification']		= DB_PREFIX . 'customer_verification';
$dbSchema['delivery']					= DB_PREFIX . 'delivery';
$dbSchema['discount_code']				= DB_PREFIX . 'discount_code';
$dbSchema['dishes']						= DB_PREFIX . 'dishes';
$dbSchema['dishes_extras']				= DB_PREFIX . 'dishes_extras';
$dbSchema['dishes_menu']               	= DB_PREFIX . 'dishes_menu';
$dbSchema['dishes_price_size']			= DB_PREFIX . 'dishes_price_size';
$dbSchema['modules']                	= DB_PREFIX . 'modules';
$dbSchema['options']                	= DB_PREFIX . 'options';
$dbSchema['orders']                		= DB_PREFIX . 'orders';
$dbSchema['order_dishes']               = DB_PREFIX . 'order_dishes';
$dbSchema['promotion_quantity'] 		= DB_PREFIX . 'promotion_quantity';
$dbSchema['restaurant'] 				= DB_PREFIX . 'restaurant';
$dbSchema['restaurant_extras']			= DB_PREFIX . 'restaurant_extras';
$dbSchema['restaurant_feedback']		= DB_PREFIX . 'restaurant_feedback';
$dbSchema['restaurant_specialties']		= DB_PREFIX . 'restaurant_specialties';
$dbSchema['restaurant_work']			= DB_PREFIX . 'restaurant_work';
$dbSchema['specialties']				= DB_PREFIX . 'specialties';
$dbSchema['special_customers_discount']	= DB_PREFIX . 'special_customers_discount';
$dbSchema['users_authority']			= DB_PREFIX . 'users_authority';
$dbSchema['users_logs']					= DB_PREFIX . 'users_logs';
$dbSchema['users_requests']				= DB_PREFIX . 'users_requests';
$dbSchema['vehicle']					= DB_PREFIX . 'vehicle';


//--------------------------------------------------------------------------
// Initialize Objects.
//--------------------------------------------------------------------------

// $administrator 	= new Administrator();
// $crypter 		= new CryptHelper();
// $encoder 		= new FeistelCipherHelper();

date_default_timezone_set('Africa/Tunis');
$dateTime = new DateTime(NULL, new DateTimeZone('Africa/Tunis'));

$sizeImageBackend = array( 'width' => '370', 'height' => '240');
$sizeImageFront = array( 'width' =>"508", 'height' =>"320");
$sizeImageCover = array( 'width' =>"508", 'height' =>"320");
$sizeCartImageFront = array( 'width' =>"917", 'height' =>"1000");
$GLOBALS['fonts'] = ['Raykuns', 'Nettizen', 'Fineberg', 'Justlyne', 'Handscript', 'Malina', 'Sunder out', 'Hamburg', 'Darkline', 'Blackball','Birdrockers', 'Kadisoka', 'Restaurants Script']
?>
