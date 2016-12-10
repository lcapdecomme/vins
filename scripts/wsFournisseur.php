<?php 
session_start();
ini_set('zlib.output_compression_level', 6);
ob_start('ob_gzhandler');
header('Content-Type: application/json');
ini_set('display_errors', 1);
// Script REST pour voir/modifier un objetfournisseur
if ($_SESSION && isset($_SESSION['id_utilisateur']) )  {
    $debug=false;
    if (isset($_GET['debug']))
    {
      // Mode debug
      $debug=true;
    }
    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/Fournisseur.php';
    // instantie la base
    $database = new Database();
    $db = $database->getConnection();

    // Nouvel objet Fournisseur
    $fournisseur = new Fournisseur($db);

    // Recherche (GET) -> op=R et id présent
    if ( isset($_GET['op']) && $_GET['op']=='R' && isset($_GET['id']) && strlen($_GET['id'])>0   )
    {
      $fournisseur->id = $_GET['id'];
      if ($debug==true)
      {
        echo "Recherche de l'objet Fournisseur {$fournisseur->id}<br>";
      }
      if ($fournisseur->read()) {
        $json["resultat"]=true;
        $json["id"]=$fournisseur->id;
        $json["nom"]=$fournisseur->nom;
        $json["adresse"]=$fournisseur->adresse;
        $json["cp"]=$fournisseur->cp;
        $json["ville"]=$fournisseur->ville;
        $json["telFixe"]=$fournisseur->telFixe;
        $json["telPortable"]=$fournisseur->telPortable;
        $json["mail"]=$fournisseur->mail;
        $json["url"]=$fournisseur->url;
        $json["message"]="";
      }
      else {
        $json["resultat"]=false;
        $json["message"]=$fournisseur->error;
      }
    }
    // Modification (POST) -> OP=M et ID non nul
    else if ( isset($_POST['op']) && $_POST['op']=='M' && isset($_POST['id']) && strlen($_POST['id'])>0  )
    {
      $fournisseur->id = $_POST['id'];
      $fournisseur->nom = $_POST['nom'];
      $fournisseur->adresse = $_POST['adresse'];
      $fournisseur->cp = $_POST['cp'];
      $fournisseur->ville = $_POST['ville'];
      $fournisseur->telFixe = $_POST['telFixe'];
      $fournisseur->telPortable = $_POST['telPortable'];
      $fournisseur->mail = $_POST['mail'];
      $fournisseur->url = $_POST['url'];
      if ($debug==true)
      {
        echo "Modification de l'objet Fournisseur {$fournisseur->id}<br>";
      }
      if ($fournisseur->update()) {
        $json["resultat"]=true;
        $json["message"]="";
      }
      else {
        $json["resultat"]=false;
        $json["message"]=$fournisseur->error;
      }
    }
    // Suppression (POST) -> OP=D et ID non nul
    else if ( isset($_POST['op']) && $_POST['op']=='D' && isset($_POST['id']) && strlen($_POST['id'])>0  )
    {
      $fournisseur->id = $_POST['id'];
      if ($debug==true)
      {
        echo "Suppression de l'objet Fournisseur {$fournisseur->id}<br>";
      }
      if ($fournisseur->delete()) {
        $json["resultat"]=true;
        $json["message"]="";
      }
      else {
        $json["resultat"]=false;
        $json["message"]=$fournisseur->error;
      }
    }
    // Ajout (POST) -> OP=M et peut importe l'id
    else if ( isset($_POST['op']) && $_POST['op']=='M' )
    {
      $fournisseur->id = $fournisseur->id;
      $fournisseur->nom = $_POST['nom'];
      $fournisseur->adresse = $_POST['adresse'];
      $fournisseur->cp = $_POST['cp'];
      $fournisseur->ville = $_POST['ville'];
      $fournisseur->telFixe = $_POST['telFixe'];
      $fournisseur->telPortable = $_POST['telPortable'];
      $fournisseur->mail = $_POST['mail'];
      $fournisseur->url = $_POST['url'];
      $valTemp = $_SESSION['id_utilisateur'];
      $fournisseur->id_utilisateur = $valTemp;
      if ($debug==true)
      {
        echo "Ajout de l'objet Fournisseur {$fournisseur->id}<br>";
      }
      if ($fournisseur->create()) {
        $json["resultat"]=true;
        $json["message"]="";
      }
      else {
        $json["resultat"]=false;
        $json["message"]=$fournisseur->error;
      }
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
  $json["message"]="Pas connecté !";
}
echo json_encode($json);
?>
