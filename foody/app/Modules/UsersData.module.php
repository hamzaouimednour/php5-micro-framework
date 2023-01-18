<?php
class UsersData
{

    static function getFullName(){
        global $session;
        switch ($session['user_auth'] ) {
            case 1:
                $adm = (new Administrator)->setUid($session['user_id'])
                                          ->getUserByUid();
                
                return $adm->getFullName();
                break;
            case 2:
                $resto = (new Restaurant)->setUid($session['user_id'])
                                         ->getUserByUid();

                return $resto->getFullName();
                break;
            case 3:
                $delivery = (new Delivery)->setUid($session['user_id'])
                                          ->getUserByUid();

                return $delivery->getFullName();
                break;
        }
    }
    static function getOccupation(){

        global $session;
        switch ($session['user_auth'] ) {
            case 1:
                return 'Administrator';
                break;
            case 2:
                $resto = (new Restaurant)->setUid($session['user_id'])
                                        ->getUserByUid();
                return $resto->getRestaurantName();
                break;
            case 3:
                return 'Delivery Man';
                break;
        }
    }
    static function getRestaurantLogo(){
        global $session;
        if($session['user_auth'] == 2) {
            $resto = (new Restaurant)->setUid($session['user_id'])
                                    ->getUserByUid();
            return $resto->getLogo();
        }
    }
}


?>