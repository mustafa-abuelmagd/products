<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../models/ProductModel.php';

// Instanciate DB and connect
$database = new Database();
$db = $database->connect();

// Instanciate product object
$book = new ProductModel('protucts');

// Product ID
$book->id = isset($_GET['id']) ? $_GET['id'] : die();


$book->get_single_product();


