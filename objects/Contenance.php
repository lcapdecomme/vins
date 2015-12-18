<?php
class Contenance{
 
    // database connection and table name
    private $conn;
    private $table_name = "contenance";
 
    // object properties
    public $id;
    public $nom;
    public $volume;
    public $equivalence;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    // Liste de tous les cépages
    function read(){
        //select all data
        $query = "SELECT
                    id, nom, volume, equivalence
                FROM
                    " . $this->table_name . "
                ORDER BY
                    ordre"; 
 
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
 
        return $stmt;
    }


    function readAll($page, $from_record_num, $records_per_page){
    
        $query = "SELECT
                    id, nom, volume, equivalence 
                FROM
                    " . $this->table_name  . "
                ORDER BY
                    nom ASC
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