<?php
require_once     PATH_MODULES        . "Authority.module.php";

if(Session::get('user_auth') != 2){
	Request::redirect(HTML_PATH_BACKEND . 'dashboard');
}

require_once     PATH_CONTROLLERS    . "MenuCategories.class.php";

require_once     PATH_CONTROLLERS    . "Dish.class.php";

require_once     PATH_CONTROLLERS    . "RestaurantExtras.class.php";

require_once     PATH_CONTROLLERS    . "DishesPriceBySize.class.php";

require_once     PATH_CONTROLLERS    . "DishExtras.class.php";

require_once     PATH_MODELS         . "Error.model.php";

require_once     PATH_HELPERS        . "UploadHelper.php";

if (!empty($_POST)) {

    error_reporting(E_ERROR | E_PARSE);

    if ($this->getAction() === 'Add' && $this->checkParams('Save', 0)) {
        $uploadError    = '';
        $uploadFileName = 'placeholder.jpg';
        $fileName       = Handler::generateNameKey();
        $uploadPath     = PATH_CDN_ROOT . Handler::encryptInt(Session::get('user_id')) . DS;
        if (!is_dir($uploadPath)) {
            if (!mkdir($uploadPath, 0777, true)) {
                echo json_encode(array(
                    'status' => 'failed',
                    'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur d\'ajout le Plat: Upload Directory Not Writable, Contacter l\'administrateur!', true)
                ));
                exit;
            }
        }
        if (!empty($_FILES)) {
            $uploadHelper   = (new FileUpload)
                ->setInputName('Image')
                ->setAllowedMimeType('Image')
                ->setUploadDirectory($uploadPath)
                ->setFileName($fileName)
                ->upload();
            $uploadFileName = $uploadHelper->getUploadedFileName();
            if (!empty($uploadHelper->getErrors())) {
                $uploadError    = $uploadHelper->getErrors(0);
            }
        }
        if (empty($uploadError)) {
            global $dateTime;
            if (Request::post('switcher_checkbox') == '1') {
                $dish = (new Dish)
                    ->resetAI()
                    ->setRestaurantId(Session::get('user_id'))
                    ->setMenuId(Request::post('categorie'))
                    ->setDishName(trim(strip_tags(Request::post('dish_name'))))
                    ->setPriceBySize('T')
                    ->setDishImage($uploadFileName)
                    ->setDescription(trim(strip_tags(Request::post('dish-description'))))
                    ->setDateTime($dateTime->format('Y-m-d H:i:s'))
                    ->setStatus(Request::post('switcher_checkbox_status'))
                    ->addElement(true);
                if (!empty($dish)) {
                    if (!empty(Request::post('dish-price'))) {
                        $pricesArray = Request::post('dish-price');
                        array_walk($pricesArray, function (&$price) {
                            $price = Handler::currency_format(Handler::getNumber($price));
                        });
                        $dishPrices = (new DishesPriceBySize)
                            ->resetAI()
                            ->setDishId($dish)
                            ->setSizeName(Request::post('dish-size'))
                            ->setPrice($pricesArray)
                            ->setStatus('1')
                            ->addElements();
                        if (!$dishPrices) {
                            echo json_encode(array(
                                'status' => 'failed',
                                'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur d\'ajout les prix en fonction de taille, Réessayer!', true)
                            ));
                            exit;
                        }
                    }
                }
            } else {
                $dish = (new Dish)
                    ->resetAI()
                    ->setRestaurantId(Session::get('user_id'))
                    ->setMenuId(Request::post('categorie'))
                    ->setDishName(trim(strip_tags(Request::post('dish_name'))))
                    ->setPriceBySize('F')
                    ->setPrice(Handler::currency_format(Handler::getNumber(Request::post('dish_price'))))
                    ->setDishImage($uploadFileName)
                    ->setDescription(trim(strip_tags(Request::post('dish-description'))))
                    ->setDateTime($dateTime->format('Y-m-d H:i:s'))
                    ->setStatus(Request::post('switcher_checkbox_status'))
                    ->addElement(true);
            }
            if (!empty($dish)) {
                if (!empty(Request::post('supplements'))) {
                    $dishesExtras = (new DishExtras)
                        ->resetAI()
                        ->setDishId($dish)
                        ->setExtraId(Request::post('supplements'))
                        ->addElements();
                    if (!$dishesExtras) {
                        echo json_encode(array(
                            'status' => 'failed',
                            'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur d\'ajout les Suppléments, Réessayer!', true)
                        ));
                        exit;
                    }
                }
                echo json_encode(array(
                    'status' => 'success',
                    'info' => AlertError::defaultNote('success', 'ion-md-checkmark-circle-outline', 'Succés!', 'Plat ajouter au menu avec succés.', true)
                ));
                exit;
            } else {
                echo json_encode(array(
                    'status' => 'failed',
                    'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur d\'ajout le Plat, Réessayer!', true)
                ));
                exit;
            }
        } else {

            echo json_encode(array(
                'status' => 'failed',
                'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur d\'ajout le Plat: ' . $uploadError . ', Réessayer!', true)
            ));
            exit;
        }
    }
    if ($this->getAction() === 'Edit' && $this->checkParams('Save', 1)) {
        $dishId = Handler::getNumber($this->getParamsIndexOf());
        $uploadError    = '';
        $uploadFileName = NULL;
        $fileName       = Handler::generateNameKey();
        $uploadPath     = PATH_CDN_ROOT . Handler::encryptInt(Session::get('user_id')) . DS;
        if (!is_dir($uploadPath)) {
            if (!mkdir($uploadPath, 0777, true)) {
                echo json_encode(array(
                    'status' => 'failed',
                    'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur d\'ajout le Plat: Upload Directory Not Writable, Contacter l\'administrateur!', true)
                ));
                exit;
            }
        }
        if (!empty($_FILES)) {
            $uploadHelper   = (new FileUpload)
                ->setInputName('Image')
                ->setAllowedMimeType('Image')
                ->setUploadDirectory($uploadPath)
                ->setFileName($fileName)
                ->upload();
            $uploadFileName = $uploadHelper->getUploadedFileName();
            if (!empty($uploadHelper->getErrors())) {
                $uploadError    = $uploadHelper->getErrors(0);
            }
        }
        if (empty($uploadError)) {
            global $dateTime;
            if (!empty(Request::post('switcher_checkbox')) && Request::post('switcher_checkbox') == '1') {
                $dish = (new Dish)
                    ->setId($dishId)
                    ->setMenuId(Request::post('categorie'))
                    ->setDishName(trim(strip_tags(Request::post('dish_name'))))
                    ->setPriceBySize('T')
                    ->setPrice(0)
                    ->setDishImage($uploadFileName)
                    ->setDescription(trim(strip_tags(Request::post('dish-description'))))
                    ->setStatus(Request::post('switcher_checkbox_status'))
                    ->updateElementById();
                if ($dish){
                    if (!empty(Request::post('dish-price'))) {
                        $pricesArray = Request::post('dish-price');
                        array_walk($pricesArray, function (&$price) {
                            $price = Handler::currency_format(Handler::getNumber($price));
                        });
                        $dishPrices = (new DishesPriceBySize)
                                      ->setDishId($dishId)
                                      ->removeByDishId();
                        $dishPrices = (new DishesPriceBySize)
                            ->resetAI()
                            ->setDishId($dishId)
                            ->setSizeName(Request::post('dish-size'))
                            ->setPrice($pricesArray)
                            ->setStatus('1')
                            ->addElements();
                        if (!$dishPrices) {
                            echo json_encode(array(
                                'status' => 'failed',
                                'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier les prix en fonction de taille, Réessayer!', true)
                            ));
                            exit;
                        }
                    }
                }
            } else {
                $dish = (new Dish)
                    ->setId($dishId)
                    ->setMenuId(Request::post('categorie'))
                    ->setDishName(trim(strip_tags(Request::post('dish_name'))))
                    ->setPriceBySize('F')
                    ->setPrice(Handler::currency_format(Handler::getNumber(Request::post('dish_price'))))
                    ->setDescription(trim(strip_tags(Request::post('dish-description'))))
                    ->setStatus(Request::post('switcher_checkbox_status'))
                    ->updateElementById();
            }
            if ($dish) {
                if (!empty(Request::post('supplements'))) {
                    $dishesExtras = (new DishExtras)
                                    ->setDishId($dishId)
                                    ->removeByDishId();
                    $dishesExtras = (new DishExtras)
                        ->resetAI()
                        ->setDishId($dishId)
                        ->setExtraId(Request::post('supplements'))
                        ->addElements();
                    if (!$dishesExtras) {
                        echo json_encode(array(
                            'status' => 'failed',
                            'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier les Suppléments, Réessayer!', true)
                        ));
                        exit;
                    }
                }
                echo json_encode(array(
                    'status' => 'success',
                    'info' => AlertError::defaultNote('success', 'ion-md-checkmark-circle-outline', 'Succés!', 'Plat modifier avec succés.', true)
                ));
                exit;
            } else {
                echo json_encode(array(
                    'status' => 'failed',
                    'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier le Plat, Réessayer!', true)
                ));
                exit;
            }
        } else {

            echo json_encode(array(
                'status' => 'failed',
                'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier le Plat: ' . $uploadError . ', Réessayer!', true)
            ));
            exit;
        }
    }
    if ($this->getAction() === 'Delete') {
        if (Request::post('data-price') == 'T') {
            $dishPriceBySize = (new DishesPriceBySize)
                ->setDishId(Handler::getNumber(Request::post('dish-id')))
                ->removeByDishId();
            if (!$dishPriceBySize) {
                echo json_encode(array('status' => 'failed', 'info' => 'Opération échouée, Erreur lors du suppression les Prix.'));
            }
        }
        if (Request::post('data-extras') != 0) {
            $dishExtras = (new DisheExtras)
                ->setDishId(Handler::getNumber(Request::post('dish-id')))
                ->removeByDishId();
            if (!$dishExtras) {
                echo json_encode(array('status' => 'failed', 'info' => 'Opération échouée, Erreur lors du suppression les Suppléménts.'));
            }
        }
        $dish = (new Dish)
            ->setId(Handler::getNumber(Request::post('dish-id')))
            ->removeElementById();
        if ($dish) {
            echo json_encode(array('status' => 'success', 'info' => 'Element supprimé avec succés'));
        } else {
            echo json_encode(array('status' => 'failed', 'info' => 'Opération échouée, Réssayer une autre fois.'));
        }
        exit;
    }
}
$this->getScriptArray(
    [
        'select2',
        'form-plugins',
        'bootstrap-select',
        'sweetalert',
        'dropzone',
        '--component-script'
    ]
);

// {
//     $dish = (new Dish)
//             ->setId(Handler::getNumber($this->getParamsIndexOf()))
//             ->getElementById();
// if($dish){

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
                                <li class="breadcrumb-item active">Dishes </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end breadcrumb -->
        <?php
        ($this->getAction() === 'Add') ?  $this->requireTPL('dishes-add') : ( ($this->getAction() === 'Edit' && count($this->getParams()) == 1 && is_numeric($this->getParamsIndexOf())) ? $this->requireTPL('dishes-edit') : $this->requireTPL('dishes-list'));
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