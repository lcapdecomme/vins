<?php

// get database connection
include_once 'config/database.php';
include_once 'objects/Bouteille.php';
 
$database = new Database();
$db = $database->getConnection();


// Des données ont-elles été envoyées par POST ? 
if($_POST){
	// Nouvel objet Bouteille 
	$bouteille = new Bouteille($db);

	$id = $_POST['id'];
	$qte = $_POST['qte'];

	$stmt = $bouteille->drink($id, $qte);
	if ($stmt==true) {
		echo "Ok";
	}
	else {
		echo "Pb";
	}
}

?>
