<?php
require_once PATH_CONTROLLERS . 'Delivery.class.php';

require_once PATH_CONTROLLERS . 'VehicleType.class.php';

require_once PATH_CONTROLLERS . 'City.class.php';

require_once PATH_CONTROLLERS . 'Address.class.php';

require_once PATH_MODELS      . "Error.model.php";
if(!empty($_POST)){
    if($this->getAction() === 'Add'){
        global $dateTime;
        $delivery = (new Delivery)
                    ->resetAI()
                    ->setFullName(Request::post('user-full_name'))
                    ->setPhone(Request::post('user-phone'))
                    ->setBirthDate(Request::post('user-birth_date'))
                    ->setEmail(Request::post('user-email'))
                    ->setUserStatus('0')
                    ->setVehicleId(Request::post('user-vehicle_id'))
                    ->setWorkingTime(Request::post('user-working_time'))
                    ->setAvailability('1')
                    ->setRegisterDate($dateTime->format('Y-m-d H:i:s'))
                    ->setPartnerRequest('P')
                    ->addUser(true);
        if(!$delivery){
            echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur d\'enregistrer votre demande, Réessayer svp autre fois!', true)));
            exit;
        }
        $address = (new Address)
                    ->resetAI()
                    ->setUserAuth('3')
                    ->setUserId($delivery)
                    ->setAddress(Request::post('user-address'))
                    ->setLatitude(Request::post('latitudeR'))
                    ->setLongtitude(Request::post('longitudeR'))
                    ->setCityId(Request::post('user-city'))
                    ->addElement();
        if(!$address){
            echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur d\'ajouter l\'Adresse du restaurant, Réessayer autre fois!', true)));
            exit;
        }

        echo json_encode(array('status' => 'success', 'info' => AlertError::defaultNote('success', 'ion-md-checkmark-circle-outline', 'Succés!', 'Nous avons reçu votre demande, nous vous contacterons dans les plus brefs délais, Merci pour votre intérêt.', true)));
        exit;
    }
}
$this->requireTPL('user-3-add');
?>