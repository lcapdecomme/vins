<?php
class Type{
 
    // database connection and table name
    private $conn;
    private $table_name = "type";
 
    // object properties
    public $id;
    public $libelle;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    // Liste de tous les cépages
    function read(){
        //select all data
        $query = "SELECT
                    id, libelle
                FROM
                    " . $this->table_name . "
                ORDER BY
                    id"; 
 
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
 
        return $stmt;
    }

    // Libellé d'un cépage pour un id donné
    function readName(){
         
        $query = "SELECT libelle FROM " . $this->table_name . " WHERE id = ? limit 0,1";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
     
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
         
        $this->libelle = $row['libelle'];
    }

}
?>