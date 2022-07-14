<?php

namespace Models;
use mysqli_sql_exception;
use PDO;

require_once __DIR__ . '/../../vendor/autoload.php';


class ProductModel extends QueryBuilder
{

    public string $table;
    public int $id =0 ;
    public string $sku = '';
    public string $name = '';
    public string $price = '';
    public string $type ='';
    public array $properties = [];

    function __construct($table_name)
    {
        parent::__construct($table_name);

    }

    public function show_all(): bool|array
    {
        try {
            return $this->select(['*'], 'products')->bind()->fetchAll(PDO::FETCH_ASSOC);
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception($e);
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
            throw new mysqli_sql_exception($e);

        }
    }

    public function add_product(array $data, array $productProperties): bool
    {
        try {
            $adding_new_product_result = $this->insert('products')->prepareStmt()->bindParams($data)->executeStmt();
            if ($adding_new_product_result == 1) {
                $added_product_id = $this->find($data[':SKU']);
                for ($i = 0; $i < count($productProperties); $i++) {
                    $productProperties[$i][":product_id"] = $added_product_id['id'];
                }
                return (new ProductPropertyModel('product_properties'))->add_product_property($productProperties);
            } else {
                throw new mysqli_sql_exception();
            }
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception($e);
        }
    }

    public function delete_products(array $data): bool
    {

        try {

            foreach ($data as $product) {
                if ($this->find($product) != null) {

                    echo ($this->find($product) == null) . "  ";

                    $this->delete('products', 'sku', $product);
                } else {
                    throw new mysqli_sql_exception();
                }
            }
            return true;
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception($e);

        }
    }


}
