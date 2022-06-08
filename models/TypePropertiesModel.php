<?php

require_once __DIR__ . '/../models/abs_model.php';

class TypePropertiesModel extends QueryBuilder
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
        return $this->select(['*'], 'type_properties')->bind()->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_type_properties($id)
    {
        return $this->select(['*'], 'type_properties')->where('id' , '=' , $id )->bind()->fetchAll(PDO::FETCH_ASSOC);
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

    public function add_type_property(string $property , string $unit  , string $type_id)
    {
        return $this->indert_type_property
        ('type_properties' )->prpareStmt()->bindParams([ ":property" => $property , ":unit" => $unit ,":type_id" => $type_id])->executeStmt();

    }


}
