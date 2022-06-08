<?php

require_once __DIR__ . '/../models/abs_model.php';
require_once __DIR__ . '/../models/ProductPropertyModel.php';


class ProductModel extends QueryBuilder
{

    public $table;
    public $id;
    public $sku;
    public $name;
    public $price;
    public $type;
    public $properties;
    public $inputs;

    function __construct($table_name)
    {
        parent::__construct($table_name);

    }

    public function show_all()
    {
        return $this->select(['*'], 'products')->bind()->fetchAll(PDO::FETCH_ASSOC);
    }


//    public function get_single_product(string $SKU)
//    {
//        $result = $this->select(['*'])->where('id', '=', $SKU)->limit([0, 1])->bind();
//        $product = $result->fetch(PDO::FETCH_ASSOC);
//
//        $this->id = $product[':id'];
//        $this->SKU = $product[':SKU'];
//        $this->name = $product[':name'];
//        $this->price = $product[':price'];
//        $this->type = $product[':type'];
//        $this->properties = $product[':properties'];
//        $product_arr = array(
//            'id' => $this->id,
//            'name' => $this->name,
//            'price' => $this->price,
//            'type' => $this->type,
//            'properties' => $this->properties,
//        );
//
//        echo json_encode($product_arr);
//        return $product;
//    }


    public function find(string $SKU)
    {
        $result = $this->select(['*'], 'products')->where('SKU', '=', $SKU)->limit([1])->bind();
        if ($result == null) {
            return null;
        } else {
            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function add_product(array $data, array $productProperties)
    {
        $adding_new_product_result = $this->insert('products')->prpareStmt()->bindParams($data)->executeStmt();
        $added_product_id = $this->find($data[':SKU']);
        for ($i = 0 ; $i < count($productProperties) ; $i++){
            $productProperties[$i]->product_id = $added_product_id['id'];
        }
        $adding_product_properties_result = (new ProductPropertyModel('product_properties'))->add_product_property($productProperties);
        return $adding_new_product_result && $adding_product_properties_result;
    }

    public function delete_products(array $data)
    {
        foreach ($data as $product) {
            echo strval($product);
            $this->delete('products', 'id', $product);
        }
    }

    public function validate_SKU()
    {

        return (!preg_match('/\s/', $this->inputs[':SKU']) && ($this->find(strval($this->inputs[':SKU'])) == null) && (strlen($this->inputs[':SKU'] > 0)));
    }

    public function validate_Name()
    {
        return (!preg_match('/\s/', $this->inputs[':name']) && (strlen($this->inputs[':name'] > 0)));
    }

    public function validate_Price()
    {
//        echo " \n   the issue might be here   " . filter_var($this->inputs[':price'], FILTER_VALIDATE_FLOAT) . (floatval($this->inputs[':price'] > 0)) ."   hhhhhhhhhh \n ";
        return (filter_var($this->inputs[':price'], FILTER_VALIDATE_FLOAT) && (strlen($this->inputs[':price'] > 0)) && (floatval($this->inputs[':price'] > 0)));
    }

    public function validate_properties()
    {
        return (!preg_match('/\s/', $this->inputs[':properties']) && (strlen($this->inputs[':properties'] > 0)));
    }


    public function validate_type()
    {
        return (!preg_match('/\s/', $this->inputs[':type']) && (strlen($this->inputs[':type'] > 0)));
    }


}
