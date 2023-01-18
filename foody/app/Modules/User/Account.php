<?php
defined('PATH_ROOT') or die('Attempt Unauthorized, Access Denied.');

//--------------------------------------------------------------------------
// Start Session.
//--------------------------------------------------------------------------
require_once PATH_MODULES     . "Session.module.php";

if(empty(Session::get('customer_id'))){

    Request::redirect( HTML_PATH_ROOT . '404' );

}

if(!empty($_POST)){

    require_once PATH_CONTROLLERS   . 'Customer.class.php';

    require_once PATH_CONTROLLERS   . 'CustomerVerification.class.php';

    require_once PATH_MODELS        . "Error.model.php";

    require_once PATH_HELPERS       . 'CryptHelper.php';

    if($this->getAction() == 'Edit'){
        $customer = (new Customer)->setUid(Session::get('customer_id'))->getUserByUid();
        if(CryptHelper::crypt(Request::post('oldpwd')) == $customer->getPasswd()){
            $updateCustomer = (new Customer)->setUid(Session::get('customer_id'))
            ->setPasswd(CryptHelper::crypt(Request::post('newpwd')))
            ->updateUserByUid();
            if($updateCustomer){
                echo json_encode(array('status' => 'success', 'info' => AlertError::success('Votre profil modifié avec succés !')));
                exit;
            }else{
                echo json_encode(array('status' => 'failed', 'info' => AlertError::failed('Opération a échoué, veuillez réessayer !')));
                exit;
            }
        }else{
            echo json_encode(array('status' => 'failed', 'info' => AlertError::failed('Mot de passe actuel incorrect')));
            exit;
        }
    }
    if($this->getAction() == 'Search'){
        $customer = (new Customer)->setUid(Session::get('customer_id'))->getUserByUid();
        if(CryptHelper::crypt(Request::post('oldpwd')) == $customer->getPasswd()){
            echo json_encode(array('status' => 'success', 'info' => ''));
            exit;
        }else{
            echo json_encode(array('status' => 'failed', 'info' => ''));
            exit;
        }

    }
}
?>