<?php

//--------------------------------------------------------------------------
// Set Default Max Sizes.
//--------------------------------------------------------------------------

@ini_set('post_max_size', '32M');
@ini_set('upload_max_filesize', '32M');

//--------------------------------------------------------------------------
// Set file size using defined UNITS.
//--------------------------------------------------------------------------

define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);
define('TB', 1099511627776);

//--------------------------------------------------------------------------
// Website Informations.
//--------------------------------------------------------------------------

$imgSizes = array("69x63", "130x110", "366x487", "320x480", "187x220", "390x220", "288x326", "370x235");
$dishesImg = array("width" => 508, "height" => 320);
?>