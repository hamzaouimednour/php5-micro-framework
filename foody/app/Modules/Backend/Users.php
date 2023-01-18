<?php
require_once PATH_MODULES . "Authority.module.php";

if(!in_array(Session::get('user_auth'), [1])){
	Request::redirect(HTML_PATH_BACKEND . 'dashboard');
}

require_once PATH_CONTROLLERS . 'Order.class.php';

require_once PATH_CONTROLLERS . 'Administrator.class.php';

require_once PATH_CONTROLLERS . 'Customer.class.php';

require_once PATH_CONTROLLERS . 'Restaurant.class.php';

require_once PATH_CONTROLLERS . 'Delivery.class.php';

require_once PATH_CONTROLLERS . 'Specialties.class.php';

require_once PATH_CONTROLLERS . 'RestaurantSpecialties.class.php';

require_once PATH_CONTROLLERS . 'RestaurantWork.class.php';

require_once PATH_CONTROLLERS . 'VehicleType.class.php';

require_once PATH_CONTROLLERS . 'Options.class.php';

require_once PATH_CONTROLLERS . 'Address.class.php';

require_once PATH_CONTROLLERS . 'City.class.php';

require_once PATH_MODELS      . "Error.model.php";

require_once PATH_HELPERS     . "UploadHelper.php";

require_once PATH_HELPERS     . "CryptHelper.php";

require_once PATH_HELPERS     . 'MailHelper.php';

if(!empty($_POST)){

    error_reporting(E_ALL ^ E_NOTICE);
    
    $user = null;
    switch (Request::post('user_auth')) {
        case 1:
            $user = new Administrator;
            break;
        case 2:
            $user = new Restaurant;
            break;
        case 3:
            $user = new Delivery;
            break;
        case 4:
            $user = new Customer;
    }

	if($this->getParamsIndexOf(1) === 'Status'){
	
		$getNewStatus = (Request::post('status') == '1') ? '0' : '1'; //1:ON, 0:OFF
		$user->setUid(Handler::getNumber(Request::post('id')))
			 ->setUserStatus($getNewStatus)
			 ->updateUserByUid();
	
		if(!$user){
            echo json_encode(array('status' => 'failed', 'info' => AlertError::failed(' Erreur, réssayer plus tard! ')));
		}else{
            echo json_encode(array('status' => 'success', 'info' => ''));
		}
		exit;
    }
    if($this->getParamsIndexOf(1) === 'Delete'){
	
		$user->setUid(Handler::getNumber(Request::post('id')))
			 ->removeUserByUid();
	
		if($user){
			echo json_encode(array('status' => 'success', 'info' => 'Utilisateur supprimé avec succés'));
		}else{
			echo json_encode(array('status' => 'failed', 'info' => 'Operation echoué, Réssayer une autre fois. '));
		}
		exit;
    }
    
    if($this->getParamsIndexOf(1) ==='Edit'){
        $dataUser = $_POST;
        if(Request::post('user_auth') == 3) {
            $dataUser['availability'] = Request::post('availability');
        }
        if(Request::post('user_auth') == 4) {
            $dataUser['credibility'] = Request::post('credibility');
        }
        $dataUser['passwd'] = empty($dataUser['passwd']) ? NULL : CryptHelper::crypt($dataUser['passwd']);
        $dataUser['user_status'] = Request::post('user_status');
        unset($dataUser['user_auth']);
        foreach ($dataUser as $property => $value) {
            if(method_exists($user,'set'.Handler::getMethod($property)))
                call_user_func_array( array($user, 'set'.Handler::getMethod($property)), array($dataUser[$property]) );
        }
        $user->updateUserByUid();
        if($user){
            echo json_encode(array('status' => 'success', 'info' => AlertError::success('Utilisateur modifié avec succés')));
		}else{
			echo json_encode(array('status' => 'failed', 'info' => AlertError::failed('Operation echoué, Réssayer une autre fois. ')));
        }
        exit;
    }

    if($this->getAction() ==='Edit' && $this->checkParams('Save', 2)){

        $userAuth = Handler::getNumber($this->getParamsIndexOf(0));
        $userUid = Handler::getNumber(Handler::decryptInt($this->getParamsIndexOf(1)));
        
        if($userAuth==2){

            //================File Upload ================
            
            $logo = NULL;
            $uploadError = NULL;
            $uploadFileName = NULL;
            $fileName   = Handler::generateNameKey();
            $uploadPath = PATH_CDN_ROOT . Handler::encryptInt($userUid) . DS;
    
            if (!empty($_FILES)) {
                if (!is_dir($uploadPath)) {
                    if (!mkdir($uploadPath, 0777, true)) {
                        echo json_encode(array(
                            'status' => 'failed',
                            'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de permission: Upload Directory Not Writable, Contacter l\'administrateur!', true)
                        ));
                        exit;
                    }
                }
                $uploadHelper   = (new FileUpload)
                    ->setInputName('Logo')
                    ->setAllowedMimeType('Image')
                    ->setUploadDirectory($uploadPath)
                    ->setFileName($fileName)
                    ->upload();
                $uploadFileName = $uploadHelper->getUploadedFileName();
                if (!empty($uploadHelper->getErrors())) {
                    $uploadError    = $uploadHelper->getErrors(0);
                    echo json_encode(array(
                        'status' => 'failed',
                        'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur: '.$uploadError.'!', true)
                    ));
                    exit;
                }
            }
            
            //==========================================
            // Restaurant Work Section
            //==========================================

            $dayWorkTime = array();
            for ($i=1; $i < 8; $i++) { 
                if(in_array('day-'.$i.'-open', array_keys($_POST)) && in_array('day-'.$i.'-close', array_keys($_POST))){
                    $dayWorkTime[$i] = array(Request::post('day-'.$i.'-open'), Request::post('day-'.$i.'-close'));
                }
            }

            $work = (new RestaurantWork)->setRestaurantId($userUid);
            if(Request::post('restaurant-delivery_type')==1){
                $work = $work->setDeliveryFee(Request::post('delivery-fee'))
                ->setInitDistance(Request::post('init-distance'))
                ->setUpFee(Request::post('up-fee'))
                ->setUpDistance(Request::post('up-distance'))
                ->setDeliveryTimeMin(Request::post('restaurant-delivery_time_min'))
                ->setDeliveryTimeMax(Request::post('restaurant-delivery_time_max'));
            }
            $work = $work->setDeliveryType(Request::post('restaurant-delivery_type'))
                    ->setMinDelivery(Request::post('restaurant-min_delivery'))
                    ->setMaxDelivery(Request::post('restaurant-max_delivery'))
                    ->setPreparationTimeMin(Request::post('restaurant-preparation_time_min'))
                    ->setPreparationTimeMax(Request::post('restaurant-preparation_time_max'))
                    ->setDescription(Request::post('restaurant-description'))
                    ->setWorkTimes($dayWorkTime)
                    ->updateElementByRestaurantId();
            if(!$work){
                echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier Work Type du restaurant, Réessayer autre fois!', true)));
                exit;
            }

            //==========================================
            // Restaurant Address Section
            //==========================================
            $address = (new Address)
                        ->setUserAuth($this->getParamsIndexOf())
                        ->setUserId($userUid)
                        ->setAddress(Request::post('restaurant-address'))
                        ->setLatitude(Request::post('restaurant-latitude'))
                        ->setLongtitude(Request::post('restaurant-longitude'))
                        ->setCityId(Request::post('restaurant-city'))
                        ->updateByUserAuthId();
            if(!$address){
                echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier Adresse du restaurant, Réessayer autre fois!', true)));
                exit;
            }
            //==========================================
            // Restaurant Specialties Section
            //==========================================

            $specialties = (new RestaurantSpecialties)
                            ->setRestaurantId($userUid)
                            ->removeAllByRestaurantId();
            if($specialties){
                $specialties = (new RestaurantSpecialties)
                            ->setRestaurantId($userUid)
                            ->setSpecialtyId(Request::post('user-specialties'))
                            ->addElements();
                if(!$specialties){
                    echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier La/Les spécialités du restaurant, Réessayer autre fois!', true)));
                    exit;
                }
            }

            //==========================================
            // Restaurant User Section
            //==========================================
            $pwd = empty(Request::post('user-passwd')) ? NULL : CryptHelper::crypt(Request::post('user-passwd'));
            $user = (new Restaurant)
                 ->setUid($userUid)
                 ->setFullName(Request::post('user-full_name'))
                 ->setEmail(Request::post('user-email'))
                 ->setPhone(Request::post('user-phone'))
                 ->setUserStatus(Request::post('user-user_status'))
                 ->setPasswd($pwd)
                 ->setRestaurantName(Request::post('user-restaurant_name'))
                 ->setLogo($uploadFileName)
                 ->updateUserByUid();
            
            if($user){
                echo json_encode(array('status' => 'success', 'info' => AlertError::defaultNote('success', 'ion-md-checkmark-circle-outline', 'Succés!', 'Utilisateur Modifier avec succés.', true)));
            }else{
                echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier l\'utilisateur, Réessayer autre fois!', true)));
            }
            exit;
        }
		
    }
    /* ****************************************** */
    // Fetch Section
    /* ****************************************** */
    if($this->getParamsIndexOf(1) === 'Fetch'){
        $user = $user->setUid(Handler::getNumber(Request::post('user_uid')))
             ->getUserByUid()
             ->setPasswd(NULL);
		if($user){
			echo json_encode($user);
		}else{
			echo json_encode(array('status' => 'failed', 'info' => 'Operation echoué, Réssayer une autre fois. '));
		}
		exit;
	}
    /* ****************************************** */
    // Partner Request Section
    /* ****************************************** */

    if($this->getAction() ==='Edit' && $this->checkParams('PartnerRequestAccept', 2)){

        if($this->getParamsIndexOf() == 2){
            $user = (new Restaurant)->setUid(Handler::getNumber(Handler::decryptInt($this->getParamsIndexOf(1))))
            ->setPartnerRequest('A')
            ->updatePartnerRequest();
        }else{
            $user = (new Delivery)->setUid(Handler::getNumber(Handler::decryptInt($this->getParamsIndexOf(1))))
            ->setPartnerRequest('A')
            ->updatePartnerRequest();
        }
       
		if($user){
			echo json_encode(array('status' => 'success', 'info' => 'success'));
		}else{
			echo json_encode(array('status' => 'failed', 'info' => 'Operation echoué, Réssayer une autre fois. '));
		}
		exit;
    }
    
    if($this->getAction() ==='Edit' && $this->checkParams('PartnerRequestRefuse', 2)){
        if($this->getParamsIndexOf() == 2){
            $user = (new Restaurant)->setUid(Handler::getNumber(Handler::decryptInt($this->getParamsIndexOf(1))))
            ->setPartnerRequest('R')
            ->updatePartnerRequest();
        }else{
            $user = (new Delivery)->setUid(Handler::getNumber(Handler::decryptInt($this->getParamsIndexOf(1))))
            ->setPartnerRequest('R')
            ->updatePartnerRequest();
        }
       
		if($user){
			echo json_encode(array('status' => 'success', 'info' => 'success'));
		}else{
			echo json_encode(array('status' => 'failed', 'info' => 'Operation echoué, Réssayer une autre fois. '));
		}
		exit;
    }
    
    if( $this->checkParams('SendPwd', 2) || $this->checkParams('SendPwd', 1)){
        if(Request::post('user-auth') == 2){
            $user = (new Restaurant)->setUid(Handler::getNumber(Request::post('user-id')))
            ->getUserByUid();
            $BACKEND_URL = 'backend/restaurant/login';
        }
        if(Request::post('user-auth') == 3){
            $user = (new Delivery)->setUid(Handler::getNumber(Request::post('user-id')))
            ->getUserByUid();
            $BACKEND_URL = 'backend/delivery/login';
        }
           
       if($user){
           // Send pwd mail. //
            $template = file_get_contents(PATH_TEMPLATES . 'password.html');
            $template = str_replace('{USER_NAME}', ucwords(Handler::getString($user->getFullName())), $template);
            $template = str_replace('{USER_PASSWORD}', Request::post('user-pwd'), $template);
            $template = str_replace('{USER_BACKEND_URL}', DOMAIN . HTML_PATH_ROOT . $BACKEND_URL, $template);
                
            $mail = (new Mailer)
            ->setFrom('noreply@foody.tn')
            ->setFromName('Foody')
            ->setTo($user->getEmail())
            ->setToName(ucwords($user->getFullName()))
            ->setSubject('Accès pour Foody Management System!')
            ->setBody($template)
            ->sendMail();
            if(!$mail){
                echo json_encode(
                    array(
                        'status' => 'failed',
                        'info' => 'Erreur d\'envoyer un email pour cette adresse e-mail.'
                        )
                );
                exit;
            }
            echo json_encode(
                array('status' => 'success', 'info' => 'Email envoyé avec succés!')
            );
		}else{
			echo json_encode(array('status' => 'failed', 'info' => 'Operation echoué, Réssayer une autre fois. '));
		}
		exit;
	}
}

$this->getScriptArray(
    [
        'select2',
        'dropzone',
        'sweetalert',
        'datatable',
        'password-indicator',
        'datepicker',
        'form-plugins',
        'clockpicker',
        'google-maps',
        '--component-script'
    ]
);

// Start HTML
$this->requireTPL('header');
$this->requireTPL('page-loader');

?>
<!-- begin #page-container -->
<div id="page-container" class="fade page-sidebar-fixed page-header-fixed show">

    <?php $this->requireTPL('header-navbar'); ?>

    <?php $this->requireTPL('page-sidebar'); ?>
    <!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <div class="breadcome">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row pull-right">
                            <ol class="breadcrumb ">
                                <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
                                <li class="breadcrumb-item active">Users </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end breadcrumb -->
        <?php
        ($this->getAction() === 'Add') ?  $this->requireTPL('users-add') : ( ($this->getAction() === 'Edit' && count($this->getParams()) == 2 && is_numeric($this->getParamsIndexOf()) && is_numeric($this->getParamsIndexOf(1))) ? $this->requireTPL('users-edit') : $this->requireTPL('users-list'));
        ?>


    </div>
    <!-- end #content -->

    <?php $this->requireTPL('theme-panel'); ?>

    <!-- begin scroll to top btn -->
    <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
    <!-- end scroll to top btn -->

</div>
<!-- end page container -->

<?php

$this->requireTPL('base-js');

?>