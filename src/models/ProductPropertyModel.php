<?php

namespace Models;

use mysqli_sql_exception;
use PDO;

class ProductPropertyModel extends QueryBuilder
{
    public string $table;
    public string $name = '';
    public string $type = '';

    public function __construct($table_name)
    {
        parent::__construct($table_name);
    }

    public function showAll(): bool|array
    {
        try {
            return $this->select(['*'], 'product_properties')
            ->bind()
            ->fetchAll(PDO::FETCH_ASSOC);
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception($e);
        }
    }


    public function getProductProperties($product_id): bool|array
    {
        try {
            return $this->select(['*'], 'product_properties')
            ->where('product_id', '=', $product_id)
            ->bind()
            ->fetchAll(PDO::FETCH_ASSOC);
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception($e);
        }
    }

    public function addProductProperty(array $data): bool
    {
        try {
            foreach ($data as $key => $val) {
                $this->insertProductProperty('product_properties')
                ->prepareStmt()
                ->bindParams((array)$val)
                ->executeStmt();
            }
            return true;
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception($e);
        }
    }
}
