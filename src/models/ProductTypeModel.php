<?php

namespace Models;
use mysqli_sql_exception;
use PDO;

require_once __DIR__ . '/../../vendor/autoload.php';

class ProductTypeModel extends QueryBuilder
{

    public string $table;
    public string $name = '';
    public string $type = '' ;
    function __construct($table_name)
    {
        parent::__construct($table_name);

    }

    public function show_all(): bool|array
    {
        try {
            return $this->select(['*'], 'product_types')->bind()->fetchAll(PDO::FETCH_ASSOC);
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception($e);

        }
    }

    public function get_product_type($id): bool|array
    {
        try {
            return $this->select(['type_name'], 'product_types')->where('id', '=', $id)->bind()->fetchAll(PDO::FETCH_ASSOC);
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception($e);

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
            throw new mysqli_sql_exception($e);

        }
    }

    public function add_product_type(string $data , string $separator): bool
    {
        try {
            if (!($this->find_by_name($data)) != null) {
                return  $this->general_insert('product_types')->prepareStmt()->bindParams2($data, $separator)->executeStmt();

            } else {
                throw new mysqli_sql_exception();
            }
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception($e);

        }


    }


}
