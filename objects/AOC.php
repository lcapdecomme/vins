<?php
class AOC{
 
    // database connection and table name
    private $conn;
    private $table_name = "aoc";
 
    // object properties
    public $id;
    public $appellation;
    public $region;
    public $sousdivision;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    // Liste de tous les cépages
    function read(){
        //select all data
        $query = "SELECT
                    id, appellation, region, sousdivision
                FROM
                    " . $this->table_name . "
                ORDER BY
                    appellation"; 
 
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
 
        return $stmt;
    }

    // Libellé d'un cépage pour un id donné
    function readName(){
         
        $query = "SELECT appellation, region FROM " . $this->table_name . " WHERE id = ? limit 0,1";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
     
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
         
        $this->appellation = $row['appellation'];
        $this->region = $row['region'];
    }


    function readAll($page, $from_record_num, $records_per_page){
    
        $query = "SELECT
                    id, appellation, region, sousdivision 
                FROM
                    " . $this->table_name  . "
                ORDER BY
                    appellation ASC
                LIMIT
                    {$from_record_num}, {$records_per_page}";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
     
        return $stmt;
    }

    // used for paging products
    public function countAll(){
     
        $query = "SELECT id FROM " . $this->table_name . "";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
     
        $num = $stmt->rowCount();
     
        return $num;
    }


}
?>