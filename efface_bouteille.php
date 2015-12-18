<?php
// check if value was posted
if($_POST){
 
    // include database and object file
    include_once 'config/database.php';
    include_once 'objects/Bouteille.php';
 
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
 
    // prepare bouteille object
    $bouteille = new Bouteille($db);
 
    // set bouteille id to be deleted
    $bouteille->id = $_POST['id'];
 
    // delete the bouteille
    if($bouteille->delete()){
        echo "Ok";
    }
     else{
        echo "Pb";
 
    }
}
?>