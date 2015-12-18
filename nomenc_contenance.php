<?php
$page_title = "Nomenclatures des contenances";
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
	include_once 'objects/Contenance.php';
	 
	// instantiate database and product object
	$database = new Database();
	$db = $database->getConnection();
	 
	$contenance = new Contenance($db);
	 
	// query products
	$stmt = $contenance->readAll($page, $from_record_num, $records_per_page);
	$num = $stmt->rowCount();
	 
	// display the products if there are any
	if($num>0){
	    echo "<table class='table table-striped table-hover table-responsive'>";
	        echo "<tr>";
	            echo "<th>Nom</th>";
	            echo "<th>Volume</th>";
	            echo "<th>Equivalence</th>";
	        echo "</tr>";
	 
	        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	 
	            extract($row);
	 
	            echo "<tr>";
	                echo "<td>{$nom}</td>";
	                echo "<td>{$volume}</td>";
	                echo "<td>{$equivalence}</td>";
	               

	            echo "</tr>";
	 
	        }
	 
	    echo "</table>";
	 
	    // paging buttons here
		include_once 'paging/paging_contenance.php';
	}
	 
	// tell the user there are no products
	else{
	    echo "<div>Aucun cépage trouvé</div>";
	}
?>



<?php
include_once "footer.php";
?>