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

$header = array('Nom du vin', 'quantite', 'Date achat', 'millesime', 'apogee', 'prix achat', 'prix estime', 'type', 'commentaire', 'contenance', 'cepage', 'aoc', 'region', 'emplacement', 'ajout');

$fp = fopen('php://output', 'w');
// Encoding
fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
if ($fp && $stmt) {
    header("Content-type: application/vnd.ms-excel; charset=UTF-8");
    header('Content-Disposition: attachment; filename="export.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
	// header
    fputcsv($fp, $header,';'); 
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
		$value = array($nomb, $quantite, $achat, $millesime, $apogee, $prixachat, 
                        $prixestime, $type_vin, $commentaire, $type_contenance, $nom_cepage, $appellation, $region, $lieu, $ajout);
        fputcsv($fp, array_values($value), ';');
    }
    die;
}


