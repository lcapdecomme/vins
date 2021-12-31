<?php
include_once "../config/constants.php";
class Bouteille{
 
    // database connection and table name
    private $conn;
    private $table_name = "bouteille";
    private $table_name_conso = "consommation";
    private $table_name_utilisateur = "utilisateur";
    private $table_name_emplacement = "emplacement";
    private $table_name_fournisseur = "fournisseur";
    private $table_name_aoc = "aoc";
    private $table_name_type = "type";
    private $table_name_contenance = "contenance";
 
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
    public $nomCepage;
    public $id_contenance;
    public $id_aoc;
    public $id_type;
    public $nomPhoto;
    public $nomPhoto2;
    public $id_emplacement;
    public $id_fournisseur;
    public $id_utilisateur;
    public $timestamp;
    public $error;
    public $empl_x;
    public $empl_y;

 
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
        if (!is_numeric($this->id_aoc)) {
            $this->id_aoc=0;
        }
        if (!is_numeric($this->id_emplacement)) {
            $this->id_emplacement=0;
        }
        if (!is_numeric($this->id_fournisseur)) {
            $this->id_fournisseur=0;
        }
        if (!is_numeric($this->id_type)) {
            $this->id_type=0;
        }
 
        try {
            //write query
            $query = "INSERT INTO `" . $this->table_name . "` (nom, quantite, achat, prixachat, prixestime, millesime, apogee, 
                            commentaire, id_contenance, nomCepage, id_aoc, id_type, id_emplacement, id_fournisseur, id_utilisateur, ajout, empl_x, empl_y) 
                            values (:nom, :quantite, :achat, :prixachat, :prixestime, :millesime, :apogee, 
                            :commentaire, :id_contenance, :nomCepage, :id_aoc, :id_type, :id_emplacement, :id_fournisseur, :id_utilisateur, :ajout, :empl_x, :empl_y)";
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
            $stmt->bindParam(':nomCepage', $this->nomCepage);
            $stmt->bindParam(':id_aoc', $this->id_aoc);
            $stmt->bindParam(':id_type', $this->id_type);
            $stmt->bindParam(':id_emplacement', $this->id_emplacement);
            $stmt->bindParam(':id_fournisseur', $this->id_fournisseur);
            $stmt->bindParam(':id_utilisateur', $this->id_utilisateur);
            $stmt->bindParam(':ajout', $this->timestamp);
            $stmt->bindParam(':empl_x', $this->empl_x);
            $stmt->bindParam(':empl_y', $this->empl_y);

            if ($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                return true;
            } else {
                $errorInfo = $stmt->errorInfo();
                $this->error = $errorInfo[1] .":".$errorInfo[2];
                return false;
            }
        } catch(PDOException $exception) {
            echo "Create : " . $this->host . " : " . $exception->getMessage();
        }
 
    }

    function readAll(){
       if (isset($_SESSION['id_utilisateur'])){
            $query = " SELECT b.id, b.nom as nomb, millesime, apogee, id_contenance, id_aoc, id_emplacement, id_fournisseur, nomCepage, id_type, b.id_utilisateur, 
                        prixachat, prixestime, achat, quantite, commentaire, nomPhoto, nomPhoto2, ajout, e.lieu as lieu, a.appellation as appellation, 
                        a.region as region, t.libelle as type_vin, c.nom as type_contenance, c.volume as type_volume, empl_x, empl_y, 
                        CONCAT(f.nom,' ',f.cp,' ',f.ville) as fournisseur 
                        FROM {$this->table_name} b
                        LEFT JOIN {$this->table_name_emplacement} e
                        ON e.id = b.id_emplacement
                        LEFT JOIN {$this->table_name_fournisseur} f
                        ON f.id = b.id_fournisseur
                        LEFT JOIN {$this->table_name_aoc} a
                        ON a.id = b.id_aoc
                        LEFT JOIN {$this->table_name_type} t
                        ON t.id = b.id_type
                        LEFT JOIN {$this->table_name_contenance} c
                        ON c.id = b.id_contenance
                        WHERE b.id_utilisateur = ?" ;
        }else {
            $query = " SELECT b.id, b.nom as nomb, millesime, apogee, id_contenance, id_aoc, id_emplacement, id_fournisseur, nomCepage, id_type, b.id_utilisateur, 
                        prixachat, prixestime, achat, quantite, commentaire, nomPhoto, nomPhoto2, b.ajout, u.nom as nomu, e.lieu as lieu, a.appellation as appellation, 
                        a.region as region, t.libelle as type_vin, c.nom as type_contenance, c.volume as type_volume, empl_x, empl_y,
                        CONCAT(f.nom,' ',f.cp,' ',f.ville) as fournisseur 
                        FROM {$this->table_name} b
                        LEFT JOIN {$this->table_name_emplacement} e
                        ON e.id = b.id_emplacement
                        LEFT JOIN {$this->table_name_fournisseur} f
                        ON f.id = b.id_fournisseur
                        LEFT JOIN {$this->table_name_aoc} a
                        ON a.id = b.id_aoc
                        LEFT JOIN {$this->table_name_type} t
                        ON t.id = b.id_type
                        LEFT JOIN {$this->table_name_contenance} c
                        ON c.id = b.id_contenance
                        INNER JOIN {$this->table_name_utilisateur} u
                        ON b.id_utilisateur = u.id" ;
        }
        // Recherche dans la zone commentaire et le nom de la bouteille 
        if (isset($this->commentaire)) {
            $query = $query . " and (lower(b.nom) like '%" . strtolower($this->commentaire) . "%'"
                            . " or lower(b.commentaire) like '%" . strtolower($this->commentaire) . "%')";
        }
       try {
            $stmt = $this->conn->prepare( $query );
            if (isset($_SESSION['id_utilisateur'])){
                $stmt->bindParam(1, $_SESSION['id_utilisateur']);
            }        
            if($stmt->execute()){
                return $stmt;
            }
            else{
                $errorInfo = $stmt->errorInfo();
                $this->error = $errorInfo[2];
                return false;
            }
                    
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
        if (!is_numeric($this->id_aoc)) {
            $this->id_aoc=0;
        }
        if (!is_numeric($this->id_emplacement)) {
            $this->id_emplacement=0;
        }
        if (!is_numeric($this->id_fournisseur)) {
            $this->id_fournisseur=0;
        }
        if (!is_numeric($this->id_type)) {
            $this->id_type=0;
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
                    nomCepage  = :nomCepage,
                    id_aoc  = :id_aoc,
                    id_type  = :id_type,
                    nomPhoto = :nomPhoto,
                    nomPhoto2 = :nomPhoto2,
                    id_emplacement  = :id_emplacement,
                    id_fournisseur  = :id_fournisseur,
                    id_utilisateur  = :id_utilisateur,
                    empl_x  = :empl_x,
                    empl_y  = :empl_y
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
        $stmt->bindParam(':nomCepage', $this->nomCepage);
        $stmt->bindParam(':id_aoc', $this->id_aoc);
        $stmt->bindParam(':nomPhoto', $this->nomPhoto);
        $stmt->bindParam(':nomPhoto2', $this->nomPhoto2);
        $stmt->bindParam(':id_type', $this->id_type);
        $stmt->bindParam(':id_emplacement', $this->id_emplacement);
        $stmt->bindParam(':id_fournisseur', $this->id_fournisseur);
        $stmt->bindParam(':id_utilisateur', $this->id_utilisateur);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':empl_x', $this->empl_x);
        $stmt->bindParam(':empl_y', $this->empl_y);
     
        // execute the query
        if($stmt->execute()){
            return true;
        }else{
            $errorInfo = $stmt->errorInfo();
            $this->error = $errorInfo[2];
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
            return true;
        }
        else{
            $errorInfo = $stmt->errorInfo();
            $this->error = $errorInfo[2];
            return false;
        }
    }

    // delete a picture
    function deletePhotos($file){
        if (isset($file) && strlen($file)>0 && file_exists($file) ) {
            unlink($file);
        }
    }
    // delete the wine
    function delete(){
        // Read for get picture names
        $this->readOne();
        if (isset($this->nomPhoto) && strlen($this->nomPhoto)>0) {
            $this->deletePhotos("..".DIRECTORY_SEPARATOR.UPLOAD_DIRECTORY.DIRECTORY_SEPARATOR.$this->nomPhoto);
        }
        if (isset($this->nomPhoto2) && strlen($this->nomPhoto2)>0) {
            $this->deletePhotos("..".DIRECTORY_SEPARATOR.UPLOAD_DIRECTORY.DIRECTORY_SEPARATOR.$this->nomPhoto2);
        }
        // Delete in database
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        if($result = $stmt->execute()){
            return true;
        }else{
            $errorInfo = $stmt->errorInfo();
            $this->error = $errorInfo[2];
            return false;
        }
    }

    // count wines
    public function countAll(){
        if (isset($_SESSION['id_utilisateur'])){
            $query = "SELECT count(*) as nb FROM " . $this->table_name. " WHERE id_utilisateur = ?" ;
        }else {
            $query = "SELECT count(*) as nb FROM " . $this->table_name. " where 1";
        }
        // Recherche dans la zone commentaire
        if (isset($this->commentaire)) {
            $query = $query . " and (lower(nom) like '%" . strtolower($this->commentaire) . "%'"
                            . " or lower(commentaire) like '%" . strtolower($this->commentaire) . "%')";
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
            $query = "SELECT sum(quantite) total FROM " . $this->table_name. " where 1";
        }
        // Recherche dans la zone commentaire
        if (isset($this->commentaire)) {
            $query = $query . " and (lower(nom) like '%" . strtolower($this->commentaire) . "%'"
                            . " or lower(commentaire) like '%" . strtolower($this->commentaire) . "%')";
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
                nom, quantite, achat, prixachat, prixestime, millesime, apogee, commentaire, id_contenance, nomCepage, id_aoc, 
                id_type, id_emplacement, id_fournisseur, id_utilisateur, nomPhoto, nomPhoto2, ajout, empl_x, empl_y
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
        $this->nomCepage = $row['nomCepage'];
        $this->id_aoc = $row['id_aoc'];
        $this->nomPhoto = $row['nomPhoto'];
        $this->nomPhoto2 = $row['nomPhoto2'];
        $this->id_type = $row['id_type'];
        $this->id_emplacement = $row['id_emplacement'];
        $this->id_fournisseur = $row['id_fournisseur'];
        $this->id_utilisateur = $row['id_utilisateur'];
        $this->ajout = $row['ajout'];
        $this->empl_x = $row['empl_x'];
        $this->empl_y = $row['empl_y'];

    }

    // used for the 'created' field when creating a product
    function getTimestamp(){
        $this->timestamp = date('Y-m-d');
    }
    
}
?>