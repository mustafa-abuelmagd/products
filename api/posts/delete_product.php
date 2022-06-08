<?php


// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control_allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control_allow-Methods,Authorization,X-Requested-With');

include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../models/ProductModel.php';
include_once __DIR__ . '/../../models/response.php';


// Instanciate DB & connect
$database = new Database();
$db = $database->connect();


// Instanciate blog post object
$book = new ProductModel('protucts');


// Get rwo posted data
$data = json_decode(file_get_contents("php://input"));

//var_dump( $data->products);

//foreach ($data->products as $product){
//    echo $product;
////            $this->delete('id' ,$product);
//}
$book->delete_products($data->products);

//echo  json_encode($data);
//$book->sku = $data->SKU;
//$book->name = $data->name;
//$book->price = $data->price;
//$book->type = $data->type;
//$book->properties = $data->properties;
//
//$newProductArr = array(
//    ':SKU' => $book->sku,
//    ':name' => $book->name,
//    ':price' => $book->price,
//    ':type' => $book->type,
//    ':properties' => $book->properties,
//);

// Create the actual post from the extracted data
//if($book->add_product($newProductArr)){
//    echo json_encode(
//        array(
//            'message'=> 'Post created'
//        )
//    );
//    response (array( 'message'=> 'Post created' ));
//echo json_encode(array('message' => 'Post created'));

//}else{
//    response (array( 'message' => 'Post not created'));

//    echo json_encode(
//        array(
//            'message' => 'Post not created'
//        )
//    );
//}



