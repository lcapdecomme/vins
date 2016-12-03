<?php
// Entête
$page_title = "Ajout d'une bouteille";
include_once "header.php";

// Connexion DB
include_once 'config/database.php';
include_once 'config/util.php';
include_once 'objects/Bouteille.php';
include_once 'objects/Referentiel.php';
include_once 'objects/AOC.php';
include_once 'objects/Cepage.php';     
 
$database = new Database();
$db = $database->getConnection();
$cepage = new Cepage($db);
$stmtcepage = $cepage->read();

if (!$_SESSION || !isset($_SESSION['id_utilisateur']) ) {
    header('Location: index.php');
}

// Si formulaire soumis et que utilisateur connecté 
if($_POST )
{
      try{
            // Nouvel objet Bouteille
            $bouteille = new Bouteille($db);
         
            // Valeurs des propriétés de la bouteille
            if (isset($_POST['nom']))                 $bouteille->nom = $_POST['nom'];
            if (isset($_POST['quantite']))            $bouteille->quantite = $_POST['quantite'];
            if (isset($_POST['achat']))               $bouteille->achat = $_POST['achat'];
            if (isset($_POST['prixachat']))           $bouteille->prixachat = $_POST['prixachat'];
            if (isset($_POST['prixestime']))          $bouteille->prixestime = $_POST['prixestime'];
            if (isset($_POST['millesime']))           $bouteille->millesime = $_POST['millesime'];
            if (isset($_POST['apogee']))              $bouteille->apogee = $_POST['apogee'];
            if (isset($_POST['id_contenance']))       $bouteille->id_contenance = $_POST['id_contenance'];
            if (isset($_POST['nomCepage']))           $bouteille->nomCepage = $_POST['nomCepage'];
            if (isset($_POST['id_aoc']))              $bouteille->id_aoc = $_POST['id_aoc'];
            if (isset($_POST['id_type']))             $bouteille->id_type = $_POST['id_type'];
            if (isset($_POST['id_emplacement']))      $bouteille->id_emplacement = $_POST['id_emplacement'];
            if (isset($_POST['id_fournisseur']))      $bouteille->id_fournisseur = $_POST['id_fournisseur'];
            if (isset($_POST['commentaire']))         $bouteille->commentaire = $_POST['commentaire'];
            if (isset($_POST['empl_x']))              $bouteille->empl_x = $_POST['empl_x'];
            if (isset($_POST['empl_y']))              $bouteille->empl_y = $_POST['empl_y'];
            if (isset($_SESSION['id_utilisateur']))   $bouteille->id_utilisateur = $_SESSION['id_utilisateur'];
         
            // Ajout d'une bouteille
            if($bouteille->create()) {
                echo "<div class=\"alert alert-success alert-dismissable\">";
                    echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                    echo "La bouteille <strong>".$_POST['nom']."</strong> a été ajoutée :-)";
                echo "</div>";

              // Upload image 1
              if (isset($_FILES) && isset($_FILES['file1']) && isset($_FILES['file1']['name'])  && strlen($_FILES['file1']['name'])>0 ) {
                  $name     = $_FILES['file1']['name'];
                  $tmpName  = $_FILES['file1']['tmp_name'];
                  $error    = $_FILES['file1']['error'];
                  $size     = $_FILES['file1']['size'];
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
                              $nomPhoto=$_SESSION['id_utilisateur'].'-'.$bouteille->id.'-1-'.$name;
                              $targetPath =  dirname( __FILE__ ) . DIRECTORY_SEPARATOR. UPLOAD_DIRECTORY. DIRECTORY_SEPARATOR.$nomPhoto;
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
                  if (!$success) {
                    // Affichage d'un message d'erreur suite à l'upload de la photo
                    echo "<div class=\"alert alert-danger alert-dismissable\">";
                      echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                      echo $response;                   
                    echo "</div>";
                  } else {
                    // Mise à jour du nom de la photo dans la base
                    if (isset($nomPhoto)) {
                        $bouteille->nomPhoto = $nomPhoto;
                        if($bouteille->update()){
                          echo "<div class=\"alert alert-success alert-dismissable\">";
                              echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                              echo "La photo <b>recto</b> de la bouteille a été mise à jour :-)";
                          echo "</div>";
                        } 
                    }
                  }
              }

              // Upload image 2
               if (isset($_FILES) && isset($_FILES['file2']) && isset($_FILES['file2']['name'])  && strlen($_FILES['file2']['name'])>0 ) {
                  $name     = $_FILES['file2']['name'];
                  $tmpName  = $_FILES['file2']['tmp_name'];
                  $error    = $_FILES['file2']['error'];
                  $size     = $_FILES['file2']['size'];
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
                              $nomPhoto=$_SESSION['id_utilisateur'].'-'.$bouteille->id.'-2-'.$name;
                              $targetPath =  dirname( __FILE__ ) . DIRECTORY_SEPARATOR. UPLOAD_DIRECTORY . DIRECTORY_SEPARATOR.$nomPhoto;
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
                  if (!$success) {
                    // Affichage d'un message d'erreur suite à l'upload de la photo
                    echo "<div class=\"alert alert-danger alert-dismissable\">";
                      echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                      echo $response;                   
                    echo "</div>";
                  } else {
                    // Mise à jour du nom de la photo dans la base
                    if (isset($nomPhoto)) {
                        $bouteille->nomPhoto2 = $nomPhoto;
                        if($bouteille->update()){
                          echo "<div class=\"alert alert-success alert-dismissable\">";
                              echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                              echo "La photo <b>verso</b> de la bouteille a été mise à jour :-)";
                          echo "</div>";
                        } 
                    }
                  }
              }

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
                    echo "Le vin <strong>".$_POST['nom']."</strong> a été ajouté au référentiel :-)";
                    echo "</div>";
                    }
                    else{
                    echo "<div class=\"alert alert-danger alert-dismissable\">";
                    echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                    echo "Impossible d'ajouter ce vin au référentiel";
                    echo "</div>";
                    }
               }
            }
            // Problème d'ajout d'une bouteille
            else{
                echo "<div class=\"alert alert-danger alert-dismissable\">";
                    echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                    echo "Impossible d'ajouter ce vin {$bouteille->nom} : {$bouteille->error}";
                echo "</div>";
            }

    }catch(Exception $exception){
        echo "!!!! : " . $exception->getMessage();
    }
}


?>
<div class='row hidden-sm hidden-xs'>
  <div class='col-md-12 right-button-margin'>
    <a href='index.php' class='btn btn-primary pull-right'>Liste des vins</a>
  </div>
</div>
<br>

<div class="row">
  <div class="col-md-4 hidden-sm hidden-xs"><img  class='viewBottle' src="img/fond.png" alt="verre"></div>
  <div class="col-md-8 col-sm-12 col-xs-12">
<!-- Formulaire d'ajout d'une bouteille  -->
<form action='ajout_bouteille.php' method='POST'  class="form-horizontal"  enctype="multipart/form-data">
 
   <div class="form-group">
    <label for="nomBouteille" class="col-sm-2 control-label">Nom</label>
    <div class="col-sm-10">
      <input type="text"  name='nom' class="form-control" id="nomBouteille" placeholder="Nom de la bouteille ..." required >
    </div>
  </div>

  <div class="form-group">
      <label for="file" class="col-sm-2 control-label">Etiquette</label>
      <div class="col-sm-10">
        <label class="btn btn-sm btn-primary btn-file">
          Recto<input type="file" name="file1" style="display: none;" onchange="$('#upload-file-info1').html($(this).val());">
        </label>
        <span class='label label-info' id="upload-file-info1"></span>
    </div>
  </div>

  <div class="form-group">
      <label for="file" class="col-sm-2 control-label"></label>
      <div class="col-sm-10">
        <label class="btn btn-sm btn-primary btn-file">
          Verso<input type="file" name="file2" style="display: none;" onchange="$('#upload-file-info2').html($(this).val());">
        </label>
        <span class='label label-info' id="upload-file-info2"></span>
    </div>
  </div>

   <div class="form-group">
    <label for="id_type" class="col-sm-2 col-xs-4 control-label">Type</label>
    <div class="col-sm-10 col-xs-8">
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
    <label for="id_contenance" class="col-sm-2 col-xs-4 control-label">Contenance</label>
    <div class="col-sm-10 col-xs-8">
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
    <label for="quantite" class="col-sm-2 col-xs-4 control-label">Quantité</label>
    <div class="col-sm-10 col-xs-8">
      <input type="text"  name='quantite' class="form-control" id="quantite" >
    </div>
  </div>


   <div class="form-group">
    <label for="prix" class="col-sm-2 col-xs-4 control-label">Prix</label>
    <div class="col-sm-5 col-xs-4">
				<div class="input-group prix">
				  <span class="input-group-addon glyphicon glyphicon-euro" aria-hidden="true"></span>
				  <input type='text' name="prixachat" class='form-control' aria-describedby="sizing-addon2" placeholder="Prix d'achat">
				</div>
     </div>
    <div class="col-sm-5 col-xs-4">
				<div class="input-group prix">
				  <span class="input-group-addon glyphicon glyphicon-euro" aria-hidden="true"></span>
				  <input type='text' name='prixestime' class='form-control' aria-describedby="sizing-addon2" placeholder="Prix estimé">
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
    <label for="achat" class="col-sm-2 col-xs-4 control-label">Achat</label>
    <div class="col-sm-5 col-xs-8">
        <input type='text' name="achat" id="achat" class='form-control' placeholder="Date d'achat" value="<?php echo $temp; ?>">
    </div>
    <div class="col-sm-5 hidden-xs">
      <input type='text' id="sliderAchat" data-slider-min="2000" data-slider-max="2050" data-slider-step="1" data-slider-value="<?php echo $temp; ?>" >
    </div>
  </div>
 
  <?php 
  // Fournisseurs
  if (isset($_SESSION) && isset($_SESSION['fournisseur']) && $_SESSION['fournisseur']=='O' ) {
      echo '<div class="form-group">';
      echo '<label for="id_fournisseur" class="col-sm-2 control-label">Fournisseur</label>';
      echo '<div class="col-sm-10">';
      // Recherche des fournisseurs en BD
      include_once 'objects/Fournisseur.php';
      // Recherche de tous les objets Fournisseur
      $fournisseur = new Fournisseur($db);
      $fournisseur->id_utilisateur = $_SESSION['id_utilisateur'];
      $stmttFournisseur = $fournisseur->readAll();
      // Remplissage de la liste
      echo "<select class='form-control' name='id_fournisseur'>";
      echo "<option>Choisir le fournisseur ...</option>";

      while ($row_fournisseur = $stmttFournisseur->fetch(PDO::FETCH_ASSOC)) {
          extract($row_fournisseur);
          echo "<option value='$id'>{$nom} ({$cp} {$ville})</option>";
      }
      echo "</select>";
      echo '</div></div>';
      }
  ?>


   <div class="form-group">
    <label for="millesime" class="col-sm-2 col-xs-4 control-label">Millésime</label>
    <div class="col-sm-5 col-xs-8">
        <input type='text' name="millesime" id="millesime" class='form-control' placeholder="Millésime" value="<?php echo $millesimeAnnee; ?>">
    </div>
    <div class="col-sm-5 hidden-xs">
      <input type='text' id="sliderMillesime" data-slider-min="2000" data-slider-max="2050" data-slider-step="1" data-slider-value="<?php echo $millesimeAnnee; ?>" >
    </div>
  </div>

   <div class="form-group">
    <label for="apogee" class="col-sm-2 col-xs-4 control-label">Apogée</label>
    <div class="col-sm-5 col-xs-8">
        <input type='text' name="apogee" id="apogee" class='form-control' placeholder="Apogée" value="<?php echo $apogeeAnnee; ?>">
    </div>
    <div class="col-sm-5 hidden-xs">
      <input type='text' id="sliderApogee" data-slider-min="2000" data-slider-max="2050" data-slider-step="1" data-slider-value="<?php echo $apogeeAnnee; ?>" >
    </div>
  </div>

   <div class="form-group">
    <label for="nomCepage" class="col-sm-2 control-label">Cépage</label>
    <div class="col-sm-10">
     <textarea  rows="3" name='nomCepage' class="form-control" id="nomCepage" placeholder="Nom du cépage ..."></textarea>
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
              echo "<option value='{$id}'>{$lieu}</option>";
          }

      echo "</select>";
      echo '</div></div>';
      ?>
      <div class="form-group">
        <label class="col-sm-2 col-xs-4 control-label">Position</label>
        <div class="col-sm-5  col-xs-4 ">
          <div class="input-group">
            <span class="input-group-addon" aria-hidden="true">X</span>
            <input type='text' name="empl_x" class='form-control' aria-describedby="sizing-addon2">
          </div>
        </div>
        <div class="col-sm-5  col-xs-4 ">
          <div class="input-group">
            <span class="input-group-addon" aria-hidden="true">Y</span>
            <input type='text' name='empl_y' class='form-control' aria-describedby="sizing-addon2">
          </div>
        </div>
      </div>
      <?php
  }
  ?>

    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">Ajouter</button>
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

