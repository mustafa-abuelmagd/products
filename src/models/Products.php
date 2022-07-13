<?php

namespace Models;
require_once __DIR__ . '/../../vendor/autoload.php';

use mysqli_sql_exception;
//use Models\response;
//use Utils\;



class Products
{
    // BASIC REQUIRED OPERATIONS
    public static function get_all_products(): void
    {
        try {
            $products_json = (new ProductModel('products'))->show_all();
            for ($i = 0; $i < count(($products_json)); $i++) {
                $products_json[$i]['properties'] = self::get_property_info($products_json[$i]['id']);
                $products_json[$i]['type'] = $products_json[$i]['properties'][0]['type'];
            }
            echo sendResponse(200, json_encode($products_json));
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(500, ["Status" => "Failed", 'message' => $e->getMessage()]);
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

            $ProductProperties = array();

            for ($i = 0; $i < count($data->productProperties); $i++) {
                $product_property = array();
                foreach ($data->productProperties[$i] as $key => $value) {
                    $product_property[":$key"] = $value;

                }
                $ProductProperties[] = $product_property;

            }
            (new ProductModel('products'))->add_product($newProductArr, $ProductProperties);


            echo sendResponse(201, json_encode(["message" => "Success"]));
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(422, ["Status " => "Product already exists", 'message' => $e->getMessage()]);
        }
    }

    public static function delete_products(): void
    {
        try {
            $data = json_decode(file_get_contents("php://input"));
            if ((new ProductModel('products'))->delete_products($data)) {
                echo "result is true";
                echo sendResponse(200, "Products Deleted!");
            } else {
                echo sendResponse(422, "An error occurred");
            }
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(422, "An error occurred");
        }
    }


    // ADDITIONAL OPERATIONS FOR SCALABILITY
    public static function get_types_data()
    {
        try {
            $product_types = (new ProductTypeModel('product_types'))->show_all();
            $type_properties = (new TypePropertiesModel('type_properties'))->show_all();

            for ($i = 0; $i < count($product_types); $i++) {
                $product_types[$i]['properties'] = array();
                for ($j = 0; $j < count($type_properties); $j++) {
                    if ($product_types[$i]['id'] == $type_properties[$j]['type_id']) {
                        $product_types[$i]['properties'][] = $type_properties[$j];

                    }
                }
            }
            echo sendResponse(200, json_encode($product_types));

        } catch (mysqli_sql_exception $e) {
            echo sendResponse(500, ["Status" => "Failed", "message" => $e->getMessage()]);
        }
    }


    public static function get_product_properties($sku): void
    {
        try {
            echo sendResponse(200, new ProductPropertyModel('product_properties'))->get_product_properties($sku);
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(500, ["Status" => "Failed", "message" => $e->getMessage()]);
        }
    }


    public static function get_type_properties($id)
    {
        try {
            echo sendResponse(200, (new TypePropertiesModel('type_properties'))->get_type_properties($id));
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(500, ["Status" => "Failed", "message" => $e->getMessage()]);
        }
    }


    public static function get_product_type($id)
    {
        try {
            echo sendResponse(200, (new ProductTypeModel('product_properties'))->get_product_type($id));
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(500, ["Status" => "Failed", "message" => $e->getMessage()]);


        }
    }

    public static function get_property_info($product_id): array
    {
        $property_info = (new ProductPropertyModel('product_properties'))->get_product_properties($product_id);
        $product_properties_info = array();
        foreach ($property_info as $key => $val) {
            $type_name = (new ProductTypeModel('product_types'))->get_product_type($val['type_id'])[0]['type_name'];
            $property = (new TypePropertiesModel('type_properties'))->get_type_properties($val['property_id'])[0];
            $property_name = $property['property'];
            $property_unit = $property['unit'];


            $product_properties_info[] = array(
                "id" => $property_info[0]['id'],
                "product_id" => $val['product_id'],
                "type" => $type_name,
                "property" => $property_name,
                "value" => $val['value'],
                "unit" => $property_unit,
            );
        }
        return $product_properties_info;
    }


    public static function get_product(): void
    {
        echo sendResponse((new ProductModel('protects'))->find($_GET['SKU']));
    }

    public static function get_all_product_types(): void
    {
        echo sendResponse(200, json_encode((new ProductTypeModel('product_types'))->show_all()));
    }


    public static function get_all_type_properties(): void
    {
        try {
            echo sendResponse(200, json_encode((new TypePropertiesModel('type_properties'))->show_all()));
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(500, ["Status" => "Failed", "Error" => $e]);
        }
    }


    public static function get_all_product_properties(): void
    {
        try {
            echo sendResponse(200, json_encode((new ProductPropertyModel('product_properties'))->show_all()));
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(500, json_encode(["Status" => "Failed", "Error" => $e]));
        }
    }


    public static function add_product_property(): void
    {
        try {
            $data = json_decode(file_get_contents("php://input"));

            echo sendResponse(200, json_encode((new ProductPropertyModel('product_properties'))->add_product_property(['product_id' => $data->product_id, 'type_id' => $data->type_id]))
            );
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(500, json_encode(["Status" => "Failed", "Error" => $e]));

        }

    }


    public static function add_type_property(): void
    {
        try {
            $data = json_decode(file_get_contents("php://input"));

            echo sendResponse(201, (new TypePropertiesModel('type_properties'))->add_type_property($data->property, $data->unit, $data->type_id));
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(500, json_encode(["Status" => "Failed", "Error" => $e]));
        }
    }


    public static function add_new_product_type(): void
    {
        try {
            $data = json_decode(file_get_contents("php://input"));
            echo sendResponse(201, json_encode((new ProductTypeModel('product_types'))->add_product_type($data->type_name, $data->separator)));
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(500, json_encode(["Status" => "Failed", "Error" => $e]));
        }
    }
}