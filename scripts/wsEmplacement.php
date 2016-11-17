<?php 
session_start();
ini_set('zlib.output_compression_level', 6);
ob_start('ob_gzhandler');
header('Content-Type: application/json');
// Script REST pour voir/modifier un objetemplacement
if ($_SESSION && isset($_SESSION['id_utilisateur']) )  {
    $debug=false;
    if (isset($_GET['debug']))
    {
      // Mode debug
      $debug=true;
    }
    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/emplacement.php';
    // instantie la base
    $database = new Database();
    $db = $database->getConnection();

    // Nouvel objet Emplacement
    $emplacement = new Emplacement($db);

    // Recherche (GET) -> op=R et id présent
    if ( isset($_GET['op']) && $_GET['op']=='R' && isset($_GET['id']) && strlen($_GET['id'])>0   )
    {
      $emplacement->id = $_GET['id'];
      if ($debug==true)
      {
        echo "Recherche de l'objet Emplacement {$emplacement->id}<br>";
      }
      if ($emplacement->read()) {
        $json["resultat"]=true;
        $json["id"]=$emplacement->id;
        $json["emplacement"]=$emplacement->lieu;
      }
      else {
        $json["resultat"]=false;
        $json["message"]=$emplacement->error;
      }
    }
    // Modification (POST) -> OP=M et ID non nul
    else if ( isset($_POST['op']) && $_POST['op']=='M' && isset($_POST['id']) && strlen($_POST['id'])>0  )
    {
      $emplacement->id = $_POST['id'];
      $valTemp = $_POST['emplacement'];
      $emplacement->lieu = $valTemp;
      if ($debug==true)
      {
        echo "Modification de l'objet Emplacement {$emplacement->id}<br>";
      }
      $json["resultat"]=$emplacement->update();
      $json["message"]=$emplacement->error;
    }
    // Suppression (POST) -> OP=D et ID non nul
    else if ( isset($_POST['op']) && $_POST['op']=='D' && isset($_POST['id']) && strlen($_POST['id'])>0  )
    {
      $emplacement->id = $_POST['id'];
      if ($debug==true)
      {
        echo "Suppression de l'objet Emplacement {$emplacement->id}<br>";
      }
      $json["resultat"]=$emplacement->delete();
    }
    // Ajout (POST) -> OP=M et peut importe l'id
    else if ( isset($_POST['op']) && $_POST['op']=='M' )
    {
      $emplacement->id = $emplacement->id;
      $valTemp = $_POST['emplacement'];
      $emplacement->lieu = $valTemp;
      $valTemp = $_SESSION['id_utilisateur'];
      $emplacement->id_utilisateur = $valTemp;
      if ($debug==true)
      {
        echo "Ajout de l'objet Emplacement {$emplacement->id}<br>";
      }
      $json["resultat"]=$emplacement->create();
      $json["message"]=$emplacement->error;
  }
    // Autre cas -> Erreur
    else {
      $json["resultat"]=false;
      $json["message"]="Parametres absents";      
    }

} 
//Erreur si pas de session
else
{
  $json["resultat"]=false;
  $json["commentaire"]="Pas connecté !";
}

echo json_encode($json);
?>
