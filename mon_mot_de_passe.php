<?php
$page_title = "Mot de passe";
include_once "header.php";

// include database and object files
include_once 'config/database.php';
include_once 'objects/Utilisateur.php';

if (!$_SESSION || !(isset($_SESSION['id_utilisateur'])) ) {
    header('Location: index.php');
}

$op=false;
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
$login = new Utilisateur($db);
$login->id = $_SESSION['id_utilisateur'];

// POST && id and Pass ?
if ( isset($_POST['motDePasse']) ) {  
    $login->mdp = strip_tags (stripslashes ($_POST["motDePasse"]));
    // update User
    if ($login->updatePassword()) {
          $op=true;
    }
} else {
    $login->read();
}

// Show forms
?>
<div class='row'>
  <div  class='col-md-12'>
    <h2 class="text-center">Modification du mot de passe</h2>
    <form class="form-signin" action='mon_mot_de_passe.php' method='POST'  >
        <div class="form-group">
          <label for="motDePasse" class="sr-only">Mot de Passe</label>
          <input name="motDePasse" type="password" id="motDePasse" class="form-control" placeholder="Mot de passe" required>
        </div>
        <div class="form-group">
          <label for="motDePasse2" class="sr-only">Mot de Passe</label>
          <input name="motDePasse2" type="password" id="motDePasse2" class="form-control" placeholder="Confirmation du mot de passe" required>
        </div>
        <button class="loginBtn btn btn-lg btn-primary btn-block" type="submit">Ok</button>
        <?php
          if (isset($login->error) ) {
            echo '<p class="errorMessage text-center">'.$login->error.'</p>';
          } elseif ($op) {
            echo '<p class="successMessage text-center">Mise à jour réussie</p>';
          } else {
            echo '<p class=" text-center"></p>';
          }
        ?>
    </form>
  </div> 
</div>   

<?php
include_once "footer.php";
?>