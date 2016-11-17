<?php
class Emplacement{
 
    // database connection and table name
    private $conn;
    private $table_name = "emplacement";
 
    // object properties
    public $id;
    public $lieu;
    public $id_utilisateur;
    public $error; 
  
    public function __construct($db){
        $this->conn = $db;
    }
 

    // Retourne l'objet pour un ID donne
    function read()
    {
      // Requete pour retrouver un objet pour un ID donné
      $query = "select * from emplacement where id=:id LIMIT 0, 1";
      $stmt = $this->conn->prepare( $query );
      $stmt->bindParam(':id', $this->id);
      $stmt->execute();
      if ($stmt->rowCount()>0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
        $this->id=$id;
      $this->lieu=$lieu;
      $this->id_utilisateur=$id_utilisateur;
        return true;
      }
      return false;
    }

   
    // Retourne tous les objets pour un utilisateur
    function readAll()
    {
      // Requete pour retrouver tous les objets dans l'ordre d'insertion en base
      $query = "select * from emplacement where id_utilisateur=:id_utilisateur  order by lieu asc";
      $stmt = $this->conn->prepare( $query );
      $stmt->bindParam(':id_utilisateur', $this->id_utilisateur);
      $stmt->execute();
      return $stmt;
    }

    // Add storage
    function create(){
         try {
          $query= "insert into emplacement (lieu,id_utilisateur) 
                  values ( :lieu, :id_utilisateur)";
         $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lieu', $this->lieu);
        $stmt->bindParam(':id_utilisateur', $this->id_utilisateur);
        if ($stmt->execute()) {
            return true;
        }   else{
            $errorInfo = $stmt->errorInfo();
            $this->error = $errorInfo[2];
            return false;
        }
        }
        catch(PDOException $exception) {
          $this->error =  $exception->getMessage();
          return false;
        }
    }

    // Update storage
    function update(){ 
        try {
        $query = "update emplacement set
                    lieu = :lieu
                WHERE
                    id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':lieu', $this->lieu);
        if ($stmt->execute()) {
            return true;
        }   else{
                $errorInfo = $stmt->errorInfo();
                $this->error = $errorInfo[2];
                return false;
        }
        }catch(PDOException $exception){
            $this->error =  $exception->getMessage();
            return false;
        }
    }

    // Delete a storage
    function delete(){ 
        try {
        $query = "delete from emplacement where 
                    id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        if ($stmt->execute()) {
            return true;
        }   else{
              $errorInfo = $stmt->errorInfo();
              $this->error = $errorInfo[2];
              return false;
        }
        }catch(PDOException $exception){
            $this->error =  $exception->getMessage();
            return false;
        }
    }
  
}
?>