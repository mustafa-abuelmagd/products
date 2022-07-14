<?php

namespace Config;
use Dotenv\Dotenv;
use PDO;
use PDOException;

require_once __DIR__ . '/../../vendor/autoload.php';
$getEnv = Dotenv::createImmutable(__DIR__.'/../../');
$getEnv->load();

class Database {



    public function connect(): PDO
    {

        $conn = null ;
        $host = $_ENV["DB_HOST"];
        $user = $_ENV["DB_USERNAME"];
        $password = $_ENV["DB_PASSWORD"];
        $dbname = $_ENV["DB_DATABASE"];
        try{
            $conn = new PDO( 'mysql:host='. $host .';dbname=' . $dbname, $user, $password);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        } catch(PDOException $e){
            echo 'Connection Error: '. $e->getMessage();
        }


        return $conn;
    }

}






