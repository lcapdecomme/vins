<?php
class Bouteille{
 
    // database connection and table name
    private $conn;
    private $table_name = "bouteille";
    private $table_name_conso = "consommation";
    private $table_name_utilisateur = "utilisateur";
    private $table_name_emplacement = "emplacement";
    private $table_name_aoc = "aoc";
    private $table_name_type = "type";
    private $table_name_contenance = "contenance";
    private $table_name_cepage = "cepage";
 
    // object properties
    public $id;
    public $nom;
    public $quantite;
    public $prixachat;
    public $prixestime;
    // Annee achat, millésime et apogée
    public $achat;
    public $millesime;
    public $apogee;
    public $commentaire;
    public $id_cepage;
    public $id_contenance;
    public $id_aoc;
    public $id_type;
    public $id_emplacement;
    public $id_utilisateur;
    public $timestamp;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    // create product
    function create(){
 
        // to get time-stamp for 'created' field
        $this->getTimestamp();
        // " => '
        $this->nom= str_replace('"',"'", $this->nom);
        // If null ....
        if (!is_numeric($this->prixachat)) {
            $this->prixachat=0;
        }
        if (!is_numeric($this->prixestime)) {
            $this->prixestime=0;
        }
        if (!is_numeric($this->id_cepage)) {
            $this->id_cepage=0;
        }
        if (!is_numeric($this->id_aoc)) {
            $this->id_aoc=0;
        }
        if (!is_numeric($this->id_emplacement)) {
            $this->id_emplacement=0;
        }
 
        try {

        //write query
        $query = "INSERT INTO `" . $this->table_name . "` (nom, quantite, achat, prixachat, prixestime, millesime, apogee, 
                        commentaire, id_contenance, id_cepage, id_aoc, id_type, id_emplacement, id_utilisateur, ajout) 
                        values (:nom, :quantite, :achat, :prixachat, :prixestime, :millesime, :apogee, 
                        :commentaire, :id_contenance, :id_cepage, :id_aoc, :id_type, :id_emplacement, :id_utilisateur, :ajout)";
 
        $stmt = $this->conn->prepare($query);
     
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':quantite', $this->quantite);
        $stmt->bindParam(':prixachat', $this->prixachat);
        $stmt->bindParam(':prixestime', $this->prixestime);
        $stmt->bindParam(':millesime', $this->millesime);
        $stmt->bindParam(':achat', $this->achat );
        $stmt->bindParam(':apogee', $this->apogee);
        $stmt->bindParam(':commentaire', $this->commentaire);
        $stmt->bindParam(':id_contenance', $this->id_contenance);
        $stmt->bindParam(':id_cepage', $this->id_cepage);
        $stmt->bindParam(':id_aoc', $this->id_aoc);
        $stmt->bindParam(':id_type', $this->id_type);
        $stmt->bindParam(':id_emplacement', $this->id_emplacement);
        $stmt->bindParam(':id_utilisateur', $this->id_utilisateur);
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

    function readAll(){
       if (isset($_SESSION['id_utilisateur'])){
            $query = " SELECT b.id, b.nom as nomb, millesime, apogee, id_contenance, id_aoc, id_emplacement, id_cepage, id_type, b.id_utilisateur, prixachat, prixestime, achat, 
                        quantite, commentaire, ajout, e.lieu as lieu, a.appellation as appellation, a.region as region, t.libelle as type_vin, c.nom as type_contenance, p.nom as nom_cepage
                        FROM {$this->table_name} b
                        LEFT JOIN {$this->table_name_emplacement} e
                        ON e.id = b.id_emplacement
                        LEFT JOIN {$this->table_name_aoc} a
                        ON a.id = b.id_aoc
                        LEFT JOIN {$this->table_name_type} t
                        ON t.id = b.id_type
                        LEFT JOIN {$this->table_name_contenance} c
                        ON c.id = b.id_contenance
                        LEFT JOIN {$this->table_name_cepage} p
                        ON p.id = b.id_cepage
                        WHERE b.id_utilisateur = ?" ;
        }else {
            $query = " SELECT b.id, b.nom as nomb, millesime, apogee, id_contenance, id_aoc, id_emplacement, id_cepage, id_type, b.id_utilisateur, prixachat, prixestime, achat, 
                        quantite, commentaire, b.ajout, u.nom as nomu, e.lieu as lieu, a.appellation as appellation, a.region as region, t.libelle as type_vin, c.nom as type_contenance, p.nom as nom_cepage
                        FROM {$this->table_name} b
                        LEFT JOIN {$this->table_name_emplacement} e
                        ON e.id = b.id_emplacement
                        LEFT JOIN {$this->table_name_aoc} a
                        ON a.id = b.id_aoc
                        LEFT JOIN {$this->table_name_type} t
                        ON t.id = b.id_type
                        LEFT JOIN {$this->table_name_contenance} c
                        ON c.id = b.id_contenance
                        LEFT JOIN {$this->table_name_cepage} p
                        ON p.id = b.id_cepage
                        INNER JOIN {$this->table_name_utilisateur} u
                        ON b.id_utilisateur = u.id" ;
        }
       try {
            $stmt = $this->conn->prepare( $query );
            if (isset($_SESSION['id_utilisateur'])){
                $stmt->bindParam(1, $_SESSION['id_utilisateur']);
            }        
            $stmt->execute();
         
            return $stmt;
        }catch(PDOException $exception){
                echo "Create : " . $this->host . " : " . $exception->getMessage();
        }
        return null;
    }


    function update(){
        // If null ....
        if (!is_numeric($this->prixachat)) {
            $this->prixachat=0;
        }
        if (!is_numeric($this->prixestime)) {
            $this->prixestime=0;
        }
        if (!is_numeric($this->id_cepage)) {
            $this->id_cepage=0;
        }
        if (!is_numeric($this->id_aoc)) {
            $this->id_aoc=0;
        }
        if (!is_numeric($this->id_emplacement)) {
            $this->id_emplacement=0;
        }
        $query = "UPDATE " . $this->table_name . " SET
                    nom = :nom,
                    quantite = :quantite,
                    prixachat = :prixachat,
                    prixestime = :prixestime,
                    millesime = :millesime,
                    achat = :achat,
                    apogee = :apogee,
                    commentaire = :commentaire,
                    id_contenance  = :id_contenance,
                    id_cepage  = :id_cepage,
                    id_aoc  = :id_aoc,
                    id_type  = :id_type,
                    id_emplacement  = :id_emplacement,
                    id_utilisateur  = :id_utilisateur
                WHERE
                    id = :id";
     
        $stmt = $this->conn->prepare($query);


        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':quantite', $this->quantite);
        $stmt->bindParam(':prixachat', $this->prixachat);
        $stmt->bindParam(':prixestime', $this->prixestime);
        $stmt->bindParam(':millesime', $this->millesime);
        $stmt->bindParam(':achat', $this->achat );
        $stmt->bindParam(':apogee', $this->apogee);
        $stmt->bindParam(':commentaire', $this->commentaire);
        $stmt->bindParam(':id_contenance', $this->id_contenance);
        $stmt->bindParam(':id_cepage', $this->id_cepage);
        $stmt->bindParam(':id_aoc', $this->id_aoc);
        $stmt->bindParam(':id_type', $this->id_type);
        $stmt->bindParam(':id_emplacement', $this->id_emplacement);
        $stmt->bindParam(':id_utilisateur', $this->id_utilisateur);
        $stmt->bindParam(':id', $this->id);
     
        // execute the query
        if($stmt->execute()){
            return true;
        }else{
            print_r($stmt->errorInfo());
            return false;
        }
    }

    // Drink a bottle of this wine
    function drink($id, $qte) {

        $query = "UPDATE " . $this->table_name . " SET quantite = :quantite
                WHERE id = :id";
     
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quantite', $qte);
        $stmt->bindParam(':id', $id);
     
        // execute the query
        if($stmt->execute()){
            //write query consommation
            $query = "INSERT INTO `" . $this->table_name_conso . "` (id_bouteille, date) 
                        values (:id_bouteille, now())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_bouteille', $id);

            if ($stmt->execute()) {
                return true;
            }   
            else
            {
                return false;
            }
        }
        else{
            return false;
        }
    }

    // delete the wine
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

    // count wines
    public function countAll(){
        if (isset($_SESSION['id_utilisateur'])){
            $query = "SELECT count(*) as nb FROM " . $this->table_name. " WHERE id_utilisateur = ?" ;
        }else {
            $query = "SELECT count(*) as nb FROM " . $this->table_name;
        }
        $stmt = $this->conn->prepare( $query );
        if (isset($_SESSION['id_utilisateur'])){
            $stmt->bindParam(1, $_SESSION['id_utilisateur']);
        }
        $stmt->execute();
        $columns = $stmt->fetch();
        $nb = $columns['nb'];
        return $nb;
    }

    // sum of bottles
    public function sumAll(){
        if (isset($_SESSION['id_utilisateur'])){
            $query = "SELECT sum(quantite) total FROM " . $this->table_name. " WHERE id_utilisateur = ?" ;
        }else {
            $query = "SELECT sum(quantite) total FROM " . $this->table_name;
        }
        $stmt = $this->conn->prepare( $query );
        if (isset($_SESSION['id_utilisateur'])){
            $stmt->bindParam(1, $_SESSION['id_utilisateur']);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $sum = $row['total'];
        if ($sum==null) {
            $sum=0;
        }        
        return $sum;
    }

    function readOne(){
         
            $query = "SELECT
                        nom, quantite, achat, prixachat, prixestime, millesime, apogee, commentaire, id_contenance, id_cepage, id_aoc, 
                        id_type, id_emplacement, id_utilisateur, ajout
                    FROM
                        " . $this->table_name . "
                    WHERE
                        id = ?
                    LIMIT
                        0,1";
         
            $stmt = $this->conn->prepare( $query );
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
         
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
         
            $this->nom = $row['nom'];
            $this->quantite = $row['quantite'];
            $this->prixachat = $row['prixachat'];
            $this->prixestime = $row['prixestime'];
            $this->achat = $row['achat'];
            $this->millesime = $row['millesime'];
            $this->apogee = $row['apogee'];
            $this->commentaire = $row['commentaire'];
            $this->id_contenance = $row['id_contenance'];
            $this->id_cepage = $row['id_cepage'];
            $this->id_aoc = $row['id_aoc'];
            $this->id_type = $row['id_type'];
            $this->id_emplacement = $row['id_emplacement'];
            $this->id_utilisateur = $row['id_utilisateur'];
            $this->ajout = $row['ajout'];

        }


    // used for the 'created' field when creating a product
    function getTimestamp(){
        $this->timestamp = date('Y-m-d');
    }


}
?>