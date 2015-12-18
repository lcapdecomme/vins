<?php
class Emplacement{
 
    // database connection and table name
    private $conn;
    private $table_name = "emplacement";
 
    // object properties
    public $id;
    public $lieu;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    // Liste de tous les cépages
    function read(){
        //select all data
        $query = "SELECT
                    id, lieu
                FROM
                    " . $this->table_name . "
                ORDER BY
                    lieu"; 
 
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
 
        return $stmt;
    }

    // Libellé d'un cépage pour un id donné
    function readName(){
         
        $query = "SELECT lieu FROM " . $this->table_name . " WHERE id = ? limit 0,1";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
     
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
         
        $this->lieu = $row['lieu'];
    }

  
}
?>