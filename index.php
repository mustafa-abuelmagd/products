<?php
include_once './config/Router.php';
require_once __DIR__ . '/models/Products.php';
require_once  __DIR__.'/models/ServerLogger.php';
define('DirName', getcwd());

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Methods: GET, POST ,DELETE');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

header("Access-Control-Allow-Headers: X-Requested-With");


//if (isset($_SERVER['HTTP_ORIGIN'])) {
//    ServerLogger::log("came here ");
//    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
//    header('Access-Control-Allow-Credentials: true');
//    header('Access-Control-Max-Age: 86400');    // cache for 1 day
//    ServerLogger::log("came here    4" , $_SERVER['HTTP_ORIGIN']);
//
//}
//
//// Access-Control headers are received during OPTIONS requests
//if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
//    ServerLogger::log("cameddddddddddddddd here " , $_SERVER['REQUEST_METHOD']);
//
//
//    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
//        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
//
//    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
//        header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
//
//    exit(0);
//}
//
//echo "You have CORS!";
//
//header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');





$router = new Router();


//$router->get('/', Products::class . '::index');
//


// BASIC REQUIRED OPERATIONS

$router->get('/getAllProducts', Products::class . '::get_all_products');
$router->post('/addProduct', Products::class . '::add_new_product');
$router->post('/deleteProducts', Products::class . '::delete_products');
//$router->get('/getProduct', Products::class . '::get_product');

// $router->get('/getAllProductProperties', Products::class . '::get_all_product_properties');



// APPLICATION DATA, TYPES WITH THEIR PROPERTIES
$router->get('/getApplicationData', Products::class . '::get_types_data');


// ADDITIONAL OPERATIONS FOR SCALABILITY
// PRODUCT TYPE OPERATIONS
$router->get('/getAllProductTypes', Products::class . '::get_all_product_types');
$router->post('/addProductType', Products::class . '::add_new_product_type');


// PRODUCT PROPERTY OPERATIONS
$router->get('/getAllTypeProperties', Products::class . '::get_all_type_properties');
$router->post('/addTypeProperty', Products::class . '::add_type_property');


$router->addNotFoundHandler('/', function () {
    echo 'Home lol';
});

$router->run();

