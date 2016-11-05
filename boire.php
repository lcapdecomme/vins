<?php

// get database connection
include_once 'config/database.php';
include_once 'objects/Bouteille.php';
 
if (!$_SESSION || !isset($_SESSION['id_utilisateur']) ) {
    header('Location: index.php');
}

$database = new Database();
$db = $database->getConnection();


// Des données ont-elles été envoyées par POST ? 
if($_POST){
	// Nouvel objet Bouteille 
	$bouteille = new Bouteille($db);

	$id = $_POST['id'];
	$qte = $_POST['qte'];

	if ($bouteille->drink($id, $qte)) {
		echo "Ok";
	}
	else {
		echo "Pb";
	}
}

?>
