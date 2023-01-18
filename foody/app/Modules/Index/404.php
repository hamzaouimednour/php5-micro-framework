<?php
    if (count(Request::processRequest()) > 1)
        Request::redirect(HTML_PATH_ROOT . '404');

        defined('PATH_ROOT') or die('Attempt Unauthorized, Access Denied.');
        
        
        //--------------------------------------------------------------------------
        // Start Session.
        //--------------------------------------------------------------------------
        require_once PATH_MODULES     . "Session.module.php";

        
        //--------------------------------------------------------------------------
        // Require Controllers, Modules, Helpers.
        //--------------------------------------------------------------------------
        
        require_once PATH_CONTROLLERS . 'Restaurant.class.php';
        
        require_once PATH_HELPERS . "URLifyHelper.php";
        
        require_once PATH_CONTROLLERS . 'Specialties.class.php';
        
        require_once PATH_CONTROLLERS . 'RestaurantSpecialties.class.php';
        
        require_once PATH_CONTROLLERS . 'RestaurantWork.class.php';
        
        require_once PATH_CONTROLLERS . 'RestaurantExtras.class.php';
        
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
        
$this->getScriptArray(
    [
        '--font-style',
        'icofont',
        'search',
        'font-awesome',
        'sweetalert',
        '--cart-script',
        '--component-script'
    ]
);
    $this->requireTPL('p-header', PATH_PUBLIC);
    $this->requireTPL('p-404', PATH_PUBLIC);
    $this->requireTPL('p-footer', PATH_PUBLIC);
    $this->requireTPL('p-base-js', PATH_PUBLIC);
?>