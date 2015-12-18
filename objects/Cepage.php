<?php
class Cepage{
 
    // database connection and table name
    private $conn;
    private $table_name = "cepage";
 
    // object properties
    public $id;
    public $nom;
    public $superficie;
    public $couleur;
    public $origine;
    public $annee;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    // Liste de tous les cépages
    function read(){
        //select all data
        $query = "SELECT
                    id, nom
                FROM
                    " . $this->table_name . "
                ORDER BY
                    nom"; 
 
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
 
        return $stmt;
    }

    // Libellé d'un cépage pour un id donné
    function readName(){
         
        $query = "SELECT nom FROM " . $this->table_name . " WHERE id = ? limit 0,1";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
     
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
         
        $this->nom = $row['nom'];
    }


    function readAll($page, $from_record_num, $records_per_page){
    
        $query = "SELECT
                    id, nom, couleur, origine, superficie, annee 
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