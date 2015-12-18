<?php
// Entête
$page_title = "Ajout d'une bouteille";
include_once "header.php";

// Connexion DB
include_once 'config/database.php';
include_once 'objects/Bouteille.php';
include_once 'objects/Referentiel.php';
include_once 'objects/AOC.php';
 
$database = new Database();
$db = $database->getConnection();


// Si formulaire soumis et que utilisateur connecté 
if($_POST){
 
        try{
              // Nouvel objet Bouteille
              $bouteille = new Bouteille($db);
           
              // Valeurs des propriétés de la bouteille
              if (isset($_POST['nom']))   $bouteille->nom = $_POST['nom'];
              if (isset($_POST['quantite']))   $bouteille->quantite = $_POST['quantite'];
              if (isset($_POST['achat']))   $bouteille->achat = $_POST['achat'];
              if (isset($_POST['prixachat']))   $bouteille->prixachat = $_POST['prixachat'];
              if (isset($_POST['prixestime']))   $bouteille->prixestime = $_POST['prixestime'];
              if (isset($_POST['millesime']))   $bouteille->millesime = $_POST['millesime'];
              if (isset($_POST['apogee']))   $bouteille->apogee = $_POST['apogee'];
              if (isset($_POST['id_contenance']))   $bouteille->id_contenance = $_POST['id_contenance'];
              if (isset($_POST['id_cepage']))   $bouteille->id_cepage = $_POST['id_cepage'];
              if (isset($_POST['id_aoc']))   $bouteille->id_aoc = $_POST['id_aoc'];
              if (isset($_POST['id_type']))   $bouteille->id_type = $_POST['id_type'];
              if (isset($_POST['id_emplacement']))   $bouteille->id_emplacement = $_POST['id_emplacement'];
              if (isset($_POST['commentaire']))   $bouteille->commentaire = $_POST['commentaire'];
              $bouteille->id_utilisateur = 0;
           
              // Ajout d'une bouteille
              if($bouteille->create()){
                  echo "<div class=\"alert alert-success alert-dismissable\">";
                      echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                      echo "La bouteille <strong>".$_POST['nom']."</strong> a été ajoutée :-)";
                  echo "</div>";

                // Nouvel objet Referentiel
                $referentiel = new Referentiel($db);   
                if ($referentiel->readOne($bouteille->nom) == False) {
                  if ($bouteille->id_aoc) {
                    // Appellation 
                    $aoc = new AOC($db);   
                    $aoc->id = $bouteille->id_aoc;
                    $aoc->readName();
                    if ($aoc->region) {
                      $referentiel->region = $aoc->region; 
                    }
                    else {
                      $referentiel->region = '';
                    }
                  }
                  else {
                      $referentiel->region = '';
                    }
                  // Referentiel
                  $referentiel->nom = $bouteille->nom;
                  if ($bouteille->id_type) {
                    $referentiel->id_type = $bouteille->id_type; 
                  }
                  else {
                    $referentiel->id_type = NULL;
                  }
                  
                  if($referentiel->create()){
                    echo "<div class=\"alert alert-success alert-dismissable\">";
                        echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        echo "La bouteille <strong>".$_POST['nom']."</strong> a été ajoutée au référentiel :-)";
                    echo "</div>";
                  }
                  else{
                  echo "<div class=\"alert alert-danger alert-dismissable\">";
                      echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                      echo "Impossible d'ajouter la bouteille au référentiel";
                  echo "</div>";
                }
               }
            }
              // Problème d'ajout d'une bouteille
              else{
                  echo "<div class=\"alert alert-danger alert-dismissable\">";
                      echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                      echo "Impossible d'ajouter la bouteille ".$_POST['nom'];
                  echo "</div>";
              }

      }catch(Exception $exception){
          echo "!!!! : " . $exception->getMessage();
      }
}


echo "<div class='right-button-margin'>";
    echo "<a href='index.php' class='btn  btn-primary pull-right'>Liste des bouteilles</a>";
echo "</div>";

?>

<div class="row">
  <div class="col-md-4"><img src="img/fond.png" alt="verre"></div>
  <div class="col-md-8">
<!-- Formulaire d'ajout d'une bouteille  -->
<form action='ajout_bouteille.php' method='POST'  class="form-horizontal">
 
   <div class="form-group">
    <label for="nomBouteille" class="col-sm-2 control-label">Nom</label>
    <div class="col-sm-10">
      <input type="text"  name='nom' class="form-control" id="nomBouteille" placeholder="Nom de la bouteille ..." required >
    </div>
  </div>

 
   <div class="form-group">
    <label for="id_type" class="col-sm-2 control-label">Type</label>
    <div class="col-sm-10">
      <?php
		    // Recherche les divers types en BD
		    include_once 'objects/Type.php';
		    $type = new Type($db);
		    $stmt = $type->read();
		 
        // Remplissage de la liste
		    echo "<select class='form-control' name='id_type' id='id_type' >";
		        echo "<option>Choisir le type ...</option>";
		 
		        while ($row_type = $stmt->fetch(PDO::FETCH_ASSOC)){
		            extract($row_type);
		            echo "<option value='{$id}'>{$libelle}</option>";
		        }
		 
		    echo "</select>";
		    ?>
    </div>
  </div>

 
   <div class="form-group">
    <label for="id_contenance" class="col-sm-2 control-label">Contenance</label>
    <div class="col-sm-10">
		<?php
		    // Recherche les diverses contenances en BD
		    include_once 'objects/Contenance.php';
		    $nom = new Contenance($db);
		    $stmt = $nom->read();
		 
        // Remplissage de la liste
		    echo "<select class='form-control' name='id_contenance' id='id_contenance'>";
		        echo "<option>Choisir le type ...</option>";
		 
		        while ($row_type = $stmt->fetch(PDO::FETCH_ASSOC)){
		            extract($row_type);

                // Par défaut, la taille 0,75cl est sélectionnée
                if($id==2){
                    echo "<option value='{$id}' selected>{$nom} ({$volume} soit {$equivalence})</option>";
                }else{
                    echo "<option value='{$id}'>{$nom} ({$volume} soit {$equivalence})</option>";
                }

		        }
		 
		    echo "</select>";
		?>
    </div>
  </div>
 
   <div class="form-group">
    <label for="quantite" class="col-sm-2 control-label">Quantité</label>
    <div class="col-sm-10">
      <input type="text"  name='quantite' class="form-control" id="quantite" >
    </div>
  </div>


   <div class="form-group">
    <label for="prix" class="col-sm-2 control-label">Prix</label>
    <div class="col-sm-5">
				<div class="input-group prix">
				  <span class="input-group-addon glyphicon glyphicon-euro" aria-hidden="true"></span>
				  <input type='text' name="prixachat" class='form-control' aria-describedby="sizing-addon2" placeholder="Prix d'achat">
				</div>
     </div>
    <div class="col-sm-5">
				<div class="input-group prix">
				  <span class="input-group-addon glyphicon glyphicon-euro" aria-hidden="true"></span>
				  <input type='text' name='prixestime'class='form-control' aria-describedby="sizing-addon2" placeholder="Prix estimé">
				</div>
     </div>
  </div>


   <div class="form-group">
    <label for="commentaire" class="col-sm-2 control-label">Commentaire</label>
    <div class="col-sm-10">
      <textarea class="form-control" rows="3" id="commentaire" name="commentaire"  placeholder="Appréciations ...."></textarea>
    </div>
  </div>

  <?php
    $temp=date("Y");
    $millesimeAnnee=$temp-2;
    $apogeeAnnee=$temp+3;
  ?>

   <div class="form-group">
    <label for="achat" class="col-sm-2 control-label">Achat</label>
    <div class="col-sm-5">
        <input type='text' name="achat" id="achat" class='form-control' placeholder="Date d'achat" value="<?php echo $temp; ?>">
    </div>
    <div class="col-sm-5">
      <input type='text' id="sliderAchat" data-slider-min="2000" data-slider-max="2050" data-slider-step="1" data-slider-value="<?php echo $temp; ?>" >
    </div>
  </div>

   <div class="form-group">
    <label for="millesime" class="col-sm-2 control-label">Millésime</label>
    <div class="col-sm-5">
        <input type='text' name="millesime" id="millesime" class='form-control' placeholder="Millésime" value="<?php echo $millesimeAnnee; ?>">
    </div>
    <div class="col-sm-5">
      <input type='text' id="sliderMillesime" data-slider-min="2000" data-slider-max="2050" data-slider-step="1" data-slider-value="<?php echo $millesimeAnnee; ?>" >
    </div>
  </div>

   <div class="form-group">
    <label for="apogee" class="col-sm-2 control-label">Apogée</label>
    <div class="col-sm-5">
        <input type='text' name="apogee" id="apogee" class='form-control' placeholder="Apogée" value="<?php echo $apogeeAnnee; ?>">
    </div>
    <div class="col-sm-5">
      <input type='text' id="sliderApogee" data-slider-min="2000" data-slider-max="2050" data-slider-step="1" data-slider-value="<?php echo $apogeeAnnee; ?>" >
    </div>
  </div>

 
   <div class="form-group">
    <label for="id_cepage" class="col-sm-2 control-label">Cépage</label>
    <div class="col-sm-10">
		<?php
		    // Recherche les divers cépages en BD
		    include_once 'objects/Cepage.php';		 
		    $cepage = new Cepage($db);
		    $stmt = $cepage->read();
		 
        // Remplissage de la liste
		    echo "<select class='form-control' name='id_cepage'>";
		        echo "<option>Choisir le cépage ...</option>";
		 
		        while ($row_cepage = $stmt->fetch(PDO::FETCH_ASSOC)){
		            extract($row_cepage);
		            echo "<option value='{$id}'>{$nom}</option>";
		        }
		 
		    echo "</select>";
		    ?>
    </div>
  </div>
  
   <div class="form-group">
    <label for="id_aoc" class="col-sm-2 control-label">Appellation</label>
    <div class="col-sm-10">
        <?php
        // Recherche les diverses appellations en BD
        include_once 'objects/AOC.php';
        $aoc = new AOC($db);
        $stmt = $aoc->read();

        // Remplissage de la liste
        echo "<select class='form-control' name='id_aoc'>";
            echo "<option>Choisir l'AOC ...</option>";
     
            while ($row_aoc = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row_aoc);
                echo "<option value='{$id}'>{$appellation}</option>";
            }
     
        echo "</select>";
        ?>
    </div>
  </div>
   

    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">Ajouter</button>
    </div>
 
</form>
  </div>
</div>


 <script type="text/javascript">
    $(document).ready(function() {

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
          minLength: 2, // Recherche après deux caractères
          dataType: 'json',
          select: function( event, ui ) {  
            event.preventDefault();
            // Quand sélection d'un vin, sélection du bon type dans la liste (Rouge, blanc, etc.)
            $('#id_type option[value="'+ui.item.id_type+'"]').prop('selected', true);
            $("#nomBouteille").val(ui.item.label);
            return false;
          }
        });

    });
  </script>


<?php
include_once "footer.php";
?>

