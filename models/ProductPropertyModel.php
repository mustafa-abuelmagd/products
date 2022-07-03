<?php

require_once __DIR__ . '/../models/abs_model.php';
require_once __DIR__ . '/../models/ServerLogger.php';

class ProductPropertyModel extends QueryBuilder
{

    public string $table;
    public int $id = 0 ;
    public string $sku = '';
    public string $name = '';
    public int $price = 0;
    public string $type = '';
    public array $properties = [];
    public array $inputs =[];

    function __construct($table_name)
    {
        parent::__construct($table_name);

    }

    public function show_all(): bool|array
    {
        try {
            return $this->select(['*'], 'product_properties')->bind()->fetchAll(PDO::FETCH_ASSOC);
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception($e);

        }
    }


    public function get_product_properties($product_id): bool|array
    {
        try {
            return $this->select(['*'], 'product_properties')->where('product_id', '=', $product_id)->bind()->fetchAll(PDO::FETCH_ASSOC);
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception($e);

        }
    }

    public function get_product_assoc_properties($product_id, $property_id): bool|array
    {
        try {
            return $this->select(['*'], 'product_properties')->where('product_id', '=', $product_id)->where('property_id', '=', $property_id)->bind()->fetchAll(PDO::FETCH_ASSOC);
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception($e);

        }
    }

    public function find(string $id)
    {
        try{
            $result = $this->select(['*'], 'product_types')->where('id', '=', $id)->limit([1])->bind();
            if ($result == null) {
                return false;
            } else {
                return $result->fetch(PDO::FETCH_ASSOC);
            }
        }catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception($e);

        }

    }

    public function add_product_property(array $data): bool
    {
        try {
            foreach ($data as $key => $val) {
                $this->indert_product_property('product_properties')->prpareStmt()->bindParams((array)$val)->executeStmt();
            }
            return true;
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception();
        }

    }


}
