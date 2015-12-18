<?php
$page_title = "Nomenclatures des AOC";
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
	include_once 'objects/AOC.php';
	 
	// instantiate database and product object
	$database = new Database();
	$db = $database->getConnection();
	 
	$aoc = new AOC($db);
	 
	// query products
	$stmt = $aoc->readAll($page, $from_record_num, $records_per_page);
	$num = $stmt->rowCount();
	 
	// display the products if there are any
	if($num>0){
	    echo "<table class='table table-striped table-hover table-responsive'>";
	        echo "<tr>";
	            echo "<th>Appellation</th>";
	            echo "<th>Région</th>";
	            echo "<th>Sous-Division</th>";
	        echo "</tr>";
	 
	        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	 
	            extract($row);
	 
	            echo "<tr>";
	                echo "<td>{$appellation}</td>";
	                echo "<td>{$region}</td>";
	                echo "<td>{$sousdivision}</td>";
	               

	            echo "</tr>";
	 
	        }
	 
	    echo "</table>";
	 
	    // paging buttons here
		include_once 'paging/paging_aoc.php';
	}
	 
	// tell the user there are no products
	else{
	    echo "<div>Aucun AOC trouvé</div>";
	}
?>



<?php
include_once "footer.php";
?>