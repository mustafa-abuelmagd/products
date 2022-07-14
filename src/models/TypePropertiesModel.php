<?php


namespace Models;
use mysqli_sql_exception;
use PDO;

require_once __DIR__ . '/../../vendor/autoload.php';

class TypePropertiesModel extends QueryBuilder
{

    public string $table;

    function __construct($table_name)
    {
        parent::__construct($table_name);

    }

    public function show_all(): bool|array
    {
        try {
            return $this->select(['*'], 'type_properties')->bind()->fetchAll(PDO::FETCH_ASSOC);
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception($e);

        }
    }

    public function get_type_properties($id): bool|array
    {
        try {
            return $this->select(['*'], 'type_properties')->where('id', '=', $id)->bind()->fetchAll(PDO::FETCH_ASSOC);
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception($e);

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
            throw new mysqli_sql_exception($e);

        }
    }

    public function add_type_property(string $property, string $unit, string $type_id): bool
    {
        try {
            if (!$this->find($property) != null) {
                return $this->insert_type_property
                ('type_properties')->prepareStmt()->bindParams([":property" => $property, ":unit" => $unit, ":type_id" => $type_id])->executeStmt();

            } else {
                throw new mysqli_sql_exception();
            }

        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception($e);

        }
    }


}
