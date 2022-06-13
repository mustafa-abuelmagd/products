<?php
header("Access-Control-Allow-Origin: *");
//echo "llllllllererlelrlerlelrlerler";
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/ProductTypeModel.php';
require_once __DIR__ . '/../models/ProductPropertyModel.php';
require_once __DIR__ . '/../models/TypePropertiesModel.php';
require_once __DIR__ . '/../models/response.php';

class Products
{


//    public static function index()
//    {
//        require_once __DIR__ . '/../src/frontend/index.html';
//    }


    // BASIC REQUIRED OPERATIONS

    public static function get_all_products()
    {
        try {
            $products_json = (new ProductModel('products'))->show_all();
            for ($i = 0; $i < count(($products_json)); $i++) {
                $products_json[$i]['properties'] = self::get_property_info($products_json[$i]['id']);
                $products_json[$i]['type'] = $products_json[$i]['properties'][0]['type'];
            }
            sendResponse(200, json_encode($products_json));
        } catch (mysqli_sql_exception $e) {
            sendResponse(500, ["Status" => "Failed"]);

        }
    }

    public static function add_new_product(array $inputs): void
    {
        try {
            $data = json_decode(file_get_contents("php://input"));

            $newProductArr = array(
                ':SKU' => $data->SKU,
                ':name' => $data->name,
                ':price' => $data->price,
                ':type' => $data->type,
            );
            ((new ProductModel('products'))->add_product($newProductArr, $data->productProperties));

            (sendResponse(201, json_encode(["message" => "Success"])));
        } catch (mysqli_sql_exception $e) {
            sendResponse(422, "Product already exists");
        }
    }

    public static function delete_products()
    {
        try {

            $data = json_decode(file_get_contents("php://input"));
            $result = (new ProductModel('protucts'))->delete_products($data->products);
            if ($result == true) {
                echo "result is true";
                sendResponse(200, "Products Deleted!");

            } else {
                sendResponse(422, "An error occurred");

            }

        } catch (mysqli_sql_exception $e) {
            sendResponse(422, "An error occurred");
        }

    }


    // ADDITIONAL OPERATIONS FOR SCALABILITY

    public static function get_types_data()
    {
        try {
            $product_types = (new ProductTypeModel('protuct_types'))->show_all();
            $type_properties = (new TypePropertiesModel('type_properties'))->show_all();

            for ($i = 0; $i < count($product_types); $i++) {
                $product_types[$i]['properties'] = array();
                for ($j = 0; $j < count($type_properties); $j++) {
                    if ($product_types[$i]['id'] == $type_properties[$j]['type_id']) {
//                    echo "\n " . (json_encode($product_types[$i])) . "  fsdafdfasdfa " . json_encode($type_properties[$j]);
                        array_push($product_types[$i]['properties'], $type_properties[$j]);
                    }
                }
            }
            sendResponse(200, json_encode($product_types));

        } catch (mysqli_sql_exception $e) {
            sendResponse(500, ["Status" => "Failed"]);

        }

    }


    public static function get_product_properties($sku)
    {
        try {
            sendResponse(200, new ProductPropertyModel('product_properties'))->get_product_properties($sku);
        } catch (mysqli_sql_exception $e) {
            sendResponse(500, ["Status" => "Failed"]);


        }
    }


    public static function get_type_properties($id)
    {
        try {
            return (new TypePropertiesModel('type_properties'))->get_type_properties($id);
        } catch (mysqli_sql_exception $e) {
            sendResponse(500, ["Status" => "Failed"]);


        }
    }

    public static function get_product_type($id)
    {
        try {
            return (new ProductTypeModel('product_properties'))->get_product_type($id);
        } catch (mysqli_sql_exception $e) {
            sendResponse(500, ["Status" => "Failed"]);


        }
    }

    public static function get_property_info($product_id)
    {
        $property_info = (new ProductPropertyModel('product_properties'))->get_product_properties($product_id);

        $product_properties_info = array();

        foreach ($property_info as $key => $val) {
            $type_name = (new ProductTypeModel('product_types'))->get_product_type($property_info[$key]['type_id'])[0]['type_name'];
            $property = (new TypePropertiesModel('type_properties'))->get_type_properties($property_info[$key]['property_id'])[0];
            $propery_name = $property['property'];
            $propery_unit = $property['unit'];

            array_push($product_properties_info, array(
                "id" => $property_info[0]['id'],
                "product_id" => $property_info[0]['product_id'],
                "type" => $type_name,
                "property" => $propery_name,
                "value" => $property_info[0]['value'],
                "unit" => $propery_unit,
            ));


        }

        return $product_properties_info;

    }

    public static function get_product()
    {
//        echo json_encode( $_GET['SKU']);
        echo sendResponse((new ProductModel('protucts'))->find($_GET['SKU']));
    }

    public static function get_all_product_types()
    {
        sendResponse(200, json_encode((new ProductTypeModel('protuct_types'))->show_all()));
    }


    public static function get_all_type_properties()
    {
        try {
            sendResponse(200, json_encode((new TypePropertiesModel('type_properties'))->show_all()));
        } catch (mysqli_sql_exception $e) {
            sendResponse(500, ["Status" => "Failed"]);


        }
    }


    public static function get_all_product_properties()
    {
        try {
            sendResponse(200, json_encode((new ProductPropertyModel('product_properties'))->show_all()));
        } catch (mysqli_sql_exception $e) {
            sendResponse(500, json_encode(["Status" => "Failed"]));


        }
    }

    public static function add_product_property()
    {
        try {
            $data = json_decode(file_get_contents("php://input"));

            sendResponse(200, json_encode((new ProductPropertyModel('product_properties'))->add_product_property(['product_id' => $data->product_id, 'type_id' => $data->type_id]))
            );
        } catch (mysqli_sql_exception $e) {
            sendResponse(500, json_encode(["Status" => "Failed"]));

        }

    }


    public static function add_type_property()
    {
        try {
            $data = json_decode(file_get_contents("php://input"));

            sendResponse(201, (new TypePropertiesModel('type_properties'))->add_type_property($data->property, $data->unit, $data->type_id));
        } catch (mysqli_sql_exception $e) {
            sendResponse(500, json_encode(["Status" => "Failed"]));

        }

    }


    public static function add_new_product_type(): void
    {
        try {
            $data = json_decode(file_get_contents("php://input"));

            sendResponse(201, json_encode((new ProductTypeModel('product_types'))->add_product_type($data->type_name, $data->separator)));
        } catch (mysqli_sql_exception $e) {
            sendResponse(500, json_encode(["Status" => "Failed"]));
        }
    }


}