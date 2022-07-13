<?php

require_once __DIR__.'/vendor/autoload.php';
use Config\Router;
use Models\Products;
define('DirName', getcwd());

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Methods: GET, POST ,DELETE');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

header("Access-Control-Allow-Headers: X-Requested-With");


$router = new Router();

//$router->get('/', Products::class . '::index');
//


// BASIC REQUIRED OPERATIONS

//$router->get('/getAllProducts', $products->get_all_products());
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

