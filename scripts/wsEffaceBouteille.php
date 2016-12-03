<?php 
session_start();
ini_set('zlib.output_compression_level', 6);
ob_start('ob_gzhandler');
header('Content-Type: application/json');
ini_set('display_errors', 1);
// Script REST pour boitre une bouteille
$json["resultat"]=false;
if ($_SESSION && isset($_SESSION['id_utilisateur']) )  {
    $debug=false;
    if (isset($_GET['debug']))
    {
      // Mode debug
      $debug=true;
    }
    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/Bouteille.php';
    $database = new Database();
    $db = $database->getConnection();
    // Des données ont-elles été envoyées par POST ? 
    if($_GET){
        // prepare bouteille object
        $bouteille = new Bouteille($db);
        // set bouteille id to be deleted
        $bouteille->id = $_GET['id'];
        if ($debug==true)
        {
            echo "Bouteille {$bouteille->id} <br>";
        }
       // delete the bouteille
        if($bouteille->delete()){
            $json["resultat"]=true;
        } else {
            $json["message"]=$bouteille->error;
        }
    }
} 
//Erreur si pas de session
else
{
  $json["message"]="Pas connecté !";
}
echo json_encode($json);
?>
