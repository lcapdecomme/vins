<?php
class Database{
 
    // specify your own database credentials
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;


    public function __construct(){
        $this->host = "localhost";
        $this->db_name = "vins";
        $this->username = "vins";
        $this->password = "vins";
    }
 

    // get the database connection
    public function getConnection(){

 
        $this->conn = null;
 
        try{
            if ($this->host=="localhost") {
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            }
            else {
               $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
             }

        }catch(PDOException $exception){
            echo "Connection error : " . $this->host . " : " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>