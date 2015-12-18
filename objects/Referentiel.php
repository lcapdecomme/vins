<?php
class Referentiel{
 
    // database connection and table name
    private $conn;
    private $table_name = "referentiel";
    private $table_name_sec = "type";
 
    // object properties
    public $id;
    public $nom;
    public $region;
    public $id_type;
 
    public function __construct($db){
        $this->conn = $db;
    }
 

    // create product
    function create(){
 
        // to get time-stamp for 'created' field
        $this->getTimestamp();
 
        try {

        //write query
        $query = "INSERT INTO `" . $this->table_name . "` (nom, region, id_type) 
                        values (:nom, :region, :id_type)";
 
        $stmt = $this->conn->prepare($query);
     
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':region', $this->region);
        $stmt->bindParam(':id_type', $this->id_type);

        if ($stmt->execute()) {
            return true;
        }   
        else {
            return false;
        }

        }catch(PDOException $exception){
            echo "Create Referentiel : " . $this->host . " : " . $exception->getMessage();
        }
 
    }

    function readLike($nom){
     
        $query = "SELECT id, nom 'label', region, id_type FROM " . $this->table_name . "
                WHERE nom like '" . $nom . "' ORDER BY nom ASC LIMIT 0, 10";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }

    function readOne($nom){
        $query = "SELECT nom, region, id_type FROM " . $this->table_name . "
                WHERE nom = '" . $nom . "' LIMIT 0,1";        
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->nom = $row['nom'];
        $this->region = $row['region'];
        $this->id_type = $row['id_type'];

        if ($this->nom == $nom) {
            return true;
        }   
        else {
            return false;
        }

    }


    // used for the 'created' field when creating a product
    function getTimestamp(){
        $this->timestamp = date('Y-m-d');
    }


    function readAll($page, $from_record_num, $records_per_page){
     
     
        $query = "SELECT
                    referentiel.id, nom, region, libelle
                FROM
                    " . $this->table_name  . ", " . $this->table_name_sec . "
                WHERE id_type=type.id 
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