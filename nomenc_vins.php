<?php
$page_title = "Nomenclatures des vins";
include_once "header.php";
?>



<?php

	// page given in URL parameter, default page is one
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	 
	// set number of records per page
	$records_per_page = 15;
	 
	// calculate for the query LIMIT clause
	$from_record_num = ($records_per_page * $page) - $records_per_page;


	// include database and object files
	include_once 'config/database.php';
	include_once 'objects/Referentiel.php';
	 
	// instantiate database and product object
	$database = new Database();
	$db = $database->getConnection();
	 
	$vins = new Referentiel($db);
	 
	// query products
	$stmt = $vins->readAll($page, $from_record_num, $records_per_page);
	$num = $stmt->rowCount();
	 
	// display the products if there are any
	if($num>0){
	    echo "<table class='table table-striped table-hover table-responsive'>";
	        echo "<tr>";
	            echo "<th>Nom</th>";
	            echo "<th>Région</th>";
	            echo "<th>Type</th>";
	        echo "</tr>";
	 
	        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	 
	            extract($row);
	 
	            echo "<tr>";
	                echo "<td>{$nom}</td>";
	                echo "<td>{$region}</td>";
	                echo "<td>{$libelle}</td>";
	               

	            echo "</tr>";
	 
	        }
	 
	    echo "</table>";
	 
	    // paging buttons here
		include_once 'paging/paging_vins.php';
	}
	 
	// tell the user there are no products
	else{
	    echo "<div>Aucun vin trouvé</div>";
	}
?>



<?php
include_once "footer.php";
?>