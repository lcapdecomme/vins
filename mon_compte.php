<?php
$page_title = "Mon compte";
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
if (isset($_POST['login']) ) {  
    $login->nom = strip_tags (stripslashes ($_POST["login"]));
    $login->mail = $_POST['mail'];
    $login->nb_vins_affiches = $_POST['nb_vins_affiches'];
    // update User
    if ($login->update()) {
          $_SESSION["nom_utilisateur"]  = $login->nom;
          $_SESSION["nb_vins_affiches"]  = $login->nb_vins_affiches;
          $op=true;
    }
} else {
    $login->read();
    if (isset($login) && isset($login->nb_vins_affiches)  && $login->nb_vins_affiches<1)  {
      $login->nb_vins_affiches=10;
    }
}

// Show forms
?>
<div class='row'>
  <div  class='col-md-12'>
    <h2 class="text-center">Mes préférences</h2>
    <form class="form-signin" action='mon_compte.php' method='POST'  >
        <div class="form-group">
          <label for="login">Login</label>
          <input name="login" type="text" id="login" class="form-control" placeholder="Login"  value='<?php if (isset($login)) { echo $login->nom; } ?>' required autofocus>
        </div>

        <div class="form-group">
          <label for="mail">Mail</label>
          <input name="mail" type="mail" id="mail" class="form-control" placeholder="Mail si mot de passe perdu" value='<?php if (isset($login)) { echo $login->mail;} ?>' required>
        </div>

        <div class="form-group">
          <label for="nb_vins_affiches">Nombre de vins par page</label>
          <select  title='Nombre de vins / page' class='form-control' name='nb_vins_affiches' id='nb_vins_affiches'>
            <option value='10' <?php  if (isset($login) && ($login->nb_vins_affiches==10)) { echo 'selected';} ?>>10</option>
            <option value='20' <?php  if (isset($login) && ($login->nb_vins_affiches==20)) { echo 'selected';} ?>>20</option>
            <option value='50' <?php  if (isset($login) && ($login->nb_vins_affiches==50)) { echo 'selected';} ?>>50</option>
            <option value='100' <?php  if (isset($login) && ($login->nb_vins_affiches==100)) { echo 'selected';} ?>>100</option>
          </select>
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