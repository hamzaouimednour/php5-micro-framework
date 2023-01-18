	
	<!-- ================== BEGIN BASE JS ================== -->
	<?=$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/jquery-ui/jquery-ui.min.js");?>
	<?=$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap/js/bootstrap.bundle.min.js");?>

	<?=$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/slimscroll/jquery.slimscroll.min.js");?>
	<?=$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/js-cookie/js.cookie.js");?>
	<?=$this->appendJS(HTML_PATH_PUBLIC . "assets/js/theme/default.min.js");?>
	<?=$this->appendJS(HTML_PATH_PUBLIC . "assets/js/apps.min.js");?>
	<?=$this->appendJS(HTML_PATH_PUBLIC . 'assets/js/script.js?' . time());?>
	<!-- <?=$this->appendJS(HTML_PATH_PUBLIC . "js/reload.min.js");?> -->
	<?php
		if(in_array('--component-script',$this->getScriptArray())){
			$this->appendJS(HTML_PATH_PUBLIC . "assets/js/".hash('fnv1a32', $this->getComponent()).".js?".time());
		}
		
		if(in_array('--component-script-webmaster',$this->getScriptArray())){
			$this->appendJS(HTML_PATH_PUBLIC . "assets/js/w-".hash('fnv1a32', $this->getComponent()).".js?".time());
		}
		
	?>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<?php
	$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/gritter/js/jquery.gritter.js");
		if($this->getComponent() === 'Dashboard'){
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js");
		}
		if(in_array('datatable',$this->getScriptArray())){
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/DataTables/media/js/jquery.dataTables.js");
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js");
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js");
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/js/demo/table-manage-default.demo.min.js");
		}
		if(in_array('jquery-clock-timepicker',$this->getScriptArray())){
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/jquery-clock-timepicker/jquery-clock-timepicker.min.js");
		}
		if(in_array('sweetalert',$this->getScriptArray())){
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-sweetalert/sweetalert.min.js");
		}
		if(in_array('bootstrap-select',$this->getScriptArray())){
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-select/bootstrap-select.min.js");
		}
		if(in_array('dropzone',$this->getScriptArray())){
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/dropzone/min/dropzone.min.js");
		}
		if(in_array('clockpicker',$this->getScriptArray())){
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/clockpicker/bootstrap-clockpicker.min.js");
		}
		if(in_array('select2',$this->getScriptArray())){
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-combobox/js/bootstrap-combobox.js");
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/select2/dist/js/select2.min.js");
		}
		if (in_array('rating', $this->getScriptArray())) {
			echo "\t\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/rating/starrr.js");
			echo "\t\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/rating/jquery.rating.init.js");
		}
		if(in_array('password-indicator',$this->getScriptArray())){
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/password-indicator/js/password-indicator.js");
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-show-password/bootstrap-show-password.js");
		}

		if(in_array('datepicker',$this->getScriptArray())){
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js");
		}

		if(in_array('datetimepicker',$this->getScriptArray())){
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-eonasdan-datetimepicker/build/js/moment-with-locales.js");
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min.js");

		}
		if(in_array('toastr',$this->getScriptArray())){
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/toastr/toastr.min.js");
		}

		if(in_array('form-plugins',$this->getScriptArray())){
			echo "\t";
			$this->appendJS(HTML_PATH_PUBLIC . "assets/js/demo/form-plugins.demo.js?" . time());
		}
		if(in_array('google-maps',$this->getScriptArray())){
			echo "\t";
			$this->appendJS("https://maps.googleapis.com/maps/api/js?language=fr-fr&key=AIzaSyCkG1aDqrbOk28PmyKjejDwWZhwEeLVJbA&callback=initMap", "async defer");
		}
		
	?>
    <!-- ================== END PAGE LEVEL JS ================== -->
	
	<script>
		
		$(document).ready(function() {
			App.init();
			<?=in_array('datatable', $this->getScriptArray()) ? "TableManageDefault.init();\n" : "\n";?>
			<?=in_array('form-plugins', $this->getScriptArray()) ? "FormPlugins.init();\n" : "\n";?>
			
		});
	</script>
</body>
</html>