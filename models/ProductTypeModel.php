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
        try {
            return $this->select(['*'], 'product_types')->bind()->fetchAll(PDO::FETCH_ASSOC);
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception();

        }
    }

    public function get_product_type($id)
    {
        try {
            return $this->select(['type_name'], 'product_types')->where('id', '=', $id)->bind()->fetchAll(PDO::FETCH_ASSOC);
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception();

        }
    }

    public function find(string $id)
    {
        try {
            $result = $this->select(['*'], 'product_types')->where('id', '=', $id)->limit([1])->bind();
            if ($result == null) {
                return false;
            } else {
                return $result->fetch(PDO::FETCH_ASSOC);
            }
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception();

        }
    }

    public function find_by_name(string $type_name)
    {
        try {
            $result = $this->select(['*'], 'product_types')->where('type_name', '=', $type_name)->limit([1])->bind();
            if ($result == null) {
                return false;
            } else {
                return $result->fetch(PDO::FETCH_ASSOC);
            }
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception();

        }
    }

    public function add_product_type(string $data , string $separator)
    {
        try {
//            $adding_product_type_result =
            if (!($this->find_by_name($data)) != null) {
                echo $this->general_insert('product_types')->prpareStmt()->bindParams2($data, $separator)->executeStmt();

            } else {
                throw new mysqli_sql_exception();
            }
//            echo '$adding_product_type_result   ' . $adding_product_type_result . "\n";
//            $added_type_id = $this->find_by_name($data)['id'];
//            echo '$added_type_id   ' . json_encode($added_type_id['id']) . "\n";


        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception();

        }


    }


}
