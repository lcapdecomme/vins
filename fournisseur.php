<?php
// Entête
$page_title = "Mes fournisseurs";
include_once "header.php";

// Connexion DB
include_once 'config/database.php';
include_once 'objects/Fournisseur.php';

if (!$_SESSION || !isset($_SESSION['id_utilisateur']) ) {
    header('Location: index.php');
}

$database = new Database();
$db = $database->getConnection();

// Recherche de tous les objets Fournisseur
$fournisseur = new Fournisseur($db);
$fournisseur->id_utilisateur = $_SESSION['id_utilisateur'];
$stmt = $fournisseur->readAll();
$errorInfo = $stmt->errorInfo();
if (isset($errorInfo) && strlen($errorInfo[2])>0 ) {
    $num = 0;
    echo "<div class='row'>";
    echo "<div  class='col-sm-12 text-warning text-left' id='messageRecherche'>";
    echo "Erreur : " . $errorInfo[2];
    echo "</div>";
    echo "</div><br>";    
} else {
    $num = $stmt->rowCount();
    echo "<div class='row'>";
    echo "<div  class='col-sm-12 text-warning text-left' id='messageRecherche'></div></div>";
    echo "<div class='row'>";
    echo "<div  class='col-md-12 right-button-margin'>";
        echo "<button type='button' class='btn btn-primary pull-right' id='AjouterFournisseur'>Ajouter un fournisseur</button>";
    echo "</div>";
    echo "</div><br>";    
}
?>


<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <table class='table table-striped table-hover table-responsive'>
            <tr>
                <th>Nom</th>
                <th>CP</th>
                <th>Ville</th>
                <th>mail</th>
                <th>tel. Fixe</th>
                <th>tel. Portable</th>
                <th>Opérations</th>
            </tr>
            <tbody>
                <?php
                $total=0;
                if($num>0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        echo "<tr>";
                            echo "<td>{$nom}</td>";
                            echo "<td>{$cp}</td>";
                            echo "<td>{$ville}</td>";
                            echo "<td>{$mail}</td>";
                            echo "<td>{$telFixe}</td>";
                            echo "<td>{$telPortable}</td>";
                            echo "<td class='text-center' >";
                            echo "<a href='#' data-id={$id}  class='rechercheFournisseur btn btn-primary btn-xs' title='Modification'>";
                            echo "<span class='glyphicon glyphicon-ok' aria-hidden='true'></span></a>&nbsp;";
                            echo "<a href='#' data-id={$id}  class='suppressionFournisseur btn btn-primary btn-xs' title='Suppression'>";
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
<div class="modal fade" id="myFournisseurPopup" tabindex="-1">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <form>
        <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title" id="fournisseurTitreOperation">Modification Fournisseur</h4>
         </div>
         <div class="modal-body form-horizontal">
            <input type="hidden" class="form-control" id="idFournisseur">
            <div class="form-group">
                <label for="message-text" class="control-label col-sm-2">Nom</label>
                <div class="col-sm-10">
                   <input type="text" class="form-control" id="fournisseurNom">
                </div>
            </div>
            <div class="form-group">
                <label for="message-text" class="control-label col-sm-2">Adresse</label>
                <div class="col-sm-10">
                   <input type="text" class="form-control" id="fournisseurAdresse">
                </div>
            </div>
            <div class="form-group">
                <label for="message-text" class="control-label col-sm-2">Code postal</label>
                <div class="col-sm-10">
                   <input type="text" class="form-control" id="fournisseurCP">
                </div>
            </div>
            <div class="form-group">
                <label for="message-text" class="control-label col-sm-2">Ville</label>
                <div class="col-sm-10">
                   <input type="text" class="form-control" id="fournisseurVille">
                </div>
            </div>
            <div class="form-group">
                <label for="message-text" class="control-label col-sm-2">Tel. Fixe</label>
                <div class="col-sm-10">
                   <input type="text" class="form-control" id="fournisseurTelFixe">
                </div>
            </div>
            <div class="form-group">
                <label for="message-text" class="control-label col-sm-2">Tel. Portable</label>
                <div class="col-sm-10">
                   <input type="text" class="form-control" id="fournisseurTelPortable">
                </div>
            </div>
            <div class="form-group">
                <label for="message-text" class="control-label col-sm-2">Mail</label>
                <div class="col-sm-10">
                   <input type="text" class="form-control" id="fournisseurMail">
                </div>
            </div>
            <div class="form-group">
                <label for="message-text" class="control-label col-sm-2">Url</label>
                <div class="col-sm-10">
                   <input type="text" class="form-control" id="fournisseurUrl">
                </div>
            </div>
            <div class="form-group">
                <p class="col-sm-12 text-warning text-left" id="messageModification"></p>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-primary" id="sauverFournisseur">Sauver</button>
         </div>
        </div>
      </form>
  </div>
</div>

<div class="modal fade" id="myFournisseurDeletePopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="fournisseurTitreOperationDelete">Suppression</h4>
      </div>
      <div class="modal-body">
          <input type="hidden" class="form-control" id="idFournisseurDelete">
          <p id="idFournisseurDeleteTitre"></p>
            <div class="form-group">
                <p class="col-sm-12 text-warning text-left" id="messageSuppression"></p>
            </div>
      </div>
      <div class="modal-footer">
       	<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary" id="effacerFournisseur">Supprimer</button>
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
    * Sauver Fournisseur
    */
    $(function() {
        $('#sauverFournisseur').click( function(e) {
            e.preventDefault();
            var idFournisseur = $('#idFournisseur').val(); 
            var nom = $('#fournisseurNom').val(); 
            var adresse = $('#fournisseurAdresse').val(); 
            var cp = $('#fournisseurCP').val(); 
            var ville = $('#fournisseurVille').val(); 
            var telFixe = $('#fournisseurTelFixe').val(); 
            var telPortable = $('#fournisseurTelPortable').val(); 
            var mail = $('#fournisseurMail').val(); 
            var url = $('#fournisseurUrl').val(); 

            $.ajax({
                type: "POST",
                cache: false,
                data: { op : 'M', id : idFournisseur, nom : nom, adresse : adresse, cp : cp, ville : ville,
                telFixe : telFixe, telPortable: telPortable, mail : mail, url : url },
                url : "scripts/wsFournisseur.php",
                success : function( msg, status,xhr ) {
                    if (msg && msg.resultat==true) {
                        $('#myFournisseurPopup').modal('hide');
                        location.reload();
                    }
                    else {
                        $('#messageModification').html('Erreur : ' + msg.message);
                    }           
                },
                error : function( msg, status,xhr ) {
                    console.log(msg + "("+status+")", "Fournisseur");
                }
            });         
        });
    });
    /**
    * Effacer Fournisseur
    */
    $(function() {
        $('#effacerFournisseur').click( function(e) {
            e.preventDefault();
            var idFournisseur = $('#idFournisseurDelete').val(); 
            $.ajax({
                type: "POST",
                cache: false,
                data: { op : 'D', id : idFournisseur },
                url : "scripts/wsFournisseur.php",
                success : function( msg, status,xhr ) {
                    if (msg && msg.resultat==true) {
                        $('#myFournisseurDeletePopup').modal('hide');
                        location.reload();
                    }   
                    else {
                        $('#messageSuppression').html('Erreur : ' + msg.message);
                    }           
                },
                error : function( msg, status,xhr ) {
                    console.log(msg + "("+status+")", "Fournisseur");
                }
            });         
        });
    });
    /**
    * Recherche Fournisseur
    */
    $('.rechercheFournisseur').click( function(e) {
        e.preventDefault();
        var idFournisseur = $(this).attr("data-id");
        $.ajax({
            cache: false,
            data: { op : 'R', id : idFournisseur },
            url : "scripts/wsFournisseur.php",
            success : function( msg, status,xhr ) {
                if (msg && msg.resultat==true) {
                        $('#idFournisseur').val(msg.id);
                        $('#fournisseurNom').val(msg.nom);
                        $('#fournisseurAdresse').val(msg.adresse);
                        $('#fournisseurCP').val(msg.cp);
                        $('#fournisseurVille').val(msg.ville);
                        $('#fournisseurTelFixe').val(msg.telFixe);
                        $('#fournisseurTelPortable').val(msg.telPortable);
                        $('#fournisseurMail').val(msg.mail);
                        $('#fournisseurUrl').val(msg.url);
                        $('#myFournisseurPopup').modal();
                    }
                    else {
                        $('#messageRecherche').html('Erreur : ' + msg.message);
                    }    
            },
            error : function( msg, status,xhr ) {
                $('#messageRecherche').html('Erreur : ' + msg + "("+status+")"+ "("+xhr+")");
            }
        });         
    });
    /**
    * Suppression Fournisseur
    */
    $('.suppressionFournisseur').click( function(e) {
        e.preventDefault();
        var idFournisseur = $(this).attr("data-id");
        $.ajax({
            cache: false,
            data: { op : 'R', id : idFournisseur },
            url : "scripts/wsFournisseur.php",
            success : function( msg, status,xhr ) {
                if (msg && msg.resultat==true) {
                        $('#idFournisseurDelete').val( msg.id);
                        $('#idFournisseurDeleteTitre').html("Fournisseur : " + msg.nom);
                        $('#myFournisseurDeletePopup').modal();
                    }
                    else {
                        $('#messageSuppression').html('Erreur : ' + msg.message);
                    }
            },
            error : function( msg, status,xhr ) {
                console.log(msg + "("+status+")", "Fournisseur");
            }
        });         
    });
    /**
    * Ajouter fournisseur
    */
    $('#AjouterFournisseur').click( function(e) {
        e.preventDefault();
        $('#fournisseurTitreOperation').text("Nouvel objet Fournisseur");
        $('#idFournisseur').val("");
        $('#fournisseurFournisseur').val("");
        $('#myFournisseurPopup').modal();
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

