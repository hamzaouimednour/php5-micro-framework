<?php

//--------------------------------------------------------------------------
// Define the absolute paths for Application directories
//--------------------------------------------------------------------------

define('DS',            DIRECTORY_SEPARATOR);

define('PATH_ROOT',     __DIR__     . DS);

define('PATH_APP',      PATH_ROOT   . 'app' . DS);

//--------------------------------------------------------------------------
// Load Core and Handler
//--------------------------------------------------------------------------

include PATH_APP   .   'core.php';

include PATH_APP   .   'autoload.php';

//--------------------------------------------------------------------------
// Run the Application
//--------------------------------------------------------------------------
// print_r(Request::processRequest());
$app = new Framework(Request::processRequest());
// echo "Run Module :" .  $app->getModule() . "<br>";

$request = array(
    'Module' => $app->getModule(),
    'Component' => $app->getComponent(),
    'Action' => $app->getAction(),
    'Params' => $app->getParams()
);
// print_r($request);
$app->run();

?>