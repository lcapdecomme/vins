<?php
$page_title = "Connexion";
include_once "header.php";

// include database and object files
include_once 'config/database.php';
include_once 'objects/Utilisateur.php';

if (!$_SESSION || !(isset($_SESSION['id_utilisateur'])) ) {
    header('Location: index.php');
}

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
$login = new Utilisateur($db);
$login->id = $_SESSION['id_utilisateur'];

// POST && id and Pass ?
if (isset($_POST['login']) && (isset($_POST['motDePasse']))) {  
    $login->nom = strip_tags (stripslashes ($_POST["login"]));
    $login->mdp = strip_tags (stripslashes ($_POST["motDePasse"]));
    $login->mail = $_POST['mail'];
    // update User
    if ($login->update()) {
          $_SESSION["nom_utilisateur"]  = $login->nom;
    }
} else {
    $login->read();
}

// Show forms
?>
<div class='row'>
  <div  class='col-md-12'>
    <h2 class="text-center">Mon compte</h2>
    <form class="form-signin" action='mon_compte.php' method='POST'  >
        <div class="form-group">
          <label for="login" class="sr-only">Login</label>
          <input name="login" type="text" id="login" class="form-control" placeholder="Login"  value='<?php if (isset($login)) { echo $login->nom; } ?>' required autofocus>
        </div>

        <div class="form-group">
          <label for="motDePasse" class="sr-only">Mot de Passe</label>
          <input name="motDePasse" type="password" id="motDePasse" class="form-control" placeholder="Mot de passe" required>
        </div>
        <div class="form-group">
          <label for="motDePasse2" class="sr-only">Mot de Passe</label>
          <input name="motDePasse2" type="password" id="motDePasse2" class="form-control" placeholder="Confirmation du mot de passe" required>
        </div>

        <div class="form-group">
          <label for="mail" class="sr-only">Mail</label>
          <input name="mail" type="mail" id="mail" class="form-control" placeholder="Mail si mot de passe perdu" value='<?php if (isset($login)) { echo $login->mail;} ?>' required>
        </div>

        <button class="loginBtn btn btn-lg btn-primary btn-block" type="submit">Ok</button>
        <?php
          if (isset($login->error) ) {
            echo '<p class="errorMessage text-center">'.$login->error.'</p>';
          } else {
            echo '<p class="successMessage text-center">Mise à jour réussie</p>';
          }
        ?>
    </form>
  </div> 
</div>   

<?php
include_once "footer.php";
?>