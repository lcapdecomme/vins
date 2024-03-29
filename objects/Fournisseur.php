<?php


class Fournisseur{
 
    // database connection and table name
    private $conn;
    private $table_name = "fournisseur";
 
    // object properties
    public $id;
    public $nom;
    public $adresse;
    public $cp;
    public $ville;
    public $telFixe;
    public $telPortable;
    public $mail;
    public $url;
    public $id_utilisateur;
    public $error; 
  
    public function __construct($db){
        $this->conn = $db;
    }
 

    // Retourne l'objet pour un ID donne
    function read()
    {
      try {
        // Requete pour retrouver un objet pour un ID donné
        $query = "select * from fournisseur where id=:id LIMIT 0, 1";
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        if ($stmt->rowCount()>0) {
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          extract($row);
          $this->id=$id;
          $this->nom=$nom;
          $this->adresse=$adresse;
          $this->cp=$cp;
          $this->ville=$ville;
          $this->telFixe=$telFixe;
          $this->telPortable=$telPortable;
          $this->mail=$mail;
          $this->url=$url;
          $this->id_utilisateur=$id_utilisateur;
          return true;
        }
      }
      catch(PDOException $exception) {
        $this->error =  $exception->getMessage();
      }
      return false;
    }

   
    // Retourne tous les objets pour un utilisateur
    function readAll()
    {
      // Requete pour retrouver tous les objets dans l'ordre d'insertion en base
      $query = "select * from fournisseur where id_utilisateur=:id_utilisateur order by nom asc";
      $stmt = $this->conn->prepare( $query );
      $stmt->bindParam(':id_utilisateur', $this->id_utilisateur);
      $stmt->execute();
      return $stmt;
    }

    // Add storage
    function create(){
         try {
            $query= "insert into fournisseur (nom, adresse, cp, ville, id_utilisateur, telFixe, telPortable, mail, url) 
                    values ( :nom, :adresse, :cp, :ville, :id_utilisateur, :telFixe, :telPortable, :mail, :url)";
            $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':adresse', $this->adresse);
        $stmt->bindParam(':cp', $this->cp);
        $stmt->bindParam(':ville', $this->ville);
        $stmt->bindParam(':telFixe', $this->telFixe);
        $stmt->bindParam(':telPortable', $this->telPortable);
        $stmt->bindParam(':mail', $this->mail);
        $stmt->bindParam(':url', $this->url);
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
        $query = "update fournisseur set
                    nom = :nom,
                    adresse = :adresse,
                    cp = :cp,
                    ville = :ville,
                    telFixe = :telFixe,
                    telPortable = :telPortable,
                    mail = :mail,
                    url = :url
                WHERE
                    id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':adresse', $this->adresse);
        $stmt->bindParam(':cp', $this->cp);
        $stmt->bindParam(':ville', $this->ville);
        $stmt->bindParam(':telFixe', $this->telFixe);
        $stmt->bindParam(':telPortable', $this->telPortable);
        $stmt->bindParam(':mail', $this->mail);
        $stmt->bindParam(':url', $this->url);
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
        $query = "delete from fournisseur where 
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