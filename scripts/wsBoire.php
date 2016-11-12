<?php 
session_start();
ini_set('zlib.output_compression_level', 6);
ob_start('ob_gzhandler');
header('Content-Type: application/json');
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
	if($_POST){
		// Nouvel objet Bouteille 
		$bouteille = new Bouteille($db);
		$id = $_POST['id'];
		$qte = $_POST['qte'];
		if ($debug==true)
		{
			echo "Bouteille {$id} = Quantité : {$qte} <br>";
		}
		if ($bouteille->drink($id, $qte)) {
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
