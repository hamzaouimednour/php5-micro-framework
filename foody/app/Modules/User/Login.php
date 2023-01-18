<?php 
defined('PATH_ROOT') or die('Attempt Unauthorized, Access Denied.');

if(!empty($_POST)){

    require_once PATH_CONTROLLERS   . 'Customer.class.php';

    require_once PATH_CONTROLLERS   . 'CustomerVerification.class.php';

    require_once PATH_MODELS        . "Error.model.php";
    
    require_once PATH_HELPERS       . 'CryptHelper.php';

    if(Handler::is_email(Request::post('username'))) { //
        $customer = ( new Customer)
                 ->setEmail(Handler::is_email(Request::post('username'))) 
                 ->setPasswd(CryptHelper::crypt(Request::post('password'))) //
                 ->checkUserByEmailWOS();
        
        if($customer){
            $customerVerification = (new CustomerVerification)
                                    ->setCustomerId($customer->getUid())
                                    ->getElementByUserId();
            if($customer->getUserStatus() == 0 && $customerVerification->getVerificationStatus() == 0){
                echo json_encode(
                    array('status' => 'warning', 'info' => AlertError::warningFront('veuillez d\'abord vérifier votre email!'))
                );
            }elseif($customer->getUserStatus() == 1 && $customerVerification->getVerificationStatus() == 1){
                if(Request::post('remember_me')){
            
                    Session::AuthFrontUser(
                        $customer->getUid(),
                        true
                    );
                }else{
                    Session::AuthFrontUser(
                        $customer->getUid()
                    );
                }
                echo json_encode(
                    array('status' => 'success', 'info' => AlertError::successFront('Bienvenue, s\'il vous plaît attendez ...'))
                );
            }else{
                echo json_encode(
                    array('status' => 'failed', 'info' => AlertError::failedFront('Les informations d\'identification sont incorrectes!'))
                );
            }
        }else{
            echo json_encode(
                array('status' => 'failed', 'info' => AlertError::failedFront('Les informations d\'identification sont incorrectes!'))
            );
        }
    }else {
        $customer = ( new Customer)
                    ->setPhone(Handler::getNumber(Request::post('username'))) 
                    ->setPasswd(CryptHelper::crypt(Request::post('password'))) //
                    ->checkUserByPhoneWOS();
        if($customer){
            $customerVerification = (new CustomerVerification)
                                    ->setCustomerId($customer->getUid())
                                    ->getElementByUserId();
            if($customer->getUserStatus() == 0 && $customerVerification->getVerificationStatus() == 0){
                echo json_encode(
                    array('status' => 'warning', 'info' => AlertError::warningFront('veuillez d\'abord vérifier votre email!'))
                );
            }elseif($customer->getUserStatus() == 1 && $customerVerification->getVerificationStatus() == 1){
                if(Request::post('remember_me')){
            
                    Session::AuthFrontUser(
                        $customer->getUid(),
                        true
                    );
                }else{
                    Session::AuthFrontUser(
                        $customer->getUid()
                    );
                }
                echo json_encode(
                    array('status' => 'success', 'info' => AlertError::successFront('Bienvenue, s\'il vous plaît attendez ...'))
                );
            }else{
                echo json_encode(
                    array('status' => 'failed', 'info' => AlertError::failedFront('Les informations d\'identification sont incorrectes!'))
                );
            }
        }else{
            echo json_encode(
                array('status' => 'failed', 'info' => AlertError::failedFront('Les informations d\'identification sont incorrectes!'))
            );
        }
    }
    exit;
}
?>