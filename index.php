<?php
	$page_title = "Mes bouteilles";
	include_once "header.php";
	// include database and object files
	include_once 'config/database.php';
	include_once 'config/util.php';
	include_once 'objects/Bouteille.php';
	include_once 'objects/Utilisateur.php';

	// debug mode ?  
	$debug=false;
    if (isset($_GET['debug']))
    {
    	// Mode debug
    	$debug=true;
    }

	// instantiate database and product object
	$database = new Database();
	$db = $database->getConnection();
	$bouteille = new Bouteille($db);
	$login = new Utilisateur($db);	 

	// Si formulaire Recherche soumis et commentaire
	if($_GET && isset($_GET['comment']))
	{
		$bouteille->commentaire=$_GET['comment'];
	}
	
	// show page header
	$total_users = $login->countAll();
	$total_wines = $bouteille->countAll();
	$total_bottles = $bouteille->sumAll();


	if (!$_SESSION || !isset($_SESSION['id_utilisateur']) ) {
		echo "<div class='jumbotron'>";
		if (isLocalhost()) {
	       	// mode loacalhost
			echo "<h1>Gestion de ma cave à vin</h1><br>";
			echo "<p class='lead'>La connexion est nécessaire pour utiliser l'application</p>";
		} else {
	      	// mode SAAS 
			echo "<h1>Bienvenue</h1><br>";
			echo "<p class='lead'>Bienvenue sur l'application de gestion de votre cave à vins. A ce jour, déjà ";
			echo "<span id='totalUsers'>{$total_users}</span>&nbsp;utilisateur";
			if ($total_users>1) { echo "s"; }
			echo " utilisent l'application pour gérer <span id='totalVins'>{$total_wines}</span>&nbsp;vin";
			if ($total_wines>1) {   echo "s";  }
			echo " soit <span id='totalBouteilles'>{$total_bottles}</span>&nbsp;bouteille";
			if ($total_bottles>1) {   echo "s";  }
			echo "</span>.";
			echo "<p>Vous pouvez tester l'application avec le compte <b><i>test</b></i> et le mot de passe <b><i>test</b></i> </p>";
		}
	       echo "<br><a href='login.php' class='btn btn-lg btn-success pull-right'>Connexion</a>";	
		echo "<br><br></div>"; 
    } else   {
		include_once 'objects/Type.php';
		include_once 'objects/Emplacement.php';
    	$dateSysteme=date("Y");
		// Recherche de tous les objets Emplacement
		$total_emplacement = 0;
		$emplacement = new Emplacement($db);
		$emplacement->id_utilisateur = $_SESSION['id_utilisateur'];
		$stmtEmplacment = $emplacement->readAll();
		$total_emplacement = $stmtEmplacment->rowCount();
		//set Session.Emplacement only here !
		if ($total_emplacement>=1) {
	    	$_SESSION['emplacement']='O';
		} else {
	    	$_SESSION['emplacement']='N';
		}
		echo "<br class='row hidden-xs'>";
		// Row except smartphone
	    echo "<div class='row hidden-xs'>";
		echo "<div  class='col-md-4 col-sm-4'>";
		echo "<h2><span id='totalVins'>{$total_wines}</span>&nbsp;vin";
		if ($total_wines>1) 			{   echo "s";  }
		echo "</h2></div>";
		echo "<div  class='col-md-4 col-sm-4'>";
		echo "<h2 class='text-center'><span id='totalBouteilles'>{$total_bottles}</span>&nbsp;bouteille";
		if ($total_bottles>1) 			{  echo "s</span>"; }
		echo "</h2></div>";
	    echo "<div  class='col-md-4 col-sm-4'>";
		if ($_SESSION && isset($_SESSION['id_utilisateur']) ) {
	        echo "<div class='right-button-margin'>";
			echo "<a href='ajout_bouteille.php' class='btn  btn-primary pull-right'>Ajouter un vin</a>";
			echo "</div>";
		} else {
			echo "<h2 style='text-align:right'><span id='totalUsers'>{$total_users}</span>&nbsp;utilisateur";
			if ($total_users>1) 		{  echo "s";  }
			echo "</h2>";
		}
		echo "</div></div>";
		// Row for smartphone
	    echo "<div class='row show-xs hidden-sm hidden-md  hidden-lg'>";
		echo "<div  class='col-xs-4'>";
		echo "<h4><span id='totalVins'>{$total_wines}</span>&nbsp;vin";
		if ($total_wines>1) 		{   echo "s";  }
		echo "</h4></div>";
		echo "<div  class='col-xs-5'>";
		echo "<h4><span id='totalBouteilles'>{$total_bottles}</span> bout.</h4></div>";
	    echo "<div  class='col-xs-3'>";
		if ($_SESSION && isset($_SESSION['id_utilisateur']) ) {
	        echo "<div><a href='ajout_bouteille.php' class='btn btn-primary pull-right'>Ajouter</a></div>";
		} else {
			echo "<h4><span id='totalUsers'>{$total_users}</span>&nbsp;util.</h4>";
		}
		echo "<br></div></div>";
		// query bottles
		$stmt = $bouteille->readAll();
		if ($debug)
		{
				echo "Retour recherche : {$bouteille->error}<br>";
				echo "Nb Bouteilles : {$stmt->rowCount()}<br>";
				print_r($stmt);
				echo "<br>";
		}
		echo "<br class='row hidden-xs'>";
		// display the products if there are any
		if($total_wines>0)
		{
		    $type = new Type($db);
			echo "<div id='modal_confirm_yes_no' title='Confirm'></div>";
		    echo "<table class='table table-striped table-hover table-responsive tablesorter' id='allVins' style='width:auto'>";
			echo "<thead>";
			echo "<tr class='tablesorter-ignoreRow'>";
			echo "<td class='pager' colspan='10'>";
			echo "<img src='lib/tablesorter/addons/pager/icons/first.png' class='first'/>";
			echo "<img src='lib/tablesorter/addons/pager/icons/prev.png' class='prev'/>";
			echo "<span class='pagedisplay'></span>";
			echo "<img src='lib/tablesorter/addons/pager/icons/next.png' class='next'/>";
			echo "<img src='lib/tablesorter/addons/pager/icons/last.png' class='last'/>";
			echo "<select class='pagesize'>";
			echo "<option value='10'>10</option>";
			echo "<option value='20'>20</option>";
			echo "<option value='50'>50</option>";
			echo "<option value='100'>100</option>";
			echo "</select>";
			echo "</td>";
			echo "</tr>";
			echo "<tr>";
            echo "<th>Nom</th>";
            echo "<th class='hidden-xs'>Qté</th>";
            echo "<th class='hidden-sm hidden-xs'>Vol.</th>";
            echo "<th class='colCouleur hidden-xs'>Type</th>";
            echo "<th class='filter-select filter-onlyAvail hidden-md hidden-sm hidden-xs'>Achat</th>";
            echo "<th class='filter-select filter-onlyAvail hidden-sm hidden-xs'>Millesime</th>";
            echo "<th class='filter-select filter-onlyAvail hidden-xs'>Apogée</th>";
            echo "<th class='hidden-sm hidden-xs'>AOC</th>";
            if ($total_emplacement>=1) {
            	echo "<th class='hidden-sm hidden-md hidden-xs'>Emplacement</th>";
            } else {
            	echo "<th class='hidden-sm hidden-md hidden-xs'>Région</th>";
            }
			if ($_SESSION && isset($_SESSION['id_utilisateur']) ) {
	            echo "<th class='titreOperations filter-false'>Opérations&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
			} else {
	            echo "<th class='titreCavistes'>Caviste&nbsp;&nbsp;&nbsp;&nbsp;</th>";
			}
        	echo "</tr></thead>";
        	echo "<tbody>";
		 
	        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	            extract($row);
	            echo "<tr>";
	                echo "<td><a href='maj_bouteille.php?id={$id}' class='linkBottle'>{$nomb}</a>";
	                if (isset($nomPhoto) && strlen($nomPhoto)>0) {
	                	$nomComplet=  'uploads' . DIRECTORY_SEPARATOR.$nomPhoto;
	                	if (file_exists($nomComplet) ) {
	                		list($width, $height) = getimagesize($nomComplet);
	                		echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' class='enlarge' data-src='".$nomComplet."' data-width='".$width."' data-height='".$height."'><span class='glyphicon glyphicon-camera' aria-hidden='true'></span></a>";
	                }	
	                }
	                echo "</td>";
	                echo "<td class='colQuantite hidden-xs' id='quantite_{$id}' style='text-align:center;'>{$quantite}</td>";
	                echo "<td class='hidden-sm hidden-xs' style='text-align:center;'>{$type_volume}</td>";
	                echo "<td class='textAndImg colCouleur hidden-xs'  style='text-align:center;'> ";
	                    $type->id = $id_type;
	                    if ($id_type==1)	echo "<span class='cacheca'>$id_type</span>&nbsp;&nbsp;<img src='img/logo_rouge.png' title='Rouge' />";
	                    if ($id_type==3)	echo "<span class='cacheca'>$id_type</span>&nbsp;&nbsp;<img src='img/logo_rose.png' title='Rosé' />";
	                    if ($id_type==4)	echo "<span class='cacheca'>$id_type</span>&nbsp;&nbsp;<img src='img/logo_doux.png' title='Vin doux / moelleux' />";
	                    if ($id_type==5)	echo "<span class='cacheca'>$id_type</span>&nbsp;&nbsp;<img src='img/logo_effervescent.png' title='Vin effervescent / champagne' />";
	                    if ($id_type==6)	echo "<span class='cacheca'>$id_type</span>&nbsp;&nbsp;<img src='img/logo_aperitifs.png' title='Apéritifs' />";
	                    if ($id_type==2)	echo "<span class='cacheca'>$id_type</span>&nbsp;&nbsp;<img src='img/logo_blanc.png' title='Blanc' />";
	                echo "</td>";

	                         		
	                if ($achat<>0) {
	                	echo "<td class='hidden-sm hidden-md hidden-xs' style='text-align:center;'>{$achat}</td>";
	                }
	                else {
	                	echo "<td class='hidden-sm hidden-md hidden-xs' ></td>";
	                }
	                if ($millesime<>0) {
	                	echo "<td class='hidden-sm hidden-xs' style='text-align:center;'>{$millesime}</td>";
	                }
	                else {
	                	echo "<td class='hidden-sm hidden-xs'></td>";
	                }
	                if ($apogee<>0) {
						if ($dateSysteme>=$apogee) {
		                	echo "<td class='hidden-xs' style='text-align:center;'><span class='apogee'>{$apogee}</span></td>";
	                	}
	                	else {
		                	echo "<td class='hidden-xs' style='text-align:center;'>{$apogee}</td>";
						}
	                }
	                else {
	                	echo "<td class='hidden-sm hidden-xs'></td>";
	                }

	                // AOC
	                echo "<td class='hidden-sm hidden-xs'>{$appellation}</td>";
			        // Emplacement de la bouteille ou région ?
		            if ($total_emplacement>=1) {
		                echo "<td class='hidden-sm hidden-md hidden-xs' >{$lieu}</td>";
		            } else {
		                echo "<td class='hidden-sm hidden-md hidden-xs' >{$region}</td>";
		            }

					if ($_SESSION && isset($_SESSION['id_utilisateur']) ) {
						// edit and delete button is here
						echo "<td><a href='maj_bouteille.php?id={$id}' class='btn btn-xs btn-primary left-margin' title='Modification'><span class='glyphicon glyphicon-ok' aria-hidden='true'></span></a>&nbsp;";
						echo "<a href='#' delete-id='{$id}' update-name='{$nomb}' class='btn btn-xs btn-danger deleteOperation' title='Suppression'><span class='glyphicon glyphicon-remove aria-hidden='true'></span></a>&nbsp;";
						if ($quantite>0) {
							echo "<a href='#' update-id='{$id}' update-name='{$nomb}' class='btn btn-xs btn-success drinkOperation right-margin' title='Boire'><span class='glyphicon glyphicon-glass aria-hidden='true'></span></a>";
						} 
						echo "</td>";
					}
	                else {
	                	echo "<td>".$nomu."</td>";
	                }
	            echo "</tr>";
	        }
	    	echo "</tbody></table>";
		}
		echo "<br class='row hidden-xs'>";
		// End display the products if there are any
?>

<!-- Creates the bootstrap modal where the image will appear -->
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Aperçu</h4>
      </div>
      <div class="modal-body">
        	<img src="#" id="imagepreview" class="img-responsive center-block viewBottle">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>




 <script type="text/javascript">


$(document).ready(function() {

	// **********************************
	//  Description of ALL pager options
	// **********************************
	var pagerOptions = {
		container: $(".pager"), // target the pager markup - see the HTML block below
		// output string - default is '{page}/{totalPages}'
		// possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
		// also {page:input} & {startRow:input} will add a modifiable input in place of the value
		output: '{startRow:input} to {endRow} ({totalRows})',
		// starting page of the pager (zero based index)
		page: 0,
		// Number of visible rows - default is 20
		size: 10,
		// Save pager page & size if the storage script is loaded (requires $.tablesorter.storage in jquery.tablesorter.widgets.js)
		savePages : true,
		//defines custom storage key
		storageKey:'tablesorter-pager',
		// if true, the table will remain the same height no matter how many records are displayed. The space is made up by an empty
		// table row set to a height to compensate; default is false
		fixedHeight: true,
		// remove rows from the table to speed up the sort of large tables.
		// setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
		removeRows: false,
		// css class names of pager arrows
		cssNext: '.next', // next page arrow
		cssPrev: '.prev', // previous page arrow
		cssFirst: '.first', // go to first page arrow
		cssLast: '.last', // go to last page arrow
		cssGoto: '.gotoPage', // select dropdown to allow choosing a page
		cssPageDisplay: '.pagedisplay', // location of where the "output" is displayed
		cssPageSize: '.pagesize', // page size selector - select dropdown that sets the "size" option
		// class added to arrows when at the extremes (i.e. prev/first arrows are "disabled" when on the first page)
		cssDisabled: 'disabled', // Note there is no period "." in front of this class name
		cssErrorRow: 'tablesorter-errorRow' // ajax error information row
	};

	$.tablesorter.filter.types.start = function( config, data ) {
	  if ( /^\^/.test( data.iFilter ) ) {
	    return data.iExact.indexOf( data.iFilter.substring(1) ) === 0;
	  }
	  return null;
	};

	// search for a match at the end of a string
	// "a$" matches "Llama" but not "aardvark"
	$.tablesorter.filter.types.end = function( config, data ) {
	  if ( /\$$/.test( data.iFilter ) ) {
	    var filter = data.iFilter,
	      filterLength = filter.length - 1,
	      removedSymbol = filter.substring(0, filterLength),
	      exactLength = data.iExact.length;
	    return data.iExact.lastIndexOf(removedSymbol) + filterLength === exactLength;
	  }
	  return null;
	};

	$('table').tablesorter({
		theme : "blue",
		widgets        : ['zebra', 'filter', 'resizable'],
	    headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

		widgetOptions: {
        // zebra widget: adding zebra striping, using content and
        // default styles - the ui css removes the background
        // from default even and odd class names included for this
        // demo to allow switching themes
        // [ "even", "odd" ]
        zebra: [
            "ui-widget-content even",
            "ui-state-default odd"],
        // uitheme widget: * Updated! in tablesorter v2.4 **
        // Instead of the array of icon class names, this option now
        // contains the name of the theme. Currently jQuery UI ("jui")
        // and Bootstrap ("bootstrap") themes are supported. To modify
        // the class names used, extend from the themes variable
        // look for the "$.extend($.tablesorter.themes.jui" code below
        uitheme: 'jui',
        // columns widget: change the default column class names
        // primary is the 1st column sorted, secondary is the 2nd, etc
        columns: [
            "primary",
            "secondary",
            "tertiary"],
        // columns widget: If true, the class names from the columns
        // option will also be added to the table tfoot.
        columns_tfoot: true,
        // columns widget: If true, the class names from the columns
        // option will also be added to the table thead.
        columns_thead: true,
        // filter widget: If there are child rows in the table (rows with
        // class name from "cssChildRow" option) and this option is true
        // and a match is found anywhere in the child row, then it will make
        // that row visible; default is false
        filter_childRows: false,
        // filter widget: If true, a filter will be added to the top of
        // each table column.
        filter_columnFilters: true,
		// Use the $.tablesorter.storage utility to save the most recent filters
		filter_saveFilters : false,
        // filter widget: css class applied to the table row containing the
        // filters & the inputs within that row
        filter_cssFilter: "tablesorter-filter",
        // filter widget: Customize the filter widget by adding a select
        // dropdown with content, custom options or custom filter functions
        // see http://goo.gl/HQQLW for more details ou 
        // http://mottie.github.io/tablesorter/docs/example-widget-filter-custom.html
		filter_functions : {
		  // Add these options to the select dropdown (numerical comparison example)
		  // Note that only the normalized (n) value will contain numerical data
		  // If you use the exact text, you'll need to parse it (parseFloat or parseInt)
		  '.colCouleur' : {
		    "Rouge"  : function(e, n, f, i, $r, c) { return n == 1; },
		    "Blanc"  : function(e, n, f, i, $r, c) { return n == 2; },
		    "Rosé"  : function(e, n, f, i, $r, c) { return n == 3; },
		    "Vin doux"  : function(e, n, f, i, $r, c) { return n == 4; },
		    "Effervescent"  : function(e, n, f, i, $r, c) { return n == 5; },
		    "Aperitifs" : function(e, n, f, i, $r, c) { return n == 6; }
		  }
		},
        // filter widget: Set this option to true to hide the filter row
        // initially. The rows is revealed by hovering over the filter
        // row or giving any filter input/select focus.
        filter_hideFilters: false,
        // filter widget: Set this option to false to keep the searches
        // case sensitive
        filter_ignoreCase: true,
        // filter widget: jQuery selector string of an element used to
        // reset the filters.
		filter_reset : '.reset',
        // Delay in milliseconds before the filter widget starts searching;
        // This option prevents searching for every character while typing
        // and should make searching large tables faster.
        filter_searchDelay: 300,
        // Set this option to true if filtering is performed on the server-side.
        filter_serversideFiltering: false,
        // filter widget: Set this option to true to use the filter to find
        // text from the start of the column. So typing in "a" will find
        // "albert" but not "frank", both have a's; default is false
        filter_startsWith: false,
        // filter widget: If true, ALL filter searches will only use parsed
        // data. To only use parsed data in specific columns, set this option
        // to false and add class name "filter-parsed" to the header
        filter_useParsedData: false,
        // Resizable widget: If this option is set to false, resized column
        // widths will not be saved. Previous saved values will be restored
        // on page reload
        resizable: true,
        // saveSort widget: If this option is set to false, new sorts will
        // not be saved. Any previous saved sort will be restored on page
        // reload.
        saveSort: true,
        // stickyHeaders widget: css class name applied to the sticky header
        stickyHeaders: "tablesorter-stickyHeader"

    },
		usNumberFormat : false,
		widthFixed	   : false
	})
	.tablesorterPager(pagerOptions)
	.bind('filterEnd', function(event, data) {
		$('#totalVins').html( data.filteredRows );
		var totalRows=0;
		$("table tr:not(.filtered) td.colQuantite").each(function() {
			totalRows=totalRows + (parseInt($(this).text())|| 0);
			});
		$('#totalBouteilles').html( totalRows );
	});

    });


	// Bouton Suppression
	$(document).on('click', '.deleteOperation', function(){
		var id = $(this).attr('delete-id');
		var name = $(this).attr('update-name');
		$("#modal_confirm_yes_no").html("Êtes-vous sûr de supprimer le vin '"+name+"' ?");
		$("#modal_confirm_yes_no").dialog({
			autoOpen: true,
			minHeight: 200,
			width: 450,
			modal: true,
			closeOnEscape: true,
			draggable: true,
			resizable: false,
			buttons: {
				'Oui': function(){
					$(this).dialog('close');
					suppression(id);
				},
				'Non': function(){
					$(this).dialog('close');
				}
			}
		});

		function suppression(valueId){
			$.ajax({
			url: "efface_bouteille.php",
			type: "POST",
			data: {id : valueId},
			success : function(msg) {
			location.reload();
			}
			});
		}
		return false;
	});

	// Bouton Boire
	$(document).on('click', '.drinkOperation', function(){
		var id = $(this).attr('update-id');
		var name = $(this).attr('update-name');
		var qte = $("#quantite_"+id).html()-1;
		if (qte>0)	{
			$temp="Il restera "+qte+" bouteilles";
		}
		if (qte==1) {
			$temp="Il restera "+qte+" bouteille";
		}
		if (qte==0) {
			$temp="Il ne restera plus de bouteille";
		}
		$("#modal_confirm_yes_no").html("Confirmez-vous avoir consommé une bouteille de "+name+" ?<br>"+$temp);
		$("#modal_confirm_yes_no").dialog({
			autoOpen: true,
			minHeight: 200,
			width: 450,
			modal: true,
			closeOnEscape: true,
			draggable: true,
			resizable: false,
			buttons: {
				'Oui': function(){
					$(this).dialog('close');
					boire(id, qte);
				},
				'Non': function(){
					$(this).dialog('close');
				}
			}
		});

		function boire(valueId, valueQte){
			$.ajax({
				valueId : valueId,
				valueQte : valueQte,
				url: "scripts/wsBoire.php",
				type: "POST",
				data: {id : valueId, qte : valueQte},
				success : function(msg) {
					// -1 bottle
					if (msg.resultat) {
						$("#quantite_"+valueId).html(valueQte);
						$("#totalBouteilles").html($("#totalBouteilles").html()-1);
						if ($("#totalBouteilles").html()<2) {
							$("#titreBouteilles").html("bouteille");
						}
					}
				}
			});
		}
		return false;
	});

	$("a.enlarge").on("click", function() {
	   $('#imagepreview').attr('src', $(this).attr('data-src')); // here asign the image to the modal when the user click the enlarge link
	   if ($(this).attr('data-width')>$(this).attr('data-height')) {
		   	$('#imagepreview').css('width', '400px'); // Landscape view
	   } else {
		   	$('#imagepreview').css('width', '300px'); // Portrait view
	   }
	   $('#imagemodal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
	});

	
	/**
	 * Initialisation de la page 
	 * 
	 */
	function initApplication() {
		/*$('table').trigger('filterReset');
		$('table').trigger('filterResetSaved');*/
     	// hide last column if label is 'Opérations'
     	<?php
     		if ( isset($_SESSION) && isset($_SESSION["nb_vins_affiches"]) ) { 
     			echo "$('table').trigger('pageSize', ";
  				echo $_SESSION["nb_vins_affiches"];
     			echo ");";
     		} else  {
    			echo "$('table').trigger('pageSize', 10);";
     		}
     	?>
	    /*var s = $(".titreOperations div").html();
		if ( (typeof s !== 'undefined')  && (s.indexOf('Opérations') !== -1 ) ) {
			$('input').filter(function(){return $(this).data().column == '8';}).hide();
		}*/
	}



	/**
	 * Initialisation de l'application dÃ¨s que le DOM est chargÃ©
	 */
	$(document).ready(initApplication);



  </script>



<?php
}
include_once "footer.php";
?>