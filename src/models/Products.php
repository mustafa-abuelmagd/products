<?php

namespace Models;

//require_once __DIR__ . '/../../vendor/autoload.php';
use mysqli_sql_exception;

class Products
{
    // BASIC REQUIRED OPERATIONS
    public static function getAllProducts(): void
    {
        try {
            $products_json = (new ProductModel('products'))->showAll();

            for ($i = 0; $i < count(($products_json)); $i++) {
                $products_json[$i]['properties'] = self::getPropertyInfo($products_json[$i]['id']);
                $products_json[$i]['type'] = $products_json[$i]['properties'][0]['type'];
            }


            echo sendResponse(200, json_encode($products_json));
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(500, ["Status" => "Failed", 'message' => $e->getMessage()]);
        }
    }


    public static function addNewProduct(array $inputs): void
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
            (new ProductModel('products'))
            ->addProduct($newProductArr, $ProductProperties);


            echo sendResponse(
                201,
                json_encode(["message" => "Success"])
            );
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(
                422,
                ["Status " => "Product already exists", 'message' => $e->getMessage()]
            );
        }
    }

    public static function deleteProducts(): void
    {
        try {
            $data = json_decode(file_get_contents("php://input"));
            if ((new ProductModel('products'))->deleteProducts($data->products)) {
                echo sendResponse(
                    200,
                    "Products Deleted!"
                );
            } else {
                echo sendResponse(
                    422,
                    "An error occurred"
                );
            }
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(
                422,
                "An error occurred"
            );
        }
    }


    // ADDITIONAL OPERATIONS FOR SCALABILITY
    public static function getTypesData()
    {
        try {
            $product_types =
            (new ProductTypeModel('product_types'))->showAll();


            $type_properties =
            (new TypePropertiesModel('type_properties'))->showAll();

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
            echo sendResponse(
                500,
                ["Status" => "Failed", "message" => $e->getMessage()]
            );
        }
    }


    public static function getProductProperties($sku): void
    {
        try {
            echo sendResponse(
                200,
                new ProductPropertyModel('product_properties')
            )
            ->getProductProperties($sku);
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(
                500,
                ["Status" => "Failed", "message" => $e->getMessage()]
            );
        }
    }


    public static function getTypeProperties($id): void
    {
        try {
            echo sendResponse(
                200,
                (new TypePropertiesModel('type_properties'))
                ->getTypeProperties($id)
            );
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(
                500,
                ["Status" => "Failed", "message" => $e->getMessage()]
            );
        }
    }


    public static function getProductType($id): void
    {
        try {
            echo sendResponse(
                200,
                (new ProductTypeModel('product_properties'))
                ->getProductType($id)
            );
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(
                500,
                ["Status" => "Failed", "message" => $e->getMessage()]
            );
        }
    }

    public static function getPropertyInfo($product_id): array
    {
        $property_info =
        (new ProductPropertyModel('product_properties'))
        ->getProductProperties($product_id);

        $product_properties_info = array();
        foreach ($property_info as $key => $val) {
            $type_name =
            (new ProductTypeModel('product_types'))
            ->getProductType($val['type_id'])[0]['type_name'];

            $property =
            (new TypePropertiesModel('type_properties'))
            ->getTypeProperties($val['property_id'])[0];

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


    public static function getProduct(): void
    {
        echo sendResponse(
            200,
            (new ProductModel('protects'))->find($_GET['SKU'])
        );
    }

    public static function getAllProductTypes(): void
    {
        echo sendResponse(
            200,
            json_encode((new ProductTypeModel('product_types'))
            ->showAll())
        );
    }


    public static function getAllTypeProperties(): void
    {
        try {
            echo sendResponse(
                200,
                json_encode((new TypePropertiesModel('type_properties'))
                ->showAll())
            );
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(
                500,
                ["Status" => "Failed", "Error" => $e]
            );
        }
    }


    public static function getAllProductProperties(): void
    {
        try {
            echo sendResponse(
                200,
                json_encode((new ProductPropertyModel('product_properties'))
                ->showAll())
            );
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(
                500,
                json_encode(["Status" => "Failed", "Error" => $e])
            );
        }
    }


    public static function addProductProperty(): void
    {
        try {
            $data = json_decode(file_get_contents("php://input"));

            echo sendResponse(
                200,
                json_encode((new ProductPropertyModel('product_properties'))
                ->addProductProperty(['product_id' => $data->product_id, 'type_id' => $data->type_id]))
            );
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(
                500,
                json_encode(["Status" => "Failed", "Error" => $e])
            );
        }
    }


    public static function addTypeProperty(): void
    {
        try {
            $data = json_decode(file_get_contents("php://input"));

            echo sendResponse(
                201,
                (new TypePropertiesModel('type_properties'))
                ->addTypeProperty($data->property, $data->unit, $data->type_id)
            );
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(
                500,
                json_encode(["Status" => "Failed", "Error" => $e])
            );
        }
    }


    public static function addNewProductType(): void
    {
        try {
            $data = json_decode(file_get_contents("php://input"));
            echo sendResponse(
                201,
                json_encode((new ProductTypeModel('product_types'))
                ->addProductType($data->type_name, $data->separator))
            );
        } catch (mysqli_sql_exception $e) {
            echo sendResponse(
                500,
                json_encode(["Status" => "Failed", "Error" => $e])
            );
        }
    }
}
