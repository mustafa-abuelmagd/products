<?php

require_once __DIR__ . '/../config/Database.php';

abstract class QueryBuilder
{

    public PDO $conn;
    public string $table = '';
    public string $query = '';
    public array $data = array();
    public $stmt;


    public function __construct($table_name)
    {
        $database = (new Database())->connect();
        $this->conn = $database;
        $this->table = $table_name;

    }


    public function select(array $cols, string $from): static
    {
        $this->query = 'SELECT ' . implode(',', $cols) . ' FROM ' . $from;
        return $this;
    }

    public function where(string $col, string $op, string $val): static
    {
        $val = '"' . $val . '"';
        $this->query .= ' WHERE ' . $col . ' ' . $op . ' ' . $val;
        return $this;
    }

    public function limit(array $numbers): static
    {
        $this->query .= ' LIMIT ' . implode(',', $numbers);
        return $this;
    }

    public function insert(string $in): static
    {
        $this->query = 'INSERT INTO ' . $in . ' SET sku = :SKU , name = :name , price = :price , type = :type  ';
        return $this;
    }

    public function general_insert(string $in): static
    {
        $this->query = 'INSERT INTO ' . $in . ' SET type_name = :type_name , `separator` = :separator ';
        return $this;
    }

    public function insert_type_property(string $in): static
    {
        $this->query = 'INSERT INTO ' . $in . ' SET  property = :property , unit = :unit , type_id = :type_id ';
        return $this;
    }

    public function insert_product_property(string $in): static
    {
        $this->query = 'INSERT INTO ' . $in . ' SET  product_id = :product_id , type_id = :type_id  , property_id = :property_id , value = :value';
        return $this;
    }


    function field(string $field_name, string $value): static
    {
        $this->query .= "  $field_name  = :$field_name ";
        $this->stmt = $this->conn->prepare($this->query);
        return $this;
    }

    public function bindValues(string $field_name, string $value): static
    {
        echo $field_name . ' => ' . json_encode($value);
        $this->stmt->bindValue("$field_name", json_encode($value));
        return $this;
    }


    public function bindParams(array $data): static
    {
        foreach ($data as $key => $val) {
            if (str_contains($this->query, $key)) {
                $this->stmt->bindValue("$key", "$val");
            }
        }
        return $this;
    }


    public function bindParams2($data, $separator): static
    {
        $this->stmt->bindValue(":type_name", $data);
        $this->stmt->bindValue(":separator", $separator);
        return $this;
    }

    public function bindParams3(array $data): static
    {
        echo $this->stmt->queryString;
        foreach ($data as $key => $val) {
            echo $key . '=>' . $val;
        }
        return $this;
    }


    public function prpareStmt(): static
    {
        $this->stmt = $this->conn->prepare($this->query);
        return $this;
    }


    public function executeStmt(): bool
    {
        try {
            if ($this->stmt->execute() == 1) {
                return true;
            } else {
                return false;
            }
        } catch (mysqli_sql_exception $e) {

            throw new mysqli_sql_exception();
        }
    }

    public function bind(): bool|PDOStatement|null
    {
        $this->stmt = $this->conn->prepare($this->query);
        $this->data = array();
        if (!$this->stmt->execute()) {
            return null;
        } else {

            return $this->stmt;
        }
    }

    public function bind2()
    {
        $result = $this->stmt->fetch(PDO::FETCH_ASSOC);
        echo "  " . $this->stmt->queryString;
        return $result;

    }


    public function get(): void
    {

        echo $this->bind()->fetchAll(PDO::FETCH_ASSOC);


    }

    public function delete(string $from, string $col, string $val): bool|PDOStatement|null
    {
        $this->query = " DELETE FROM " . "$from ";
        $this->where($col, '=', $val);
        return $this->bind();
    }


}


