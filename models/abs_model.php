<?php

require_once __DIR__ . '/../config/Database.php';

abstract class QueryBuilder
{

    public $conn;
//    public $tabdddddd = 'roducts';

    public $table;
    public $query = '';
    public $data = array();
    public $stmt;


    public function __construct($table_name)
    {
        $database = (new Database())->connect();
        $this->conn = $database;
        $this->table = $table_name;

    }


    public function select(array $cols, string $from)
    {
//        echo 'table name is ' . $this->table;
        $this->query = 'SELECT ' . implode(',', $cols) . ' FROM ' . $from;

        return $this;
    }


    public function where(string $col, string $op, string $val)
    {
        $val = '"' . $val . '"';
        $this->query .= ' WHERE ' . $col . ' ' . $op . ' ' . $val;
//        echo  ' and the value is ' . gettype($val);
        return $this;
    }

    public function limit(array $numbers)
    {
        $this->query .= ' LIMIT ' . implode(',', $numbers);
        return $this;
    }

    public function insert(string $in)
    {
        $this->query = 'INSERT INTO ' . $in . ' SET sku = :SKU , name = :name , price = :price , type = :type  ';
        return $this;
    }

    public function general_insert(string $in)
    {
        $this->query = 'INSERT INTO ' . $in . ' SET type_name = :type_name ';
        return $this;
    }

    public function indert_type_property(string $in)
    {
//        echo count($data);
        $this->query = 'INSERT INTO ' . $in . ' SET  property = :property , unit = :unit , type_id = :type_id ';
        return $this;
    }

    public function indert_product_property(string $in)
    {
//        echo count($data);
        $this->query = 'INSERT INTO ' . $in . ' SET  product_id = :product_id , type_id = :type_id  , property_id = :property_id , value = :value';
        return $this;
    }


    function field(string $field_name, string $value)
    {
        $this->query .= "  $field_name  = :$field_name ";
        $this->stmt = $this->conn->prepare($this->query);
//        echo 'llllool '.$this->stmt->bindValue(":$field_name", "$value");

        return $this;
    }

    public function bindValues(string $field_name, string $value)
    {
        echo $field_name . ' => ' . json_encode($value);
//        $this->prpareStmt();

        $this->stmt->bindValue("$field_name", json_encode($value));

        return $this;
    }


    public function bindParams(array $data)
    {
        foreach ($data as $key => $val) {


            if (str_contains($this->query, $key)) {
                if (str_contains(json_encode($val), "{")) {
                    $this->stmt->bindValue("$key", json_encode($val));

                } else {
                    $this->stmt->bindValue("$key", "$val");

                }
            }
        }
        return $this;
    }


    public function bindParams2($data)
    {
        $this->stmt->bindValue(":type_name", $data);
        return $this;
    }

    public function bindParams3(array $data)
    {
        echo $this->stmt->queryString;
        foreach ($data as $key => $val) {
            echo $key . '=>' . $val;
//            $this->stmt->bindValue(":type_name", $data );

        }
        return $this;
    }


    public function prpareStmt()
    {
        $this->stmt = $this->conn->prepare($this->query);
        return $this;
    }


    public function executeStmt()
    {
        try {
            if ($this->stmt->execute() ==1 ) {
                return true;
            }
            else {
                return false;
            }
        } catch (PDOException $e) {
            $e->getMessage();
            return \http\Exception::class;
        }
    }

    public function bind()
    {
        $this->stmt = $this->conn->prepare($this->query);

        $this->data = array();
//        echo $this->stmt->queryString ."\n";

        if (!$this->stmt->execute()) {
            return null;
        } else {

            return $this->stmt;
        }

//        try {
//            return $this->stmt->execute();
//        } catch (exception $e) {
//            echo " an exception  $e";
//        }

//        return $this->stmt;

    }

    public function bind2()
    {
        $result = $this->stmt->fetch(PDO::FETCH_ASSOC);
        echo "  " . $this->stmt->queryString;
        return $result;

    }


    public function get()
    {

        echo $this->bind()->fetchAll(PDO::FETCH_ASSOC);


    }

    public function delete(string $from, string $col, string $val)
    {
        $this->query = " DELETE FROM " . "$from ";
        $this->where($col, '=', $val);
        return $this->bind();
    }


}


?>