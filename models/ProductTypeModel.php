<?php

require_once __DIR__ . '/../models/abs_model.php';

class ProductTypeModel extends QueryBuilder
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
        return $this->select(['*'], 'product_types')->bind()->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_product_type($id)
    {
        return $this->select(['type_name'], 'product_types')->where('id' , '=' , $id)->bind()->fetchAll(PDO::FETCH_ASSOC);
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

    public function add_product_type(string $data)
    {
        return $this->general_insert('product_types')->prpareStmt()->bindParams2($data)->executeStmt();

    }


}
