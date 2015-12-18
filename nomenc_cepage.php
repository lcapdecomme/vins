<?php
$page_title = "Nomenclatures des cépages";
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
	include_once 'objects/Cepage.php';
	 
	// instantiate database and product object
	$database = new Database();
	$db = $database->getConnection();
	 
	$cepage = new Cepage($db);
	 
	// query products
	$stmt = $cepage->readAll($page, $from_record_num, $records_per_page);
	$num = $stmt->rowCount();
	 
	// display the products if there are any
	if($num>0){
	    echo "<table class='table table-striped table-hover table-responsive'>";
	        echo "<tr>";
	            echo "<th>Nom</th>";
	            echo "<th>Couleur</th>";
	            echo "<th>Origine</th>";
	            echo "<th>Superficie (m2)</th>";
	            echo "<th>Année</th>";
	        echo "</tr>";
	 
	        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	 
	            extract($row);
	 
	            echo "<tr>";
	                echo "<td>{$nom}</td>";
	                echo "<td>{$couleur}</td>";
	                echo "<td>{$origine}</td>";
	                echo "<td>{$superficie}.000</td>";
	                echo "<td>{$annee}</td>";
	               

	            echo "</tr>";
	 
	        }
	 
	    echo "</table>";
	 
	    // paging buttons here
		include_once 'paging/paging_cepage.php';
	}
	 
	// tell the user there are no products
	else{
	    echo "<div>Aucun cépage trouvé</div>";
	}
?>



<?php
include_once "footer.php";
?>