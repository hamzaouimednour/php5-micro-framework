<?php 

defined('PATH_ROOT') or die('Attempt Unauthorized, Access Denied.');

//--------------------------------------------------------------------------
// Start Session.
//--------------------------------------------------------------------------
require_once PATH_MODULES     . "Session.module.php";

//--------------------------------------------------------------------------
// Require Controllers, Modules, Helpers.
//--------------------------------------------------------------------------

require_once PATH_CONTROLLERS . 'Restaurant.class.php';

require_once PATH_HELPERS . "URLifyHelper.php";

require_once PATH_CONTROLLERS . 'Specialties.class.php';

require_once PATH_CONTROLLERS . 'RestaurantSpecialties.class.php';

require_once PATH_CONTROLLERS . 'Specialties.class.php';

require_once PATH_CONTROLLERS . 'RestaurantWork.class.php';

require_once PATH_CONTROLLERS . 'RestaurantExtras.class.php';

require_once PATH_CONTROLLERS . 'Address.class.php';

require_once PATH_CONTROLLERS . 'City.class.php';

require_once PATH_CONTROLLERS . 'Customer.class.php';

require_once PATH_CONTROLLERS . 'MenuCategories.class.php';

require_once PATH_CONTROLLERS . 'Dish.class.php';

require_once PATH_CONTROLLERS . 'DishExtras.class.php';

require_once PATH_CONTROLLERS . 'DishesPriceBySize.class.php';

require_once PATH_CONTROLLERS . 'CustomerDishBookmark.class.php';

require_once PATH_CONTROLLERS . 'Options.class.php';

require_once PATH_CONTROLLERS . 'Discount.class.php';

require_once PATH_MODELS      . "Error.model.php";

require_once PATH_MODELS      . "Cart.model.php";

if($this->getParamsIndexOf() == 'Specialities'){
  
  header("Content-type: application/json");

  $avaibleSpecialities = (new RestaurantSpecialties)->getAllBySpecialitiesId();
  $specialitiesIDs = [];
  foreach ($avaibleSpecialities as $obj) {
    $specialitiesIDs[] = $obj->getSpecialtyId();
  }
  $specialitiesIDs = implode(', ', $specialitiesIDs);
  $specialities = (new Specialties)->setId('(' . Handler::getNumberMap($specialitiesIDs) . ')')->getElementsById();
  $data = array( [ 'id' => 'all', 'value' => 'TOUTES LES CUISINES'] );
  foreach ($specialities as $obj) {
    $data[] = ['id' => Handler::encryptInt($obj->getId()), 'value' => ucwords($obj->getSpecialty()) ];
  }
  echo json_encode( $data );
  exit;
}



if(!empty($_POST)){

  error_reporting(E_ALL ^ E_NOTICE);
  if($this->getAction() == 'List'){

    $city_id = Handler::decryptInt(Handler::getNumber(Request::post('city')));
    $city = (new city)->setId($city_id)->getElementById();
    if($city){
      Session::set('customer_city_id', $city_id);
    }

    if(Request::post('cuisine') == all){
      echo json_encode( 
        array('status' => 'success', 'url' => HTML_PATH_ROOT . 'restaurants/' . mb_strtolower(URLify::filter($city->getCityName()). '/delivery')
      ));
      exit; 
    }else{
      $spec = (new Specialties)
      ->setId(Handler::decryptInt(Handler::getNumber(Request::post('cuisine'))))
      ->getElementById();
      echo json_encode(
        array('status' => 'success', 'url' => HTML_PATH_ROOT . 'restaurants/' . mb_strtolower(URLify::filter($city->getCityName()). '/' . mb_strtolower(URLify::filter($spec->getSpecialty()) . '-' . Handler::encryptInt($spec->getId())))
      ));
      exit;  
    }
    echo json_encode( array('status' => 'failed') );
    exit; 
    
  }
}
if(empty(Session::get('customer_city_id'))){
  Request::redirect(HTML_PATH_ROOT);
}
$this->getScriptArray(
  [
      '--font-style',
      'icofont',
      'search',
      'font-awesome',
      'sweetalert',
      '--cart-script',
      '--component-script'
  ]
);
$this->requireTPL('p-header', PATH_PUBLIC);
$this->requireTPL('p-restaurants', PATH_PUBLIC);
$this->requireTPL('p-footer', PATH_PUBLIC);
$this->requireTPL('p-base-js', PATH_PUBLIC);
?>