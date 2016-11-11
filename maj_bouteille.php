<?php
$page_title = "Mise à jour d'une bouteille";
include_once "header.php";

if (!$_SESSION || !isset($_SESSION['id_utilisateur']) ) {
    header('Location: index.php');
}
//include database and object files
include_once 'config/database.php';
include_once 'objects/Bouteille.php';
include_once 'objects/Cepage.php';     
 
$database = new Database();
$db = $database->getConnection();
$cepage = new Cepage($db);
$stmtcepage = $cepage->read();

// prepare bouteille object
$bouteille = new Bouteille($db);
// get ID of the bouteille to be edited
$id = isset($_GET['id']) ? $_GET['id'] : die("Erreur ! Il manque l'identifiant de la bouteille");
// set ID property of bouteille to be edited
$bouteille->id = $id;
// read the details of bouteille to be edited
$bouteille->readOne();
// Annee courante
$temp=date("Y");

// Si formulaire soumis et que utilisateur connecté 
if($_POST && $_SESSION && isset($_SESSION['id_utilisateur']))
{           
	// set bouteille property values
	if (isset($_POST['nom']))   			$bouteille->nom = $_POST['nom'];
	if (isset($_POST['quantite']))   		$bouteille->quantite = $_POST['quantite'];
	if (isset($_POST['achat']))   		$bouteille->achat = $_POST['achat'];
	if (isset($_POST['prixachat']))   	$bouteille->prixachat = $_POST['prixachat'];
	if (isset($_POST['prixestime']))   	$bouteille->prixestime = $_POST['prixestime'];
	if (isset($_POST['millesime']))   	$bouteille->millesime = $_POST['millesime'];
	if (isset($_POST['apogee']))   		$bouteille->apogee = $_POST['apogee'];
	if (isset($_POST['id_contenance']))   $bouteille->id_contenance = $_POST['id_contenance'];
    if (isset($_POST['nomCepage']))   $bouteille->nomCepage = $_POST['nomCepage'];
	if (isset($_POST['id_aoc']))   		$bouteille->id_aoc = $_POST['id_aoc'];
	if (isset($_POST['id_type']))   		$bouteille->id_type = $_POST['id_type'];
	if (isset($_POST['id_emplacement']))	$bouteille->id_emplacement = $_POST['id_emplacement'];
	if (isset($_POST['commentaire']))   	$bouteille->commentaire = $_POST['commentaire'];

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

echo "<div class='row'>";
echo "<div class='col-md-12 right-button-margin'>";
echo "<a href='index.php' class='btn btn-primary pull-right' style='margin-left:10px;'>Liste des vins</a>";
if ($_SESSION && isset($_SESSION['id_utilisateur']) ) {
	echo "<a href='ajout_bouteille.php' class='btn  btn-primary pull-right'>Ajouter un vin </a>";
}
echo "</div>";
echo "</div>";
?>

<div class="row">
<div class="col-md-4"><img src="img/fond.png" alt="verre"></div>
<div class="col-md-8">
<!-- Formulaire d'ajout d'une bouteille  -->
<form action='maj_bouteille.php?id=<?php echo $id; ?>' method='post'  class="form-horizontal">

	<div class="form-group">
		<label for="nomBouteille" class="col-sm-2 control-label">Nom</label>
		<div class="col-sm-10">
		<input type="text"  name='nom' class="form-control" id="nomBouteille" value='<?php echo $bouteille->nom; ?>' >
		</div>
	</div>

	<div class="form-group">
	<label for="id_type" class="col-sm-2 control-label">Type</label>
	<div class="col-sm-10">
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
	<label for="id_contenance" class="col-sm-2 control-label">Contenance</label>
	<div class="col-sm-10">
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
	<label for="quantite" class="col-sm-2 control-label">Quantité</label>
	<div class="col-sm-10">
	<input type="text"  name='quantite' class="form-control" id="quantite" value='<?php echo $bouteille->quantite; ?>'  >
	</div>
	</div>

	<div class="form-group">
	<label for="prix" class="col-sm-2 control-label">Prix achat / estimé</label>
	<div class="col-sm-5">
			<div class="input-group prix">
			  <span class="input-group-addon glyphicon glyphicon-euro" aria-hidden="true"></span>
			  <input type='text' name="prixachat" class='form-control' aria-describedby="sizing-addon2" value='<?php echo $bouteille->prixachat; ?>'>
			</div>
	</div>
	<div class="col-sm-5">
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
		<label for="achat" class="col-sm-2 control-label">Achat</label>
		<div class="col-sm-5">
				<input type='text' name="achat" id="achat" class='form-control' value='<?php echo $bouteille->achat; ?>' >
		</div>
		<div class="col-sm-5">
			<input type='text' id="sliderAchat" data-slider-min="2000" data-slider-max="2050" data-slider-step="1" data-slider-value="<?php echo $bouteille->achat; ?>" >
		</div>
	</div>

	<div class="form-group">
		<label for="millesime" class="col-sm-2 control-label">Millésime</label>
		<div class="col-sm-5">
				<input type='text' name="millesime" id="millesime" class='form-control' value='<?php echo $bouteille->millesime; ?>' >
		</div>
		<div class="col-sm-5">
			<input type='text' id="sliderMillesime" data-slider-min="2000" data-slider-max="2050" data-slider-step="1" data-slider-value="<?php echo $bouteille->millesime; ?>" >
		</div>
	</div>

	<div class="form-group">
	<?php 
		if ($temp>=$bouteille->apogee) {
			echo "<label for='apogee' class='col-sm-2 control-label apogee'>Apogée</label>";
		}
		else {
			echo "<label for='apogee' class='col-sm-2 control-label'>Apogée</label>";
		}
	?>
	<div class="col-sm-5">
	<?php 
		if ($temp>=$bouteille->apogee) {
			echo "<input type='text' name='apogee' id='apogee' class='form-control apogee' value='{$bouteille->apogee}' >";
		}
		else {
			echo "<input type='text' name='apogee' id='apogee' class='form-control' value='{$bouteille->apogee}' >";
		}
	?>

	</div>
	<div class="col-sm-5">
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
  }
  ?>


 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">Modifier</button>
    </div>
 
</form>
  </div>
</div>


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
			max: 120,
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

