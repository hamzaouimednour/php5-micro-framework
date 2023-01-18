<?php

require_once PATH_MODULES . 'Session.module.php';

if(Session::LogoutFrontUser(Session::get('customer_id')))
Request::redirect(HTML_PATH_ROOT);
?>