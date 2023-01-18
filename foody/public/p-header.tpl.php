<?php
// Start HTML
$this->requireTPL('p-base-css', PATH_PUBLIC);
$this->requireTPL('p-preload', PATH_PUBLIC);
if(empty($this->getComponent())){
    if(empty(Session::get('customer_id'))){
        $this->requireTPL('p-login-modal', PATH_PUBLIC);
        $this->requireTPL('p-topbar-login', PATH_PUBLIC);
    }else {
        $this->requireTPL('p-topbar-user', PATH_PUBLIC);
        $this->requireTPL('p-user-profile-modal', PATH_PUBLIC);
    }
}else {
    if(!empty(Session::get('customer_id'))){
        $this->requireTPL('p-topbar-user', PATH_PUBLIC);
        $this->requireTPL('p-navbar-user', PATH_PUBLIC);
        $this->requireTPL('p-user-profile-modal', PATH_PUBLIC);
    }else{
        $this->requireTPL('p-login-modal', PATH_PUBLIC);
        $this->requireTPL('p-topbar-login', PATH_PUBLIC);
        $this->requireTPL('p-navbar-login', PATH_PUBLIC);
    }
}

?>