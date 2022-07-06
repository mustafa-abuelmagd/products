<?php

class Database {
    private $host ='192.168.1.9:4306';
    private $user ='root';
    private $password = 'mysqlserver';
    private $dbname = 'products';
    private $port = 4306;
    private $conn;




    public function connect(): PDO
    {

        $this->conn = null ;

        try{
            $this->conn = new PDO( 'mysql:host='. $this->host .';dbname=' . $this->dbname , $this->user , $this->password);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//        echo 'database error';


        } catch(PDOException $e){
            echo 'Connection Error: '. $e->getMessage();
        }


        return $this->conn; 
    }

}






?>