<?php

defined('PATH_ROOT') or die('Attempt Unauthorized, Access Denied.');

if(empty($this->getParams()) || count($this->getParams()) != 2 || empty($this->getParamsIndexOf(0)) || empty($this->getParamsIndexOf(1)) ){
    Request::redirect( HTML_PATH_ROOT . '404' );
}

require_once PATH_CONTROLLERS . 'City.class.php';

require_once PATH_CONTROLLERS   . 'Customer.class.php';

require_once PATH_CONTROLLERS   . 'CustomerVerification.class.php';

require_once PATH_HELPERS       . 'CryptHelper.php';

require_once PATH_MODELS        . "Error.model.php";

require_once PATH_HELPERS       . 'PHPMailer-5.2-stable/MailHelper.php';

$user_data = json_decode(Encryption::decode($this->getParamsIndexOf(0)), true);

if (json_last_error() === JSON_ERROR_NONE) {

    $customer = (new Customer)
    ->setUid(Handler::decryptInt($user_data[1]))
    ->setUserStatus('0')
    ->getUserByUid();

    $customer_verif = (new CustomerVerification)
    ->setCustomerId(Handler::decryptInt($user_data[1]))
    ->setVerificationId($this->getParamsIndexOf(1))
    ->SetVerificationStatus('0')
    ->getElementByIDUser();
    
    if($customer_verif && $customer){
        
        $update_customer = (new Customer)
        ->setUid(Handler::decryptInt($user_data[1]))
        ->setUserStatus('1')
        ->updateUserByUid();

        $update_customer_verif = (new CustomerVerification)
        ->setCustomerId(Handler::decryptInt($user_data[1]))
        ->SetVerificationStatus('1')
        ->updateByUserId();

        if($update_customer_verif && $update_customer){
            $this->requireTPL('email-verified', PATH_TEMPLATES);
            exit;
        }else{
            Request::redirect( HTML_PATH_ROOT . '404' );
        }

    }else{
        Request::redirect( HTML_PATH_ROOT . '404' );
    }

} else {
    Request::redirect( HTML_PATH_ROOT . '404' );
}

?>