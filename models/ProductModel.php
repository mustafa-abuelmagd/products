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
        try {
            $result = $this->select(['*'], 'products')->bind()->fetchAll(PDO::FETCH_ASSOC);

            return $result;

        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception();

        }


    }


    public function find(string $SKU)
    {
        try {
            $result = $this->select(['*'], 'products')->where('SKU', '=', $SKU)->limit([1])->bind();
            if ($result == null) {
                return null;
            } else {
                return $result->fetch(PDO::FETCH_ASSOC);
            }
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception();

        }
    }

    public function add_product(array $data, array $productProperties)
    {
        try {
            $adding_new_product_result = $this->insert('products')->prpareStmt()->bindParams($data)->executeStmt();
            if ($adding_new_product_result == 1) {
                $added_product_id = $this->find($data[':SKU']);
                for ($i = 0; $i < count($productProperties); $i++) {
                    $productProperties[$i][":product_id"] = $added_product_id['id'];
                }
                $adding_product_properties_result = (new ProductPropertyModel('product_properties'))->add_product_property($productProperties);
                return $adding_new_product_result && $adding_product_properties_result;
            } else {
                throw new mysqli_sql_exception();
            }
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception();
        }
    }

    public function delete_products(array $data)
    {

        try {

            foreach ($data as $product) {
                if ($this->find($product) != null) {

                    echo ($this->find($product) == null) . "  ";

                    $this->delete('products', 'sku', $product);
//                    return true;

                } else {
//                    return false;
                }

            }

        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception();

        }
    }


}
