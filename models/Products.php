<?php
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/ProductTypeModel.php';
require_once __DIR__ . '/../models/ProductPropertyModel.php';
require_once __DIR__ . '/../models/TypePropertiesModel.php';
require_once __DIR__ . '/../validation/Validator.php';

class Products
{
    public static function get_types_data()
    {
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

        echo response($product_types);
    }

    public static function get_all_products()
    {
        $products_json = json_decode(response((new ProductModel('protucts'))->show_all()));
        for ($i = 0; $i < count(($products_json)); $i++) {
            $product_properties = (self::get_property_info(((array)$products_json[$i])['id']));
            $products_json[$i]->type = $products_json[$i]->properties[0]['type'];
        }


        echo(json_encode($products_json));

    }

    public static function get_product_properties($sku)
    {
        return (new ProductPropertyModel('product_properties'))->get_product_properties($sku);
    }

    public static function get_type_properties($id)
    {
        return (new TypePropertiesModel('type_properties'))->get_type_properties($id);
    }

    public static function get_product_type($id)
    {
        return (new ProductTypeModel('product_properties'))->get_product_type($id);
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
        echo response((new ProductModel('protucts'))->find($_GET['SKU']));
    }

    public static function get_all_product_types()
    {
//        echo response((new ProductTypeModel('protuct_types'))->show_all());
        return response((new ProductTypeModel('protuct_types'))->show_all());
    }


    public static function get_all_type_properties()
    {
        return response((new TypePropertiesModel('type_properties'))->show_all());
    }


    public static function get_all_product_properties()
    {
        echo response((new ProductPropertyModel('product_properties'))->show_all());
    }

    public static function add_product_property()
    {
        $data = json_decode(file_get_contents("php://input"));

        echo (new ProductPropertyModel('product_properties'))->add_product_property(['product_id' => $data->product_id, 'type_id' => $data->type_id]);
    }


    public static function add_type_property()
    {
        $data = json_decode(file_get_contents("php://input"));

        echo (new TypePropertiesModel('type_properties'))->add_type_property($data->property, $data->unit, $data->type_id);
    }


    public static function add_new_product_type(array $inputs): void
    {
        $data = json_decode(file_get_contents("php://input"));

        echo (new ProductTypeModel('product_types'))->add_product_type($data->type_name);
    }

    public static function add_new_product(array $inputs): void
    {
        $data = json_decode(file_get_contents("php://input"));

        $newProductArr = array(
            ':SKU' => $data->SKU,
            ':name' => $data->name,
            ':price' => $data->price,
            ':type' => $data->type,
        );


        (new ProductModel('products'))->add_product($newProductArr, $data->productProperties);
    }

    public static function delete_products(array $data)
    {
        (new ProductModel('protucts'))->delete_products($data);
        return response(array('status' => 'success', 'message' => 'Deleted ' . count($data)));
    }


}