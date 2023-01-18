<?php
defined('PATH_ROOT') or die('Attempt Unauthorized, Access Denied.');

// disable errors and notice in this page specificlly cause jquer.Json cant parse
// the data when there is errors.

error_reporting(E_ALL ^ E_NOTICE);  

//--------------------------------------------------------------------------
// Start Session.
//--------------------------------------------------------------------------
Session::start();

//--------------------------------------------------------------------------
// Check for user authority.
//--------------------------------------------------------------------------


if(!empty(Session::get( 'user_auth' )) && !empty(Session::get( 'user_id' )) && Session::get( 'user_auth' )==2){

}

//--------------------------------------------------------------------------
// Verif the request.
//--------------------------------------------------------------------------

if($this->checkParams('auth', 0) && !empty($_POST) ) { //
	
    //--------------------------------------------------------------------------
    // User Class.
    //--------------------------------------------------------------------------
    require_once  PATH_CONTROLLERS   . "Restaurant.class.php";


    //--------------------------------------------------------------------------
    // Crypt Helper : for crypting passwds.
    //--------------------------------------------------------------------------
    require_once  PATH_HELPERS       . "CryptHelper.php";

    $restaurant     = (new Restaurant)

                    ->setEmail(Handler::is_email(Request::post('email'))) //

                    ->setPasswd(CryptHelper::crypt(Request::post('passwd'))) //

					->setPartnerRequest('A')
					
                    ->setUserStatus(1) //
                    
                    ->checkUser();

    //--------------------------------------------------------------------------
    // check the Query.
    //--------------------------------------------------------------------------
    if($restaurant){
        
        if(Request::post('remember_me')){
            
            Session::AuthUser(
                '2',
                $restaurant->getUid(),
                true
            );
        }else{
            Session::AuthUser(
                '2',
                $restaurant->getUid()
            );
        }
        echo json_encode(
			array(
            'status'    => 'success',
            'url'       =>  HTML_PATH_BACKEND . "dashboard"
        ));
    }else{
        echo json_encode(
			array(
            'status' => 'failed'
            //'error' => serialize()
        ));
    }
    exit();
}
$this->getScriptArray(
    [

        'sweetalert'
    ]
);
$this->requireTPL('header');
?>

<body class="pace-top bg-white">
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade show"><span class="spinner"></span></div>
	<!-- end #page-loader -->
    <div id="page-container" class="fade">
		<!-- begin login -->
		<div class="login login-with-news-feed">
			<!-- begin news-feed -->
			<div class="news-feed">
				<div class="news-image" style="background-image: url(<?= HTML_PATH_IMG . "slider/slider-9.jpeg";?>)"></div>
				<div class="news-caption font-accent">
					<h1 class="caption-title">Great food as Quick as click</h1>
					<p>
						Download the Color Admin app for iPhone®, iPad®, and Android™. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
					</p>
				</div>
			</div>
			<!-- end news-feed -->
			<!-- begin right-content -->
			<div class="right-content">
				<!-- begin login-header -->
				<div class="login-header font-accent">
					<div class="brand">
						<h1>Foody.tn, Restaurant Managment System.</h1>
						<small>Your Favourite Food delivery Partner, YUMMY!</small>
					</div>
					<div class="icon">
						<i class="fa fa-sign-in"></i>
					</div>
				</div>
                <!-- end login-header -->
				<!-- begin login-content -->
				<div class="login-content">
					<form id="backend-login-form" method="POST" class="margin-bottom-0">
						<div class="form-group m-b-15">
							<input type="text" name="email" class="form-control form-control-lg" placeholder="Email Address" required />
						</div>
						<div class="form-group m-b-15">
							<input type="password" name="passwd" class="form-control form-control-lg" placeholder="Password" required />
						</div>
						<div class="checkbox checkbox-css m-b-30">
							<input type="checkbox" name="remember_me" id="remember_me_checkbox" value="1" />
							<label for="remember_me_checkbox">
							Remember Me
							</label>
						</div>
						<div class="login-buttons">
                        
							<button type="submit" id="login-btn" class="btn btn-success btn-block btn-lg"><span></span> Connexion</button>
						</div>
						<hr />
						<p class="text-center text-grey-darker">
							&copy; Foody.tn All Right Reserved 2019
						</p>
					</form>
				</div>
				<!-- end login-content -->
			</div>
			<!-- end right-container -->
		</div>
		<!-- end login -->
	</div>
    <!-- end page container -->
<?php

$this->requireTPL('base-js');

?>