<?php
require_once PATH_MODULES . "Authority.module.php";

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

require_once PATH_HELPERS     . "ResizeImageHelper.php";

require_once PATH_HELPERS     . "CryptHelper.php";

$user = null;
switch (Session::get('user_auth')) {
    case 1:
        $user = new Administrator;
        break;
    case 2:
        $user = new Restaurant;
        break;
    case 3:
        $user = new Delivery;
}

if (!empty($_POST)) {

    error_reporting(E_ALL ^ E_NOTICE);

    if ($this->getParamsIndexOf() === 'Request') {
        global $dateTime;
        require_once PATH_CONTROLLERS . 'UsersRequests.class.php';

        $request = (new UsersRequests)
            ->resetAI()
            ->setUserAuth(Session::get('user_auth'))
            ->setUserId(Session::get('user_id'))
            ->setRequest(Request::post('request'))
            ->setDescription(Request::post('description'))
            ->setDateTime($dateTime->format('Y-m-d H:i:s'))
            ->addElement();

        if (!$request) {
            echo json_encode(array('status' => 'failed', 'info' => AlertError::failed(' Erreur, réssayer plus tard! ')));
        } else {
            echo json_encode(array('status' => 'success', 'info' => AlertError::success(' Success, Nous avons reçu votre demande, nous y répondrons dans les meilleurs délais, merci! ')));
        }
        exit;
    }

    if ($this->getAction() === 'Search') {

        $user = $user->setUid(Session::get('user_id'))
            ->setPasswd(CryptHelper::crypt(Request::post('old-pwd')))
            ->checkUserPasswd();
        if ($user) {
            echo json_encode(array('status' => 'success', 'info' => 'Mot de passe est correct'));
        } else {
            echo json_encode(array('status' => 'failed', 'info' => 'Mot de passe est incorrect'));
        }
        exit;
    }

    if ($this->getParamsIndexOf() == 'CoverPhoto') {
        if (Session::get('user_auth') == 2) {
            global $sizeImageCover;
            $uploadError = NULL;
            $uploadFileName = NULL;
            $fileName   = Handler::generateNameKey();
            $uploadPath = PATH_CDN_ROOT . Handler::encryptInt(Session::get('user_id')) . DS;
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
                    ->setInputName('Cover')
                    ->setAllowedMimeType('Image')
                    ->setUploadDirectory($uploadPath)
                    ->setFileName('cover-' . $fileName)
                    ->upload();

                $uploadFileName = $uploadHelper->getUploadedFileName();
                if (!empty($uploadHelper->getErrors())) {
                    $uploadError    = $uploadHelper->getErrors(0);
                    echo json_encode(array(
                        'status' => 'failed',
                        'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur: ' . $uploadError . '!', true)
                    ));
                    exit;
                }
                try {
                    $resize = new ResizeImage($uploadPath . $uploadFileName);
                    $resize->saveImage($uploadPath . $uploadFileName);
                    $resize = new ResizeImage($uploadPath . $uploadFileName);
                    $resize->resizeTo($sizeImageCover['width'], $sizeImageCover['height'], 'exact');
                    $resize->saveImage($uploadPath . 'thumb-' . $uploadFileName);

                    $user = (new Restaurant)
                    ->setUid(Session::get('user_id'))
                    ->setCoverPhoto($uploadFileName)
                    ->updateUserByUid();

                    if (!$user) {
                        echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier la photo de couverture, Réessayer autre fois!', true)));
                        exit;
                    }
                    echo json_encode(array(
                        'status' => 'success',
                        'info' => AlertError::defaultNote('success', 'ion-md-checkmark-circle-outline', 'Succés!', 'Votre photo de couverture changer avec succés.', true)
                    ));
                    exit;
                } catch (Exception $e) {
                    echo json_encode(array(
                        'status' => 'failed',
                        'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur: while resize image!', true)
                    ));
                    exit;
                }
            }
            echo json_encode(array(
                'status' => 'failed',
                'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur: There is no uploading files!', true)
            ));
            exit;
        }
    }

    if ($this->getAction() === 'Edit') {
        $dataUser = $_POST;
        //==========================================
        // Address Section
        //==========================================
        if (in_array('address', array_keys($dataUser))) {

            $address = (new Address)
                ->setUserAuth(Session::get('user_auth'))
                ->setUserId(Session::get('user_id'))
                ->setAddress(Request::post('address'))
                ->setLatitude(Request::post('latitude'))
                ->setLongtitude(Request::post('longitude'))
                ->setCityId(Request::post('city'))
                ->updateByUserAuthId();
            if (!$address) {
                echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier Adresse du restaurant, Réessayer autre fois!', true)));
                exit;
            }
        }

        //============================= Check Old Pwd Validity/ =============================
        if (!empty(Request::post('old-pwd'))) {
            $checkpwd = $user->setUid(Session::get('user_id'))
                ->setPasswd(CryptHelper::crypt(Request::post('old-pwd')))
                ->checkUserPasswd();
            if (!$checkpwd) {
                echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur lors de modification de données, Mot de passe Actuel Inccorect!', true)));
                exit;
            }
        }
        $dataUser['passwd'] = empty($dataUser['new-pwd']) ? NULL : CryptHelper::crypt($dataUser['new-pwd']);
        unset($dataUser['new-pwd'], $dataUser['old-pwd']);
        if (Session::get('user_auth') == 3) {
            $dataUser['availability'] = Request::post('availability');
        }
        if (Session::get('user_auth') == 2) {

            //=========================== File Upload ===========================
            $uploadError = NULL;
            $uploadFileName = NULL;
            $fileName   = Handler::generateNameKey();
            $uploadPath = PATH_CDN_ROOT . Handler::encryptInt(Session::get('user_id')) . DS;
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
                        'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur: ' . $uploadError . '!', true)
                    ));
                    exit;
                }
            }

            //=========================== Restaurant Work Section ===========================

            $dayWorkTime = array();
            for ($i = 1; $i < 8; $i++) {
                if (in_array('day-' . $i . '-open', array_keys($_POST)) && in_array('day-' . $i . '-close', array_keys($_POST))) {
                    $dayWorkTime[$i] = array(Request::post('day-' . $i . '-open'), Request::post('day-' . $i . '-close'));
                }
            }

            $work = (new RestaurantWork)->setRestaurantId(Session::get('user_id'));
            if (Request::post('restaurant-delivery_type') == 1) {
                $work = $work->setDeliveryFee(Handler::currency_format(Handler::getNumber(Request::post('delivery-fee'))))
                    ->setInitDistance(Handler::getNumber(Request::post('init-distance')))
                    ->setUpFee(Handler::currency_format(Handler::getNumber(Request::post('up-fee'))))
                    ->setUpDistance('1')
                    // ->setUpDistance(Handler::getNumber(Request::post('up-distance')))
                    ->setDeliveryTimeMin(Handler::getNumber(Request::post('restaurant-delivery_time_min')))
                    ->setDeliveryTimeMax(Handler::getNumber(Request::post('restaurant-delivery_time_max')));
            }
            $work = $work->setDeliveryType(Request::post('restaurant-delivery_type'))
                ->setMinDelivery(Handler::currency_format(Handler::getNumber(Request::post('restaurant-min_delivery'))))
                ->setMaxDelivery(Handler::currency_format(Handler::getNumber(Request::post('restaurant-max_delivery'))))
                ->setPreparationTimeMin(Handler::getNumber(Request::post('restaurant-preparation_time_min')))
                ->setPreparationTimeMax(Handler::getNumber(Request::post('restaurant-preparation_time_max')))
                ->setDescription(Request::post('restaurant-description'))
                ->setWorkTimes($dayWorkTime)
                ->updateElementByRestaurantId();
            if (!$work) {
                echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier Work Type du restaurant, Réessayer autre fois!', true)));
                exit;
            }

            //================================== Restaurant Specialties Section =================================

            $specialties = (new RestaurantSpecialties)
                ->setRestaurantId(Session::get('user_id'))
                ->removeAllByRestaurantId();
            if ($specialties) {
                $specialties = (new RestaurantSpecialties)
                    ->setRestaurantId(Session::get('user_id'))
                    ->setSpecialtyId(Request::post('user-specialties'))
                    ->addElements();
                if (!$specialties) {
                    echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier La/Les spécialités du restaurant, Réessayer autre fois!', true)));
                    exit;
                }
            }

            //==================================== Restaurant User Section =================================

            $user = (new Restaurant)
                ->setUid(Session::get('user_id'))
                ->setFullName(Request::post('user-full_name'))
                ->setEmail(Request::post('user-email'))
                ->setPhone(Request::post('user-phone'))
                ->setPasswd($dataUser['passwd'])
                ->setRestaurantName(Request::post('user-restaurant_name'))
                ->setLogo($uploadFileName)
                ->updateUserByUid();

            if ($user) {
                echo json_encode(array('status' => 'success', 'info' => AlertError::defaultNote('success', 'ion-md-checkmark-circle-outline', 'Succés!', 'Votre compte Modifier avec succés.', true)));
            } else {
                echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier votre compte, Réessayer autre fois!', true)));
            }
            exit;
        } else {
            foreach ($dataUser as $property => $value) {
                if (method_exists($user, 'set' . Handler::getMethod($property)))
                    call_user_func_array(array($user, 'set' . Handler::getMethod($property)), array($dataUser[$property]));
            }
            $user->setUid(Session::get('user_id'))->updateUserByUid();
            if ($user) {
                echo json_encode(array('status' => 'success', 'info' => AlertError::defaultNote('success', 'ion-md-checkmark-circle-outline', 'Succés!', 'données modifier avec succés.', true)));
            } else {
                echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur lors de modification de données Réessayer!', true)));
            }
            exit;
        }
    }

    // if($this->getAction() ==='Edit' && $this->checkParams('Save', 2)){

    //     $userAuth = Handler::getNumber($this->getParamsIndexOf(0));
    //     $userUid = Handler::getNumber(Handler::decryptInt($this->getParamsIndexOf(1)));

    //     if($userAuth==2){
    //         $logo = NULL;
    //         $uploadError = NULL;
    //         $uploadFileName = NULL;
    //         $fileName   = Handler::generateNameKey();
    //         $uploadPath = PATH_CDN_ROOT . Handler::encryptInt($userUid) . DS;

    //         if (!empty($_FILES)) {
    //             if (!is_dir($uploadPath)) {
    //                 if (!mkdir($uploadPath, 0777, true)) {
    //                     echo json_encode(array(
    //                         'status' => 'failed',
    //                         'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de permission: Upload Directory Not Writable, Contacter l\'administrateur!', true)
    //                     ));
    //                     exit;
    //                 }
    //             }
    //             $uploadHelper   = (new FileUpload)
    //                 ->setInputName('Logo')
    //                 ->setAllowedMimeType('Image')
    //                 ->setUploadDirectory($uploadPath)
    //                 ->setFileName($fileName)
    //                 ->upload();
    //             $uploadFileName = $uploadHelper->getUploadedFileName();
    //             if (!empty($uploadHelper->getErrors())) {
    //                 $uploadError    = $uploadHelper->getErrors(0);
    //                 echo json_encode(array(
    //                     'status' => 'failed',
    //                     'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur: '.$uploadError.'!', true)
    //                 ));
    //                 exit;
    //             }
    //         }

    //         //==========================================
    //         // Restaurant Work Section
    //         //==========================================

    //         $dayWorkTime = array();
    //         for ($i=1; $i < 8; $i++) { 
    //             if(in_array('day-'.$i.'-open', array_keys($_POST)) && in_array('day-'.$i.'-close', array_keys($_POST))){
    //                 $dayWorkTime[$i] = array(Request::post('day-'.$i.'-open'), Request::post('day-'.$i.'-close'));
    //             }
    //         }

    //         $work = (new RestaurantWork)->setRestaurantId($userUid);
    //         if(Request::post('restaurant-delivery_type')==1){
    //             $work = $work->setDeliveryFee(Request::post('delivery-fee'))
    //             ->setInitDistance(Request::post('init-distance'))
    //             ->setUpFee(Request::post('up-fee'))
    //             ->setUpDistance(Request::post('up-distance'));
    //         }
    //         $work = $work->setDeliveryType(Request::post('restaurant-delivery_type'))
    //                 ->setMinDelivery(Request::post('restaurant-min_delivery'))
    //                 ->setMaxDelivery(Request::post('restaurant-max_delivery'))
    //                 ->setPreparationTimeMin(Request::post('restaurant-preparation_time_min'))
    //                 ->setPreparationTimeMax(Request::post('restaurant-preparation_time_max'))
    //                 ->setDeliveryTimeMin(Request::post('restaurant-delivery_time_min'))
    //                 ->setDeliveryTimeMax(Request::post('restaurant-delivery_time_max'))
    //                 ->setDescription(Request::post('restaurant-description'))
    //                 ->setWorkTimes($dayWorkTime)
    //                 ->updateElementByRestaurantId();
    //         if(!$work){
    //             echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier Work Type du restaurant, Réessayer autre fois!', true)));
    //             exit;
    //         }

    //         //==========================================
    //         // Restaurant Address Section
    //         //==========================================
    //         $address = (new Address)
    //                     ->setUserAuth($this->getParamsIndexOf())
    //                     ->setUserId($userUid)
    //                     ->setAddress(Request::post('restaurant-address'))
    //                     ->setLatitude(Request::post('restaurant-latitude'))
    //                     ->setLongtitude(Request::post('restaurant-longitude'))
    //                     ->setCityId(Request::post('restaurant-city'))
    //                     ->updateByUserAuthId();
    //         if(!$address){
    //             echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier Adresse du restaurant, Réessayer autre fois!', true)));
    //             exit;
    //         }
    //         //==========================================
    //         // Restaurant Specialties Section
    //         //==========================================

    //         $specialties = (new RestaurantSpecialties)
    //                         ->setRestaurantId($userUid)
    //                         ->removeAllByRestaurantId();
    //         if($specialties){
    //             $specialties = (new RestaurantSpecialties)
    //                         ->setRestaurantId($userUid)
    //                         ->setSpecialtyId(Request::post('user-specialties'))
    //                         ->addElements();
    //             if(!$specialties){
    //                 echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier La/Les spécialités du restaurant, Réessayer autre fois!', true)));
    //                 exit;
    //             }
    //         }

    //         //==========================================
    //         // Restaurant User Section
    //         //==========================================
    //         $pwd = empty(Request::post('user-passwd')) ? NULL : CryptHelper::crypt(Request::post('user-passwd'));
    //         $user = (new Restaurant)
    //              ->setUid($userUid)
    //              ->setFullName(Request::post('user-full_name'))
    //              ->setEmail(Request::post('user-email'))
    //              ->setPhone(Request::post('user-phone'))
    //              ->setUserStatus(Request::post('user-user_status'))
    //              ->setPasswd($pwd)
    //              ->setRestaurantName(Request::post('user-restaurant_name'))
    //              ->setLogo($uploadFileName)
    //              ->updateUserByUid();

    //         if($user){
    //             echo json_encode(array('status' => 'success', 'info' => AlertError::defaultNote('success', 'ion-md-checkmark-circle-outline', 'Succés!', 'Utilisateur Modifier avec succés.', true)));
    //         }else{
    //             echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier l\'utilisateur, Réessayer autre fois!', true)));
    //         }
    //         exit;
    //     }

    // }
}

$this->getScriptArray(
    [
        'select2',
        'bootstrap-select',
        'dropzone',
        'datepicker',
        'sweetalert',
        'password-indicator',
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
                                    <li class="breadcrumb-item active">Account </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end breadcrumb -->
            <div class="m-b-20"></div>
            <?php
            $this->requireTPL('account-' . Session::get('user_auth'));
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