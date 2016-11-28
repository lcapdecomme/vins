<?php
$page_title = "Mise à jour d'une bouteille";
include_once "header.php";

// user connected ? 
if (!$_SESSION || !isset($_SESSION['id_utilisateur']) ) {
    header('Location: index.php');
}
// id bottle ? 
if (!$_GET || !isset($_GET['id'])  ) {
    header('Location: index.php');
}
//include database and object files
include_once 'config/database.php';
include_once 'objects/Bouteille.php';
include_once 'objects/Cepage.php';     
include_once 'config/util.php';
 
// debug mode ?  
$debug=false;
if (isset($_GET['debug']))
{
	// Mode debug
	$debug=true;
}

$database = new Database();
$db = $database->getConnection();
$cepage = new Cepage($db);
$stmtcepage = $cepage->read();

// prepare bouteille object
$bouteille = new Bouteille($db);
// get ID of the bouteille to be edited
$id = $_GET['id'];
// set ID property of bouteille to be edited
$bouteille->id = $id;
// read the details of bouteille to be edited
$bouteille->readOne();
// Really owner of the bottle ? 
if ($_SESSION['id_utilisateur'] != $bouteille->id_utilisateur ) {
    header('Location: index.php');
}
// Annee courante
$temp=date("Y");

// Si formulaire soumis et que utilisateur connecté 
if($_POST && $_SESSION && isset($_SESSION['id_utilisateur']))
{           
	// set bouteille property values
	if (isset($_POST['nom']))   			$bouteille->nom = $_POST['nom'];
	if (isset($_POST['quantite']))   		$bouteille->quantite = $_POST['quantite'];
	if (isset($_POST['achat']))   			$bouteille->achat = $_POST['achat'];
	if (isset($_POST['prixachat']))   		$bouteille->prixachat = $_POST['prixachat'];
	if (isset($_POST['prixestime']))   		$bouteille->prixestime = $_POST['prixestime'];
	if (isset($_POST['millesime']))   		$bouteille->millesime = $_POST['millesime'];
	if (isset($_POST['apogee']))   			$bouteille->apogee = $_POST['apogee'];
	if (isset($_POST['id_contenance']))   	$bouteille->id_contenance = $_POST['id_contenance'];
    if (isset($_POST['nomCepage']))   		$bouteille->nomCepage = $_POST['nomCepage'];
	if (isset($_POST['id_aoc']))   			$bouteille->id_aoc = $_POST['id_aoc'];
	if (isset($_POST['id_type']))   		$bouteille->id_type = $_POST['id_type'];
	if (isset($_POST['id_emplacement']))	$bouteille->id_emplacement = $_POST['id_emplacement'];
	if (isset($_POST['empl_x']))			$bouteille->empl_x = $_POST['empl_x'];
	if (isset($_POST['empl_y']))			$bouteille->empl_y = $_POST['empl_y'];
	if (isset($_POST['commentaire']))   	$bouteille->commentaire = $_POST['commentaire'];


	// Upload image 
	if ($_POST['debug']) {
		print_r($_FILES);
		print_r($_FILES['file']);
		print_r("name:".$_FILES['file']['name']."<br>");
		print_r("temp name:".$_FILES['file']['tmp_name']."<br>");
		print_r("error:".$_FILES['file']['error']."<br>");
		print_r("size:".$_FILES['file']['size']."<br>");
	}
	if (isset($_FILES) && isset($_FILES['file']) && isset($_FILES['file']['name'])  && strlen($_FILES['file']['name'])>0 ) {
		$name     = $_FILES['file']['name'];
		$tmpName  = $_FILES['file']['tmp_name'];
		$error    = $_FILES['file']['error'];
		$size     = $_FILES['file']['size'];
		$ext      = strtolower(pathinfo($name, PATHINFO_EXTENSION));
		$response = "";
		$success=false;
		switch ($error) {
	      case UPLOAD_ERR_OK:
	          $valid = true;
              //validate file extensions
              if ( !in_array($ext, array('jpg','jpeg','png','gif')) ) {
                  $valid = false;
                  $response = "L'extension du fichier est invalide.";
              }
              //validate file size
              if ( $size/1024/1024 > 2 ) {
                  $valid = false;
                  $response = "La taille du fichier a dépassé la taille autorisée.";
              }
	          //upload file
	          if ($valid) {
	          	  // Replace - by _
	          	  $name=str_replace('-', '_', $name);
	          	  // replace accents
	          	  $name=wd_remove_accents($name);
                  // Hebergeur bloque tous les fichiers dont le nom contient 'chat'
                  if (!isLocalhost()) {
                      $name = str_replace("chat", "ch_at", $name);
                  }
                  $nomPhoto=$_SESSION['id_utilisateur'].'-'.$bouteille->id.'-'.$name;
	              $targetPath =  dirname( __FILE__ ) . DIRECTORY_SEPARATOR. 'uploads' . DIRECTORY_SEPARATOR.$nomPhoto;
	              $success=move_uploaded_file($tmpName,$targetPath);
	          }
	          break;
              case UPLOAD_ERR_INI_SIZE:
                  $response = "La taille du fichier dépasse la taille autorisée par le fichier php.ini.";
                  break;
              case UPLOAD_ERR_FORM_SIZE:
                  $response = "La taille du fichier dépasse la directive MAX_FILE_SIZE du formulaire.";
                  break;
              case UPLOAD_ERR_PARTIAL:
                  $response = "Le fichier a été partiellement téléchargé.";
                  break;
              case UPLOAD_ERR_NO_FILE:
                  $response = "Aucun fichier n'a été téléchargé.";
                  break;
              case UPLOAD_ERR_NO_TMP_DIR:
                  $response = "Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.";
                  break;
              case UPLOAD_ERR_CANT_WRITE:
                  $response = "Erreur d'écriture du fichier sur le disque (PHP 5.1.0).";
                  break;
              case UPLOAD_ERR_EXTENSION:
                  $response = "Le fichier téléchargé a été stoppé par son extension (PHP 5.2.0).";
                  break;
              default:
                  $response = "Erreur inconnu";
	      break;
	  	}
	if ($_POST['debug']) {
		print_r("success:".$success."<br>");
	}
		if (!$success) {
			// Affichage d'un message d'erreur suite à l'upload de la photo
			echo "<div class=\"alert alert-danger alert-dismissable\">";
			echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
			echo $response;                   
			echo "</div>";
		} else  {
			echo "<div class=\"alert alert-success alert-dismissable\">";
			  echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
			  echo "La photo de la bouteille <strong>".wd_remove_accents($name)."</strong> a été mise à jour :-)";
			echo "</div>";			
			$bouteille->nomPhoto = $nomPhoto;
		}
	}
	// update the bouteille
	if($bouteille->update()){
	echo "<div class=\"alert alert-success alert-dismissable\">";
	    echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
	    echo "Le vin <strong>".$_POST['nom']."</strong> a été mis à jour :-)";
	echo "</div>";
	}
	// if unable to update the bouteille, tell the user
	else{
	echo "<div class=\"alert alert-danger alert-dismissable\">";
	    echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
	    echo "Problème de mise à jour du vin";
	echo "</div>";
	}
}

echo "<div class='row hidden-sm hidden-xs'>";
echo "<div class='col-md-12 right-button-margin'>";
echo "<a href='index.php' class='btn btn-primary pull-right' style='margin-left:10px;'>Liste des vins</a>";
if ($_SESSION && isset($_SESSION['id_utilisateur']) ) {
	echo "<a href='ajout_bouteille.php' class='btn  btn-primary pull-right'>Ajouter un vin </a>";
}
echo "</div>";
echo "</div><br>";
?>

<div class="row">
<div class="col-md-3 hidden-sm hidden-xs">
	<?php
		if (isset($bouteille->nomPhoto) && strlen($bouteille->nomPhoto)>0) {
			echo "<img src='uploads/{$bouteille->nomPhoto}' alt='{$bouteille->nom}' class='viewBottle'>";
		} else {
			echo "<img src='img/fond.png' alt='verre' class='viewBottle'>";
		}
	?>
</div>	
<div class="col-md-9 col-sm-12 col-xs-12">
<!-- Formulaire d'ajout d'une bouteille  -->
<form action='maj_bouteille.php?id=<?php echo $id; ?>' method='post'  class="form-horizontal"  enctype="multipart/form-data">

	<div class="form-group">
		<label for="nomBouteille" class="col-sm-2 control-label">Nom</label>
		<div class="col-sm-10">
		<input type="text"  name='nom' class="form-control" id="nomBouteille" value='<?php echo $bouteille->nom; ?>' >
		<input type="hidden"  name='debug' class="form-control" id="nomBouteille" value='<?php echo $debug; ?>' >
		</div>
	</div>


  <div class="form-group">
      <label for="file" class="col-sm-2 control-label">Etiquette</label>
      <div class="col-sm-10">
        <label class="btn btn-sm btn-primary btn-file">
          Sélection de l'image <input type="file" name="file" style="display: none;" onchange="$('#upload-file-info').html($(this).val());">
        </label>
        <?php
        	$tmp="";
        	if (isset($bouteille->nomPhoto) && strlen($bouteille->nomPhoto)>0) {
	        	$tmpPhoto = explode("-", $bouteille->nomPhoto);
	        	$tmp=$tmpPhoto[2];
			}        	
		    echo "<span class='label label-info' id='upload-file-info'>".$tmp."</span>";
        ?>
    </div>
  </div>


	<div class="form-group">
	<label for="id_type" class="col-sm-2 col-xs-4 control-label">Type</label>
	<div class="col-sm-10 col-xs-8">
		<?php
		// read the bouteille categories from the database
		include_once 'objects/Type.php';
		$type = new Type($db);
		$stmt = $type->read();
		// put them in a select drop-down
		echo "<select class='form-control' name='id_type'>";
		echo "<option>Choisir le type ...</option>";
		while ($row_cepage = $stmt->fetch(PDO::FETCH_ASSOC)){
			extract($row_cepage);
			// current cepage of the product must be selected
			if($bouteille->id_type==$id){
			echo "<option value='$id' selected>";
			}else{
			echo "<option value='$id'>";
			}
			echo "$libelle</option>";
		}
		echo "</select>";
		?>
	</div>
	</div>

 
	<div class="form-group">
	<label for="id_contenance" class="col-sm-2 col-xs-4 control-label">Contenance</label>
	<div class="col-sm-10 col-xs-8">
	<?php
		// read the bouteille categories from the database
		include_once 'objects/Contenance.php';
		$contenance = new Contenance($db);
		$stmt = $contenance->read();
		// put them in a select drop-down
		echo "<select class='form-control' name='id_contenance'>";
		echo "<option>Choisir la contenance ...</option>";
		while ($row_contenance = $stmt->fetch(PDO::FETCH_ASSOC)){
			extract($row_contenance);
			// current cepage of the product must be selected
			if($bouteille->id_contenance==$id){
			echo "<option value='$id' selected>";
			}else{
			echo "<option value='$id'>";
			}
			echo "$nom ($volume soit $equivalence)</option>";
		}
		echo "</select>";
	?>

	</div>
	</div>

	<div class="form-group">
	<label for="quantite" class="col-sm-2  col-xs-4 control-label">Quantité</label>
	<div class="col-sm-10 col-xs-8">
	<input type="text"  name='quantite' class="form-control" id="quantite" value='<?php echo $bouteille->quantite; ?>'  >
	</div>
	</div>

	<div class="form-group">
	<label for="prix" class="col-sm-2 col-xs-4 control-label">Prix</label>
	<div class="col-sm-5  col-xs-4 ">
			<div class="input-group prix">
			  <span class="input-group-addon glyphicon glyphicon-euro" aria-hidden="true"></span>
			  <input type='text' name="prixachat" class='form-control' aria-describedby="sizing-addon2" value='<?php echo $bouteille->prixachat; ?>'>
			</div>
	</div>
	<div class="col-sm-5  col-xs-4 ">
			<div class="input-group prix">
			  <span class="input-group-addon glyphicon glyphicon-euro" aria-hidden="true"></span>
			  <input type='text' name='prixestime' class='form-control' aria-describedby="sizing-addon2" value='<?php echo $bouteille->prixestime; ?>' >
			</div>
	</div>
	</div>

	<div class="form-group">
		<label for="commentaire" class="col-sm-2 control-label">Commentaire</label>
		<div class="col-sm-10">
		<textarea class="form-control" rows="3" id="commentaire" name="commentaire" ><?php echo $bouteille->commentaire; ?></textarea>
		</div>
	</div>

	<div class="form-group">
		<label for="achat" class="col-sm-2 col-xs-4 control-label">Achat</label>
		<div class="col-sm-5 col-xs-8">
				<input type='text' name="achat" id="achat" class='form-control' value='<?php echo $bouteille->achat; ?>' >
		</div>
		<div class="col-sm-5 hidden-xs">
			<input type='text' id="sliderAchat" data-slider-min="2000" data-slider-max="2050" data-slider-step="1" data-slider-value="<?php echo $bouteille->achat; ?>" >
		</div>
	</div>

	<div class="form-group">
		<label for="millesime" class="col-sm-2 col-xs-4 control-label">Millésime</label>
		<div class="col-sm-5 col-xs-8">
				<input type='text' name="millesime" id="millesime" class='form-control' value='<?php echo $bouteille->millesime; ?>' >
		</div>
		<div class="col-sm-5  hidden-xs">
			<input type='text' id="sliderMillesime" data-slider-min="2000" data-slider-max="2050" data-slider-step="1" data-slider-value="<?php echo $bouteille->millesime; ?>" >
		</div>
	</div>

	<div class="form-group">
	<?php 
		if ($temp>=$bouteille->apogee) {
			echo "<label for='apogee' class='col-sm-2 col-xs-4 control-label apogee'>Apogée</label>";
		}
		else {
			echo "<label for='apogee' class='col-sm-2 col-xs-4 control-label'>Apogée</label>";
		}
	?>
	<div class="col-sm-5 col-xs-8">
	<?php 
		if ($temp>=$bouteille->apogee) {
			echo "<input type='text' name='apogee' id='apogee' class='form-control apogee' value='{$bouteille->apogee}' >";
		}
		else {
			echo "<input type='text' name='apogee' id='apogee' class='form-control' value='{$bouteille->apogee}' >";
		}
	?>

	</div>
	<div class="col-sm-5 hidden-xs">
		<input type='text' id="sliderApogee" data-slider-min="2000" data-slider-max="2050" data-slider-step="1" data-slider-value="<?php echo $bouteille->apogee; ?>" >
	</div>
	</div>


   <div class="form-group">
    <label for="nomCepage" class="col-sm-2 control-label">Cépage</label>
    <div class="col-sm-10">
     <textarea  rows="3" name='nomCepage' class="form-control" id="nomCepage" placeholder="Nom du cépage ..."><?php echo $bouteille->nomCepage; ?></textarea>
    </div>
  </div>
  
    
	<div class="form-group">
	<label for="id_aoc" class="col-sm-2 control-label">Appellation</label>
	<div class="col-sm-10">
		<?php
		// read the bouteille categories from the database
		include_once 'objects/AOC.php';
		$aoc = new AOC($db);
		$stmt = $aoc->read();
		// put them in a select drop-down
		echo "<select class='form-control' name='id_aoc'>";
		echo "<option>Choisir l'AOC ...</option>";
		while ($row_aoc = $stmt->fetch(PDO::FETCH_ASSOC)){
			extract($row_aoc);
			// current aoc of the product must be selected
			if($bouteille->id_aoc==$id){
			    echo "<option value='$id' selected>";
			}else{
			    echo "<option value='$id'>";
			}
			echo "{$appellation}</option>";
		}
		echo "</select>";
		?>
	</div>
	</div>
  
  <?php 
  // Emplacements
  if (isset($_SESSION) && isset($_SESSION['emplacement']) && $_SESSION['emplacement']=='O' ) {
      echo '<div class="form-group">';
      echo '<label for="id_emplacement" class="col-sm-2 control-label">Emplacement</label>';
      echo '<div class="col-sm-10">';
      // Recherche des emplacements en BD
      include_once 'objects/Emplacement.php';
      // Recherche de tous les objets Emplacement
      $emplacement = new Emplacement($db);
      $emplacement->id_utilisateur = $_SESSION['id_utilisateur'];
      $stmtEmplacment = $emplacement->readAll();
      // Remplissage de la liste
      echo "<select class='form-control' name='id_emplacement'>";
      echo "<option>Choisir l'Emplacement ...</option>";

      while ($row_emplacement = $stmtEmplacment->fetch(PDO::FETCH_ASSOC)) {
          extract($row_emplacement);
		    // current emplacement of the product must be selected
		    if($bouteille->id_emplacement==$id){
		        echo "<option value='$id' selected>";
		    }else{
		        echo "<option value='$id'>";
		    }
          echo "{$lieu}</option>";
      }
      echo "</select>";
      echo '</div></div>';
      ?>
      <div class="form-group">
		<label class="col-sm-2 col-xs-4 control-label">Position</label>
		<div class="col-sm-5  col-xs-4 ">
			<div class="input-group">
			  <span class="input-group-addon" aria-hidden="true">X</span>
			  <input type='text' name="empl_x" class='form-control' aria-describedby="sizing-addon2" value='<?php echo $bouteille->empl_x; ?>'>
			</div>
		</div>
		<div class="col-sm-5  col-xs-4 ">
			<div class="input-group">
			  <span class="input-group-addon" aria-hidden="true">Y</span>
			  <input type='text' name='empl_y' class='form-control' aria-describedby="sizing-addon2" value='<?php echo $bouteille->empl_y; ?>' >
			</div>
		</div>
	</div>
	<?php
  }
  ?>


 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">Modifier</button>
    </div>
 
</form>
  </div>
</div>
<br>


 <script type="text/javascript">
    $(document).ready(function() {

  $( function() {
    var availableTags = [
          <?php
            while ($row_cepage = $stmtcepage->fetch(PDO::FETCH_ASSOC)){
                extract($row_cepage);
                echo '"'.$nom.'",';
            }
          ?>
    ];
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( "#nomCepage" )
      // don't navigate away from the field on tab when selecting an item
      .on( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          // delegate back to autocomplete, but extract the last term
          response( $.ui.autocomplete.filter(
            availableTags, extractLast( request.term ) ) );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
  } );


		$("input[name='quantite']").TouchSpin({
			min: 0,
			max: 240,
			boostat: 5,
			maxboostedstep: 10,
			postfix: 'Bouteilles(s)'
		});



		var slider0 = new Slider("#sliderAchat");
		slider0.on("slide", function(slideEvt) {
			$("#achat").val(slideEvt);
		});
		slider0.on("change", function(slideEvt) {
			$("#achat").val(slideEvt.newValue);
		});

		var slider1 = new Slider("#sliderMillesime");
		slider1.on("slide", function(slideEvt) {
			$("#millesime").val(slideEvt);
		});
		slider1.on("change", function(slideEvt) {
			$("#millesime").val(slideEvt.newValue);
		});

		var slider2 = new Slider("#sliderApogee");
		slider2.on("slide", function(slideEvt) {
			$("#apogee").val(slideEvt);
		});
		slider2.on("change", function(slideEvt) {
			$("#apogee").val(slideEvt.newValue);
		});


		$('#nomBouteille').autocomplete({
			source: 'autocomplete.php',
			minLength: 2, //search after two characters
			dataType: 'json'
		});

    });

      </script>



<?php
include_once "footer.php";
?>

