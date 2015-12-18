<?php

// get database connection
include_once 'config/database.php';
include_once 'objects/Referentiel.php';
 
$database = new Database();
$db = $database->getConnection();


// Des données ont-elles été envoyées par POST ? 
if($_GET){
 
	 //Creates a blank array.
	$row_set = array();

    // Nouvel objet Referentiel 
    $referentiel = new Referentiel($db);

    $term = '%'.$_GET['term'].'%';

	$stmt = $referentiel->readLike($term);

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		//$row['nom']=htmlentities(stripslashes($row['nom']));
		$row_set[] = $row; //build an array	
		//$row_set[] = $row; //build an array	
	}

    // On renvoie le données au format JSON pour le plugin
    echo json_encode($row_set);
}

?>
