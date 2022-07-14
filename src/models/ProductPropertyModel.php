<?php


namespace Models;
use mysqli_sql_exception;
use PDO;

require_once __DIR__ . '/../../vendor/autoload.php';


class ProductPropertyModel extends QueryBuilder
{

    public string $table;
    public string $name = '';
    public string $type = '';

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

    public function add_product_property(array $data): bool
    {
        try {
            foreach ($data as $key => $val) {
                $this->insert_product_property('product_properties')->prepareStmt()->bindParams((array)$val)->executeStmt();
            }
            return true;
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception($e);
        }

    }


}
