<?php

require_once __DIR__ . '/vendor/autoload.php';

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

//$router->get('/getAllProducts', $products->getAllProducts());
$router->get('/getAllProducts',
  Products::class . '::getAllProducts');

$router->post('/addProduct',
  Products::class . '::addNewProduct');

$router->delete('/deleteProducts',
 Products::class . '::deleteProducts');
//$router->get('/getProduct', Products::class . '::getProduct');

// $router->get('/getAllProductProperties', Products::class . '::getAllProductProperties');


// APPLICATION DATA, TYPES WITH THEIR PROPERTIES
$router->get('/getApplicationData',
  Products::class . '::getTypesData');


// ADDITIONAL OPERATIONS FOR SCALABILITY
// PRODUCT TYPE OPERATIONS
$router->get('/getAllProductTypes',
  Products::class . '::getAllProductTypes');
$router->post('/addProductType',
  Products::class . '::addNewProductType');


// PRODUCT PROPERTY OPERATIONS
$router->get('/getAllTypeProperties',
  Products::class . '::getAllTypeProperties');


$router->post('/addTypeProperty',
  Products::class . '::addTypeProperty');


$router->addNotFoundHandler('/', function () {
  echo 'Home lol';
});

$router->run();

