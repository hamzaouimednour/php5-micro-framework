<?php

defined('PATH_ROOT') or die('Attempt Unauthorized, Access Denied.');

// header('Content-Type: application/json');

require_once PATH_CONTROLLERS . 'City.class.php';

if(!empty($_POST)){

    require_once PATH_CONTROLLERS   . 'Customer.class.php';

    require_once PATH_CONTROLLERS   . 'CustomerVerification.class.php';

    require_once PATH_HELPERS       . 'CryptHelper.php';

    require_once PATH_MODELS        . "Error.model.php";
    
    require_once PATH_HELPERS       . 'MailHelper.php';

    if(Handler::check_array($_POST)){
        echo json_encode(
            array(
                'status' => 'failed',
                'info' => AlertError::failedFront('Tous les champs sont obligatoire!')
                )
        );
        exit;
    }

    // check if email already registred.
    $checkUserCredentiels = (new Customer)
                            ->setEmail(Handler::is_email(Request::post('email'))) //
                            ->checkEmail();
                            
    if($checkUserCredentiels){
        echo json_encode(
            array(
                'status' => 'error',
                'info' => AlertError::failedFront('Utilisateur avec cette adresse Email déjà existe.')
                )
        );
        exit;
    }
    
    // check if phone already registred.
    $checkUserCredentiels = (new Customer)
                            ->setPhone(Handler::getNumber(Request::post('phone'))) //
                            ->checkPhone();
                        
    if($checkUserCredentiels){

        echo json_encode(
            array(
                'status' => 'failed',
                'info' => AlertError::failed('Utilisateur avec ce Numéro de téléphone déjà existe.')
                )
        );
        exit;
    }
    
    $customer = (new Customer)
                ->resetAI() // Reset The AutoIncrement
                ->setFullName(Handler::getString(Request::post('name'))) //
                ->setEmail(Handler::is_email(Request::post('email'))) //
                ->setPhone(Handler::getNumber(Request::post('phone'))) //
                ->setPasswd(CryptHelper::crypt(Request::post('pass'))) //
                ->setRegisterDate(Handler::getDateTime())
                ->setUserStatus('0') //Until verify his Email
                ->addUser(true);
    
    if($customer){
        $authKey = Handler::generateAuthKey($customer);
        (new CustomerVerification)
        ->resetAI()
        ->setCustomerId($customer)
        ->setVerificationId($authKey)
        ->SetVerificationStatus('0')
        ->addElement();

        $user_data = json_encode(array(Handler::is_email(Request::post('email')) , Handler::encryptInt($customer)));

        // Send verification mail. //
        $template = file_get_contents(PATH_TEMPLATES . 'email-verification.html');
        $template = str_replace('{TO_NAME}', ucwords(Handler::getString(Request::post('name'))), $template);
        $template = str_replace('{VERIFICATION_URL}', DOMAIN . HTML_PATH_ROOT . 'user/verify/' . Encryption::encode($user_data) . '/' . $authKey, $template);
        
        $mail = (new Mailer)
        ->setFrom('noreply@foody.tn')
        ->setFromName('Foody')
        ->setTo(Request::post('email'))
        ->setToName(ucwords(Handler::getString(Request::post('name'))))
        ->setSubject('Vérifiez votre e-mail sur foody!')
        ->setBody($template)
        ->sendMail();
        if(!$mail){
            echo json_encode(
                array(
                    'status' => 'failed',
                    'info' => AlertError::failed('Erreur d\'envoyer un email pour cette adresse e-mail.')
                    )
            );
            exit;
        }
        echo json_encode(
            array('status' => 'success', 'info' => AlertError::successFront('Vous avez enregistré avec succès, svp vérifier votre email!'))
        );
    }else {
        echo json_encode(
            array('status' => 'failed', 'info' => AlertError::failedFront('Erreur survenue, svp essayez à nouveau!'))
        );
    }
    exit;
}
?>