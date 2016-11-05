<?php
class Utilisateur{
 
    // database connection and table name
    private $conn;
    private $table_name = "utilisateur";
 
    // object properties
    public $id;
    public $nom;
    public $mdp;
    public $mail;
    public $error;

    public function __construct($db){
        $this->conn = $db;
    }
 
    // add user
    function addUser(){
 
        // to get time-stamp for 'created' field
        $this->getTimestamp();
        // If null ....
        if (!isset($this->nom)) {
            $this->error = "Login obligatoire";
            return false;
        }
        if (!isset($this->mdp)) {
            $this->error = "Mot de passe obligatoire";
            return false;
        }
        // Mail valid ? 
        if (!filter_var($this->mail, FILTER_VALIDATE_EMAIL)) {
            $this->error = "Mail invalide";
            return false;
        }
        // User exist ? 
        if ($this->checkUserNom()) {
            $this->error = "Utilisateur existant";
            return false;
        }

        try {
            //write query
            $query = "INSERT INTO `" . $this->table_name . "` (nom, mdp, mail, ajout) 
                            values (:nom, :mdp, :mail, :ajout)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nom', $this->nom);
            $stmt->bindParam(':mdp', $this->mdp);
            $stmt->bindParam(':mail', $this->mail);
            $stmt->bindParam(':ajout', $this->timestamp);

            if ($stmt->execute()) {
                return $this->checkUserPassword();
            }   else {
                $errorInfo = $stmt->errorInfo();
                $this->error = $errorInfo[2];
                return false;
            }
        } catch(PDOException $exception) {
            echo "Create : " . $this->host . " : " . $exception->getMessage();
        }
 
    }

    function read() {
        $query = "SELECT nom, mdp, mail, ajout
            FROM {$this->table_name}    
            WHERE id = :id";       
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();         
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->nom = $row['nom'];
        $this->mdp = $row['mdp'];
        $this->mail = $row['mail'];
        $this->ajout = $row['ajout'];
        if($this->nom) {
            return true;
        } else {
            $this->error = "Utilisateur inconnu";
            return false;
        }
    }

   function checkUserNom() {
        $query = "SELECT id
            FROM {$this->table_name}    
            WHERE nom = :nom";       
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':nom', $this->nom);
        $stmt->execute();         
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id = $row['id'];
        if($this->id) {
            return true;
        } else {
            return false;
        }
    }

   function checkUserId() {
        $query = "SELECT id
            FROM {$this->table_name}    
            WHERE id = :id";       
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();         
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id = $row['id'];
        if($this->id) {
            return true;
        } else {
            return false;
        }
    }

    function checkUserPassword() {
        $query = "SELECT id, nom
            FROM {$this->table_name}    
            WHERE nom = :nom
            AND   mdp = :mdp";       
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':mdp', $this->mdp);
        $stmt->execute();         
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id = $row['id'];
        $this->nom = $row['nom'];
        if($this->id){
            return true;
        }else{
            $this->error = "Utilisateur ou mot de passe incorrect";
            return false;
        }
    }

    // update the user
    function update(){
       // If null ....
        if (!isset($this->nom)) {
            $this->error = "Login obligatoire";
            return false;
        }
        if (!isset($this->id)) {
            $this->error = "Utilisateur inconnu";
            return false;
        }
        if (!isset($this->mdp)) {
            $this->error = "Mot de passe obligatoire";
            return false;
        }
        // Mail valid ? 
        if (!filter_var($this->mail, FILTER_VALIDATE_EMAIL)) {
            $this->error = "Mail invalide";
            return false;
        }
        // User exist ? 
        if (!$this->checkUserId()) {
            $this->error = "Utilisateur inexistant";
            return false;
        }

        $query = "UPDATE " . $this->table_name . " SET
                    nom = :nom,
                    mdp = :mdp,
                    mail = :mail
                WHERE
                    id = :id";
     
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':mdp', $this->mdp);
        $stmt->bindParam(':mail', $this->mail);
        $stmt->bindParam(':id', $this->id);
     
        // execute the query
        if($stmt->execute()){
            return true;
        }else{
            $errorInfo = $stmt->errorInfo();
            $this->error = $errorInfo[2];
            return false;
        }
    }

    // count wines
    public function countAll(){
        $query = "SELECT count(*) as nb FROM " . $this->table_name;
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $columns = $stmt->fetch();
        $nb = $columns['nb'];
        return $nb;
    }

    // delete the user
    function delete(){
     
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
     
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
     
        if($result = $stmt->execute()){
            return true;
        }else{
            return false;
        }
    }


    // used for the 'created' field when creating a user
    function getTimestamp(){
        $this->timestamp = date('Y-m-d');
    }


}
?>