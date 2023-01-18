<!-- <!DOCTYPE html> -->
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="foody">
    <meta name="author" content="foody">
    <meta name="url" content="<?= DOMAIN . HTML_PATH_ROOT ?>">
    <meta name="base-html" content="<?= HTML_PATH_ROOT ?>">
    <title>Foody - Livraison de repas à domicile en Tunisie</title>
    <!-- Favicon Icon -->
    <!-- <link rel="icon" type="image/png" href="img/favicon.png"> -->
    <!-- Bootstrap core CSS -->
    <?= $this->appendCSS(HTML_PATH_PUBLIC . "vendor/bootstrap/css/bootstrap.min.css"); ?>
    <!-- Material Design Icons -->
    <?= $this->appendCSS(HTML_PATH_PUBLIC . "vendor/icons/css/materialdesignicons.min.css", 'media="all" type="text/css"'); ?>
    <!-- Select2 CSS -->
    <?= $this->appendCSS(HTML_PATH_PUBLIC . "vendor/select2/css/select2-bootstrap.css"); ?>
    <?= $this->appendCSS(HTML_PATH_PUBLIC . "vendor/select2/css/select2.min.css"); ?>
    <!-- Custom styles for this template -->
    <?= $this->appendCSS(HTML_PATH_PUBLIC . "css/osahan.min.css"); ?>

    <?php if ($this->getComponent() === 'Restaurant') $this->appendCSS(HTML_PATH_PUBLIC . 'css/icofont.css'); ?>
    <!-- Owl Carousel -->
    <?= $this->appendCSS(HTML_PATH_PUBLIC . "vendor/owl-carousel/owl.carousel.css"); ?>
    <?= $this->appendCSS(HTML_PATH_PUBLIC . "vendor/owl-carousel/owl.theme.css"); ?>
    <?php
    if (in_array('style-home', $this->getScriptArray())) {
        $this->appendCSS(HTML_PATH_PUBLIC . "css/style-home.css");
    }
    if (in_array('--font-style', $this->getScriptArray())) {
        echo "\t";
        $this->appendCSS(HTML_PATH_PUBLIC . "fonts/style.css");
    }
    if (in_array('icofont', $this->getScriptArray())) {
        echo "\t";
        $this->appendCSS(HTML_PATH_PUBLIC . "css/icofont.css");
    }
    if (in_array('font-awesome', $this->getScriptArray())) {
        echo "\t";
        $this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/font-awesome/css/all.min.css");
    }
    if (in_array('rating', $this->getScriptArray())) {
        echo "\t";
        $this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/rating/starrr.css");
    }
    ?>
    <?= $this->appendCSS(HTML_PATH_PUBLIC . "css/preloader.css"); ?>
    <?= $this->appendCSS(HTML_PATH_PUBLIC . "css/custom.css"); ?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>

<body>