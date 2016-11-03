<?php
class Utilisateur{
 
    // database connection and table name
    private $conn;
    private $table_name = "utilisateur";
 
    // object properties
    public $id;
    public $pseudo;
    public $nom;
    public $mdp;
    public $mail;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    // add user
    function add(){
 
        // to get time-stamp for 'created' field
        $this->getTimestamp();
        // If null ....
        if (!is_numeric($this->nom)) {
            return false;
        }
        if (!is_numeric($this->mdp)) {
            return false;
        }
 
        try {
        //write query
        $query = "INSERT INTO `" . $this->table_name . "` (pseudo, nom, mdp, mail, ajout) 
                        values (:pseudo, :nom, :mdp, :mail, :ajout)";
 
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pseudo', $this->pseudo);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':mdp', $this->mdp);
        $stmt->bindParam(':mail', $this->mail);
        $stmt->bindParam(':ajout', $this->timestamp);

        if ($stmt->execute()) {
            return true;
        }   else{
            return false;
        }

        }catch(PDOException $exception){
            echo "Create : " . $this->host . " : " . $exception->getMessage();
        }
 
    }

    function check() {
        $query = "SELECT id, pseudo
            FROM {$this->table_name}    
            WHERE nom = :nom
            AND   mdp = :mdp";       
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':mdp', $this->mdp);
        $stmt->execute();         
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id = $row['id'];
        $this->pseudo = $row['pseudo'];
        if($this->id){
            return true;
        }else{
            return false;
        }
    }

    // update the user
    function update(){
        // If null ....
        // If null ....
        if (!is_numeric($this->nom)) {
            return false;
        }
        if (!is_numeric($this->mdp)) {
            return false;
        }
 
        $query = "UPDATE " . $this->table_name . " SET
                    pseudo = :pseudo,
                    nom = :nom,
                    mdp = :mdp,
                    mail = :mail
                WHERE
                    id = :id";
     
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pseudo', $this->pseudo);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':mdp', $this->mdp);
        $stmt->bindParam(':mail', $this->mail);
     
        // execute the query
        if($stmt->execute()){
            return true;
        }else{
            print_r($stmt->errorInfo());
            return false;
        }
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