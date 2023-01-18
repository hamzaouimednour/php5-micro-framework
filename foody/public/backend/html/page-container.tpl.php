
    <!-- begin #page-container -->
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">

        <?php $this->requireTPL('header-navbar'); ?>

        <?php $this->requireTPL('page-sidebar'); ?>

        <?php $this->requireTPL('page-content'); ?>

        <?php $this->requireTPL('theme-panel'); ?>

        <!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
        <!-- end scroll to top btn -->
        
    </div>
    <!-- end page container -->
    