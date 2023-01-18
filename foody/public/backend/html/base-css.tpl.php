	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<meta name="base-html" content="<?= HTML_PATH_ROOT ?>">
	<?= $this->appendCSS("https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700");?>
	<?= $this->appendCSS("https://fonts.googleapis.com/css?family=Maven+Pro:400,500,700,900|Roboto+Condensed:300,300i,400,400i,700,700i");?>
	<?= $this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/jquery-ui/jquery-ui.min.css");?>
	<?= $this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap/css/bootstrap.min.css");?>
	<?= $this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/font-awesome/css/all.min.css");?>
	<?= $this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/animate/animate.min.css");?>
	<?= $this->appendCSS(HTML_PATH_PUBLIC . "assets/css/default/style.min.css");?>
	<?= $this->appendCSS(HTML_PATH_PUBLIC . "assets/css/default/style-responsive.min.css");?>
	<?= $this->appendCSS(HTML_PATH_PUBLIC . "assets/css/default/theme/default.css", 'id="theme"');?>
	<?= $this->appendCSS(HTML_PATH_PUBLIC . "assets/css/default/donut-spinner.css", 'id="theme"');?>
	<!-- ================== END BASE CSS STYLE ================== -->

	<!-- ================== BEGIN PAGE LEVEL CSS STYLE ================== -->
	<?php 
	$this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/gritter/css/jquery.gritter.css");
	if($this->getComponent() === 'Dashboard'){
		echo "\t";
		$this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css");
	}
	echo "\t";
	$this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/simple-line-icons/css/simple-line-icons.css");
	echo "\t";
	$this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/ionicons/css/ionicons.min.css");
	if(in_array('datatable',$this->getScriptArray())){
		echo "\t";
		$this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css");
		echo "\t";
		$this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css");
	}
	if (in_array('rating', $this->getScriptArray())) {
        echo "\t";
        $this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/rating/starrr.css");
    }
	if(in_array('spinner',$this->getScriptArray())){
		echo "\t";
		$this->appendCSS(HTML_PATH_PUBLIC . "assets/css/default/donut-spinner.css");
	}
	if(in_array('bootstrap-select',$this->getScriptArray())){
		echo "\t";
		$this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-select/bootstrap-select.min.css");
	}
	if(in_array('select2',$this->getScriptArray())){
		echo "\t";
		$this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/select2/dist/css/select2.min.css");
	}
	if(in_array('dropzone',$this->getScriptArray())){
		echo "\t";
		$this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/dropzone/min/dropzone.min.css");
	}
	if(in_array('password-indicator',$this->getScriptArray())){
		echo "\t";
		$this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/password-indicator/css/password-indicator.css");
	}
	if(in_array('clockpicker',$this->getScriptArray())){
		echo "\t";
		$this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/clockpicker/bootstrap-clockpicker.min.css");
	}
	if(in_array('datepicker',$this->getScriptArray())){
		echo "\t";
		$this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css");
		echo "\t";
		$this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css");
	}
	if(in_array('datetimepicker',$this->getScriptArray())){
		echo "\t";
		$this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min.css");
	}
	if(in_array('invoice',$this->getScriptArray())){
		echo "\t";
		$this->appendCSS(HTML_PATH_PUBLIC . "assets/css/default/invoice-print.min.css");
	}
	if(in_array('toastr',$this->getScriptArray())){
		echo "\t";
		$this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/toastr/toastr.min.css");
	}
	?>
	<!-- ================== END PAGE LEVEL CSS STYLE ================== -->

	<!-- ================== BEGIN BASE JS ================== -->
	<?= $this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/pace/pace.min.js"); ?>
	<?=$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/jquery/jquery-3.3.1.min.js");?>
	<!-- ================== END BASE JS ================== -->
	<style>
		.swal-text {
		text-align: center;
		}
		.text-wrap {
			overflow: hidden;
			text-overflow: ellipsis;
			display: -webkit-box;
			line-height: 16px;     /* fallback */
			max-height: 32px;      /* fallback */
			-webkit-line-clamp: 1; /* number of lines to show */
			-webkit-box-orient: vertical;
		}

		/* .popover{
			z-index: 100000000;
		} */
	</style>