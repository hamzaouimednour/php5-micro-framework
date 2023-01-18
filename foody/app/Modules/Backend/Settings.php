<?php
require_once 	PATH_MODULES 		. "Authority.module.php";

if(Session::get('user_auth') != 1){
	Request::redirect(HTML_PATH_BACKEND . 'dashboard');
}

require_once 	PATH_CONTROLLERS	. "Options.class.php";

require_once 	PATH_MODELS 		. "Error.model.php";

if(!empty($_POST)){

    error_reporting(E_ALL ^ E_NOTICE);  
    
	if($this->getAction() === 'Edit'){
	
        $option = (new Options);
        $fields = 0;
        foreach ($_POST as $key => $value) {
            $option->setOptionName($key)->setOptionValue($value)->updateElementByName();
            if($option){$fields++;}
        }							
		if($fields == count($_POST)){
			echo json_encode(array('status' => 'success', 'info' => AlertError::success('Les options modifié avec succés')));
		}else{
			echo json_encode(array('status' => 'failed', 'info' => AlertError::failed('Echec, réssayer une autre fois.')));
		}
		exit;
	}

}

// Call Scripts CSS / JS in specific pages.
$this->getScriptArray(
	[
		'datatable',
		'--component-script',
		'sweetalert',
		'spinner',
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
									<li class="breadcrumb-item active">Options </li>
								</ol>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- end breadcrumb -->
			<div class="m-b-20"></div>
		<?php 
		
			
			$this->requireTPL('settings');
		
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
