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
        try {
            return $this->select(['*'], 'type_properties')->bind()->fetchAll(PDO::FETCH_ASSOC);
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception();

        }
    }

    public function get_type_properties($id)
    {
        try {
            return $this->select(['*'], 'type_properties')->where('id', '=', $id)->bind()->fetchAll(PDO::FETCH_ASSOC);
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception();

        }
    }

    public function find(string $id)
    {
        try {
            $result = $this->select(['*'], 'type_properties')->where('property', '=', $id)->limit([1])->bind();
            if ($result == null) {
                return false;
            } else {
                return $result->fetch(PDO::FETCH_ASSOC);
            }
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception();

        }
    }

    public function add_type_property(string $property, string $unit, string $type_id)
    {
        try {
            if (!$this->find($property) != null) {
                return $this->indert_type_property
                ('type_properties')->prpareStmt()->bindParams([":property" => $property, ":unit" => $unit, ":type_id" => $type_id])->executeStmt();

            } else {
                throw new mysqli_sql_exception();
            }

        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception();

        }
    }


}
