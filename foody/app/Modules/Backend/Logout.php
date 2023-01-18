<?php

require_once PATH_MODULES . 'Authority.module.php';
if($session['user_auth'] == 1){
    $path = HTML_PATH_BACKEND . "webmaster/login";
}
if($session['user_auth'] == 2){
    $path = HTML_PATH_BACKEND . "restaurant/login";
}
if($session['user_auth'] == 3){
    $path = HTML_PATH_BACKEND . "delivery/login";
}
if(Session::LogoutUser($session['user_auth'], $session['user_id'] ))
Request::redirect($path);
?>