<?php


header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../models/ProductModel.php';

$book = new ProductModel('protucts');


$result = $book->show_all();


// Row count
$row_count = $result->rowCount();
//echo $row_count;


// Check if any products
if ($row_count > 0) {
    $products_arr = array();
    $products_arr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $product_item = array(
            'id' => $id,
            'name' => $name,
            'SKU' => $SKU,
            'type' => $type,
            'price' => $price,
            'properties' => $properties,
            'created_at' => $created_at,
            'form' => $form,

        );

        // Push the item to the products array
        array_push($products_arr['data'], $product_item);
    }
    // Convert to json and send
    echo json_encode($products_arr);
} else json_encode(array(
    'message' => 'No Products added to the list.'
));


