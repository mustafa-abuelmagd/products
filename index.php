<?php

require_once __DIR__ . '/../products/vendor/autoload.php';
include_once './config/Router.php';
require_once __DIR__ . '/models/Products.php';

define('DirName', getcwd());

$router = new Router();

// BASIC REQUIRED OPERATIONS

$router->get('/getAllProducts', Products::class . '::get_all_products');
$router->post('/addProduct', Products::class . '::add_new_product');
$router->delete('/deleteProducts', Products::class . '::delete_products');
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

