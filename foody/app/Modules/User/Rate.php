<?php

defined('PATH_ROOT') or die('Attempt Unauthorized, Access Denied.');


//--------------------------------------------------------------------------
// Start Session.
//--------------------------------------------------------------------------
require_once PATH_MODULES     . "Session.module.php";

if(empty(Session::get('customer_id'))){

    Request::redirect( HTML_PATH_ROOT . '404' );

}

//--------------------------------------------------------------------------
// Require Controllers, Modules, Helpers.
//--------------------------------------------------------------------------

require_once PATH_CONTROLLERS . 'Restaurant.class.php';

require_once PATH_HELPERS . "URLifyHelper.php";

require_once PATH_CONTROLLERS . 'Specialties.class.php';

require_once PATH_CONTROLLERS . 'RestaurantSpecialties.class.php';

require_once PATH_CONTROLLERS . 'RestaurantWork.class.php';

require_once PATH_CONTROLLERS . 'RestaurantExtras.class.php';

require_once PATH_CONTROLLERS . 'RestaurantFeedback.class.php';

require_once PATH_CONTROLLERS . 'Customer.class.php';

require_once PATH_CONTROLLERS . 'Address.class.php';

require_once PATH_CONTROLLERS . 'City.class.php';

require_once PATH_CONTROLLERS . 'MenuCategories.class.php';

require_once PATH_CONTROLLERS . 'Dish.class.php';

require_once PATH_CONTROLLERS . 'DishExtras.class.php';

require_once PATH_CONTROLLERS . 'DishesPriceBySize.class.php';

require_once PATH_CONTROLLERS . 'CustomerDishBookmark.class.php';

require_once PATH_CONTROLLERS . 'CustomerAddress.class.php';

require_once PATH_CONTROLLERS . 'Options.class.php';

require_once PATH_CONTROLLERS . 'Discount.class.php';

require_once PATH_MODELS      . "Error.model.php";

require_once PATH_MODELS      . "Cart.model.php";

if(!empty($_POST)){

    if($this->getAction() == 'Add'){
        global $dateTime;

        $feedbackCheck = (new RestaurantFeedback)->setCustomerId(Session::get('customer_id'))
        ->checkCustomerNote();
        if($feedbackCheck){
            $feedback = (new RestaurantFeedback)
            ->setRestaurantId(Handler::decryptInt(Handler::getNumber(Request::post('resto-id'))))
            ->setCustomerId(Session::get('customer_id'))
            ->setRating(Request::post('user-rates'))
            ->setComment(Request::post('user-comment'))
            ->setDateTime($dateTime->format('Y-m-d H:i:s'))
            ->updateElementByCustomerId();
            if($feedback){
                echo json_encode(array('status' => 'success', 'info' => AlertError::success('Votre avis modifié avec succés!') ));
                exit;
            }
            echo json_encode(array('status' => 'failed', 'info' => AlertError::failed('Erreur s\'est produite lors de modification d\'avis!') ));
            exit;
        }else{
            $feedback = (new RestaurantFeedback)->resetAI()
            ->setRestaurantId(Handler::decryptInt(Handler::getNumber(Request::post('resto-id'))))
            ->setCustomerId(Session::get('customer_id'))
            ->setRating(Request::post('user-rates'))
            ->setComment(Request::post('user-comment'))
            ->setStatus('1')
            ->setDateTime($dateTime->format('Y-m-d H:i:s'))
            ->addElement();
            if($feedback){
                echo json_encode(array('status' => 'success', 'info' => AlertError::success('Votre avis ajouté avec succés!') ));
                exit;
            }
            echo json_encode(array('status' => 'failed', 'info' => AlertError::failed('Erreur s\'est produite lors de l\'ajout d\'avis!') ));
            exit;
        }

    }
}
?>