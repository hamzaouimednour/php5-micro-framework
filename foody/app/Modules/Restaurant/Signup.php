<?php
require_once PATH_CONTROLLERS . 'Restaurant.class.php';

require_once PATH_CONTROLLERS . 'Specialties.class.php';

require_once PATH_CONTROLLERS . 'RestaurantSpecialties.class.php';

require_once PATH_CONTROLLERS . 'City.class.php';

require_once PATH_CONTROLLERS . 'Address.class.php';

require_once PATH_CONTROLLERS . 'Options.class.php';

require_once PATH_CONTROLLERS . 'RestaurantWork.class.php';

require_once PATH_MODELS      . "Error.model.php";

if(!empty($_POST)){
    if($this->getAction() ==='Add'){
        global $dateTime;
        $userUid = (new Restaurant)
                ->resetAI()
                ->setFullName(Request::post('restaurant-full_name'))
                ->setEmail(Request::post('restaurant-email'))
                ->setPhone(Request::post('restaurant-phone'))
                ->setUserStatus('0')
                ->setPartnerRequest('P')
                ->setRestaurantName(Request::post('restaurant-restaurant_name'))
                ->setLogo('unkown.jpg')
                ->setRegisterDate($dateTime->format('Y-m-d H:i:s'))
                ->addUser(true);
        
        if(!$userUid){
            echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur d\'enregistrer votre demande, Réessayer svp autre fois!', true)));
            exit;
        }
        $work = (new RestaurantWork)
                ->resetAI()
                ->setRestaurantId($userUid)
                ->setDeliveryType(Request::post('restaurant-delivery_type'))
                ->addElement();
        if(!$work){
            echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier Work Type du restaurant, Réessayer autre fois!', true)));
            exit;
        }
        

        //==========================================
        // Restaurant Specialties Section
        //==========================================

        $specialties = (new RestaurantSpecialties)
                            ->resetAI()
                            ->setRestaurantId($userUid)
                            ->setSpecialtyId(Request::post('restaurant-specialties'))
                            ->addElements();
        if(!$specialties){
            echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de\'ajouter La/Les spécialités du restaurant, Réessayer autre fois!', true)));
            exit;
        }

        //==========================================
        // Restaurant Address Section
         //==========================================
        $address = (new Address)
                    ->resetAI()
                    ->setUserAuth('2')
                    ->setUserId($userUid)
                    ->setAddress(Request::post('restaurant-address'))
                    ->setLatitude(Request::post('latitudeR'))
                    ->setLongtitude(Request::post('longitudeR'))
                    ->setCityId(Request::post('restaurant-city'))
                    ->addElement();
        if(!$address){
            echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur d\'ajouter l\'Adresse du restaurant, Réessayer autre fois!', true)));
            exit;
        }

        echo json_encode(array('status' => 'success', 'info' => AlertError::defaultNote('success', 'ion-md-checkmark-circle-outline', 'Succés!', 'Nous avons reçu votre demande, nous vous contacterons dans les plus brefs délais, Merci pour votre intérêt.', true)));
        exit;
    }
}

$this->requireTPL('user-2-add');
?>