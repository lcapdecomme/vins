<?php
session_start();
ini_set('zlib.output_compression_level', 6);
ob_start('ob_gzhandler');

// include database and object files
include_once 'config/database.php';
include_once 'objects/Bouteille.php';

if (!$_SESSION || !(isset($_SESSION['id_utilisateur'])) ) {
    header('Location: index.php');
}

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
$bouteille = new Bouteille($db);
$stmt = $bouteille->readAll();

$header = array('Identifiant', 'Nom du vin', 'quantite', 'Date achat', 'prix achat', 'prix estime', 'millesime', 'apogee', 'commentaire', 'contenance', 'cepage', 'aoc', 'type', 'emplacement', 'ajout');

$fp = fopen('php://output', 'w');
if ($fp && $stmt) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="export.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
	// header
    fputcsv($fp, $header,';'); 
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        fputcsv($fp, array_values($row), ';');
    }
    die;
}


