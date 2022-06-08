<?php

require_once __DIR__ . '/../models/abs_model.php';

class ProductPropertyModel extends QueryBuilder
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
        return $this->select(['*'], 'product_properties')->bind()->fetchAll(PDO::FETCH_ASSOC);
    }


    public function get_product_properties( $product_id )
    {
        return $this->select(['*'], 'product_properties')->where('product_id' , '=' , $product_id)->bind()->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_product_assoc_properties( $product_id , $property_id)
    {
        return $this->select(['*'], 'product_properties')->where('product_id' , '=' , $product_id)->where('property_id' , '=' , $property_id)->bind()->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(string $id)
    {
        $result = $this->select(['*'], 'product_types')->where('id', '=', $id)->limit([1])->bind();
        if ($result == null) {
            return false;
        } else {
            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function add_product_property(array $data)
    {


        try {
            foreach ($data as $key => $val) {
                echo '        json encode =   ' . gettype((array)$val). '        ' . "\n";

                $this->indert_product_property('product_properties')->prpareStmt()->bindParams((array)$val)->executeStmt();

            }

            return true;
        } catch (error $e) {
            echo "Error occured", $e;
            return false;
        }

    }


}
