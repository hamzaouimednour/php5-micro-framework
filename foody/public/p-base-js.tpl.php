      
        <!-- Bootstrap core JavaScript -->
        <!-- <?= $this->appendJS(HTML_PATH_PUBLIC . "vendor/jquery/jquery.min.js"); ?> -->
        <?= $this->appendJS(HTML_PATH_PUBLIC . "vendor/bootstrap/js/bootstrap.bundle.min.js"); ?>

        <!-- Select2 Js -->
        <?= $this->appendJS(HTML_PATH_PUBLIC . "vendor/select2/js/select2.min.js"); ?>

        <!-- Owl Carousel -->
        <?= $this->appendJS(HTML_PATH_PUBLIC . "vendor/owl-carousel/owl.carousel.js"); ?>

        <!-- Custom -->
        <?= $this->appendJS(HTML_PATH_PUBLIC . "js/custom.min.js"); ?>
        <?= $this->appendJS(HTML_PATH_PUBLIC . "js/script.js?" . time()); ?>

        <!-- Scripts -->
    <?php
    if(in_array('search',$this->getScriptArray())){
        echo "\t";
        $this->appendJS(HTML_PATH_PUBLIC . "js/search.js");
    }
    if(in_array('sweetalert',$this->getScriptArray())){
        echo "\t";
        $this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-sweetalert/sweetalert.min.js");
    }
    if (in_array('--cart-script', $this->getScriptArray())) {
        echo "\t\t";
        $this->appendJS(HTML_PATH_PUBLIC . "js/cart.common.js?" . time());
        echo "\t\t";
        $this->appendJS(HTML_PATH_PUBLIC . "js/cart.min.js?" . time());
 
    }
    if (in_array('--component-script', $this->getScriptArray())) {
        echo "\t\t";
        $this->appendJS(HTML_PATH_PUBLIC . "js/" . hash('fnv1a32', $this->getComponent()) . ".js?" . time());
    }
    if (in_array('--init-map-address', $this->getScriptArray())) {
        echo "\t\t";
        $this->appendJS(HTML_PATH_PUBLIC . "js/map-2c6a2953.js");
    }
    if (in_array('--add-map-address', $this->getScriptArray())) {
        echo "\t\t";
        $this->appendJS(HTML_PATH_PUBLIC . "js/map-ce6d9359.js");
    }
    if (in_array('--init-map-checkout', $this->getScriptArray())) {
        echo "\t\t";
        $this->appendJS(HTML_PATH_PUBLIC . "js/map-4db95739.js");
    }
    if (in_array('--init-map-delivery', $this->getScriptArray())) {
        echo "\t\t";
        $this->appendJS(HTML_PATH_PUBLIC . "js/map-f9c53a55.js");
    }
    if (in_array('datatables', $this->getScriptArray())) {
        echo "\t\t";
        $this->appendCSS(HTML_PATH_PUBLIC . "vendor/datatables/datatables.min.css");
        echo "\t\t";
        $this->appendJS(HTML_PATH_PUBLIC . "vendor/datatables/datatables.min.js");
    }
    if (in_array('rating', $this->getScriptArray())) {
        echo "\t\t";
        $this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/rating/starrr.js");
        echo "\t\t";
        $this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/rating/jquery.rating.init.js");
    }

    if (in_array('google-maps', $this->getScriptArray())) {
        echo "\t\t";
        $this->appendJS("https://maps.googleapis.com/maps/api/js?language=fr-fr&key=AIzaSyCkG1aDqrbOk28PmyKjejDwWZhwEeLVJbA&callback=initMap", "async defer");
    }
    
    ?>
    
        <script>
            "use strict";

            //Preloader
            var preloader = $('.preloader');
            $(window).on('load', function() {
                var preloaderFadeOutTime = 500;

                function hidePreloader() {
                    preloader.fadeOut(preloaderFadeOutTime);
                }
                hidePreloader();
            });
            <?php
                if (in_array('datatables', $this->getScriptArray())) {
                    echo "\n";
            ?>
            $(document).ready(function() {
                $('.datatabel').DataTable();
            } );
            <?php
                }
            ?>

        </script>
    </body>

</html>