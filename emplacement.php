<?php
// Entête
$page_title = "Mes emplacements";
include_once "header.php";

// Connexion DB
include_once 'config/database.php';
include_once 'objects/Emplacement.php';

if (!$_SESSION || !isset($_SESSION['id_utilisateur']) ) {
    header('Location: index.php');
}

$database = new Database();
$db = $database->getConnection();

// Recherche de tous les objets Emplacement
$emplacement = new Emplacement($db);
$emplacement->id_utilisateur = $_SESSION['id_utilisateur'];
$stmt = $emplacement->readAll();
$num = $stmt->rowCount();

echo "<div class='row'>";
echo "<div  class='col-md-12 right-button-margin'>";
    echo "<button type='button' class='btn btn-primary pull-right' id='AjouterEmplacement'>Ajouter un emplacement</button>";
echo "</div>";
echo "</div><br>";
?>


<!-- /.row -->
<div class="row">
    <label id="messageRecherche" class="warning text-left"></label>
    <div class="col-lg-12">
        <table class='table table-striped table-hover table-responsive'>
            <tr>
                <th>Emplacement</th>
                <th>Opérations</th>
            </tr>
            <tbody>
                <?php
                $total=0;
                if($num>0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        echo "<tr>";
                            echo "<td>{$lieu}</td>";
                            echo "<td class='text-center' >";
                            echo "<a href='#' data-id={$id}  class='rechercheEmplacement btn btn-primary btn-xs' title='Modification'>";
                            echo "<span class='glyphicon glyphicon-ok' aria-hidden='true'></span></a>&nbsp;";
                            echo "<a href='#' data-id={$id}  class='suppressionEmplacement btn btn-primary btn-xs' title='Suppression'>";
                            echo "<span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a></td>";
                        echo "</tr>";
                    }    
                }
                ?>
            </tbody>
            <!-- /.panel-body -->
        </table>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>


<!-- Modal - MAJ d'un objet -->
<div class="modal fade" id="myEmplacementPopup" tabindex="-1">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <form>
        <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title" id="emplacementTitreOperation">Modification Emplacement</h4>
         </div>
         <div class="modal-body form-horizontal">
            <input type="hidden" class="form-control" id="idEmplacement">
            <div class="form-group">
                <label for="message-text" class="control-label col-sm-2">emplacement</label>
                <div class="col-sm-10">
                   <input type="text" class="form-control" id="emplacementEmplacement">
                </div>
            </div>
            <div class="form-group">
                <p class="control-label col-sm-12 warning text-left" id="messageModification"></p>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-primary" id="sauverEmplacement">Sauver</button>
         </div>
        </div>
      </form>
  </div>
</div>

<div class="modal fade" id="myEmplacementDeletePopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="emplacementTitreOperationDelete">Suppression</h4>
      </div>
      <div class="modal-body">
          <input type="hidden" class="form-control" id="idEmplacementDelete">
          <p id="idEmplacementDeleteTitre"></p>
            <div class="form-group">
                <p class="control-label col-sm-12 warning text-left" id="messageSuppression"></p>
            </div>
      </div>
      <div class="modal-footer">
       	<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary" id="effacerEmplacement">Supprimer</button>
        </div>
   </div>
 </div>
</div>


 <script type="text/javascript">

(function($) {
    'use strict';
        
    /**
     * Initialisation de l'application 
     * 
     */
    function initApplication() {
        // Bug Safari sur iphone : 
        //http://stackoverflow.com/questions/2898740/iphone-safari-web-app-opens-links-in-new-window
        $("a").click(function (event) {
            event.preventDefault();
            window.location = $(this).attr("href");
        });
    };

    /**
    * Sauver Emplacement
    */
    $(function() {
        $('#sauverEmplacement').click( function(e) {
            e.preventDefault();
            var idEmplacement = $('#idEmplacement').val(); 
            var emplacement = $('#emplacementEmplacement').val(); 

            $.ajax({
                type: "POST",
                cache: false,
                data: { op : 'M', id : idEmplacement, emplacement : emplacement },
                url : "scripts/wsEmplacement.php",
                success : function( msg, status,xhr ) {
                    if (msg && msg.resultat==true) {
                        $('#myEmplacementPopup').modal('hide');
                        location.reload();
                    }
                    else {
                        $('#messageModification').html(msg.message);
                    }           
                },
                error : function( msg, status,xhr ) {
                    console.log(msg + "("+status+")", "Emplacement");
                }
            });         
        });
    });
    /**
    * Effacer Emplacement
    */
    $(function() {
        $('#effacerEmplacement').click( function(e) {
            e.preventDefault();
            var idEmplacement = $('#idEmplacementDelete').val(); 
            $.ajax({
                type: "POST",
                cache: false,
                data: { op : 'D', id : idEmplacement },
                url : "scripts/wsEmplacement.php",
                success : function( msg, status,xhr ) {
                    if (msg && msg.resultat==true) {
                        $('#myEmplacementDeletePopup').modal('hide');
                        location.reload();
                    }   
                    else {
                        $('#messageSuppression').html(msg.message);
                    }           
                },
                error : function( msg, status,xhr ) {
                    console.log(msg + "("+status+")", "Emplacement");
                }
            });         
        });
    });
    /**
    * Recherche Emplacement
    */
    $('.rechercheEmplacement').click( function(e) {
        e.preventDefault();
        var idEmplacement = $(this).attr("data-id");
        $.ajax({
            cache: false,
            data: { op : 'R', id : idEmplacement },
            url : "scripts/wsEmplacement.php",
            success : function( msg, status,xhr ) {
                if (msg && msg.resultat==true) {
                        $('#idEmplacement').val(msg.id);
                        $('#emplacementEmplacement').val(msg.emplacement);
                        $('#myEmplacementPopup').modal();
                    }
                    else {
                        $('#messageRecherche').html(msg.message);
                    }    
            },
            error : function( msg, status,xhr ) {
                console.log(msg + "("+status+")", "Emplacement");
            }
        });         
    });
    /**
    * Suppression Emplacement
    */
    $('.suppressionEmplacement').click( function(e) {
        e.preventDefault();
        var idEmplacement = $(this).attr("data-id");
        $.ajax({
            cache: false,
            data: { op : 'R', id : idEmplacement },
            url : "scripts/wsEmplacement.php",
            success : function( msg, status,xhr ) {
                if (msg && msg.resultat==true) {
                        $('#idEmplacementDelete').val( msg.id);
                        $('#idEmplacementDeleteTitre').html("Emplacement : " + msg.emplacement);
                        $('#myEmplacementDeletePopup').modal();
                    }
                    else {
                        $('#messageSuppression').html(msg.message);
                    }
            },
            error : function( msg, status,xhr ) {
                console.log(msg + "("+status+")", "Emplacement");
            }
        });         
    });
    /**
    * Ajouter emplacement
    */
    $('#AjouterEmplacement').click( function(e) {
        e.preventDefault();
        $('#emplacementTitreOperation').text("Nouvel objet Emplacement");
        $('#idEmplacement').val("");
        $('#emplacementEmplacement').val("");
        $('#myEmplacementPopup').modal();
    });


    /** DatePicker */
    /**
     * Initialisation de l'application dès que le DOM est chargé
     */
    $(document).ready(initApplication);


})(jQuery);


  </script>



<?php
include_once "footer.php";
?>

