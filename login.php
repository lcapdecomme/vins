<?php
$page_title = "Connexion";
include_once "header.php";

// Not authenticate 
$authentification= false;

// id, action and Pass ?
if ( isset($_POST['login']) && isset($_POST['action']) && 
  (isset($_POST['motDePasse']) || isset($_POST['newMotDePasse'])) ) 
{  
    // include database and object files
    include_once 'config/database.php';
    include_once 'config/util.php';
    include_once 'objects/Utilisateur.php';

    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();
    $login = new Utilisateur($db);
    $login->nom = strip_tags (stripslashes ($_POST["login"]));

    $resultat=false;
    if (isset($_POST['action']) && ($_POST['action']=="login")) {
        // check user and pass
        $login->mdp = strip_tags (stripslashes ($_POST["motDePasse"]));
        $resultat = $login->checkUserPassword();
    }
    if (isset($_POST['action']) && ($_POST['action']=="register")) {
        // check pass
        $pass1 = strip_tags (stripslashes ($_POST["newMotDePasse"]));
        $pass2 = strip_tags (stripslashes ($_POST["newMotDePasse2"]));
        if ($pass1 != $pass2) {
          $login->error="Mots de passe différents";
        } else {
          $login->mail = $_POST['mail'];
          $login->mdp = $pass1;
          $resultat = $login->addUser();
        }
    }

    if ($resultat)
    {
          // Read user's information 
          $login->read();
          // Create session 
          $_SESSION["id_utilisateur"]   = $login->id;
          $_SESSION["nom_utilisateur"]  = $login->nom;
          $_SESSION["nb_vins_affiches"]  = $login->nb_vins_affiches;
          // Connection auto ?
          if (isset($_POST['auto'])) {
              // Define cookie
          		addCookie($login->id, $login->nom, $login->nb_vins_affiches);
          }             
          // Authentificate Ok ! 
          $authentification= true;
    } 
}

if ($authentification)
{
    header('Location: index.php');
}
else
{
    // Show forms
?>
<div class='row'>
  <div  class='col-md-6 col-sm-12'>
    <h2 class="text-center">Connexion</h2>
    <form class="form-signin" action='login.php' method='POST'  >
        <input type="hidden" name="action" value="login">
        <div class="form-group">
          <label for="login" class="sr-only">Login</label>
          <input name="login" type="text" id="login" class="form-control" placeholder="Login"  value='<?php if (isset($_POST['action']) && ($_POST['action']=="login") && isset($login)) { echo $login->nom;} ?>' required autofocus>
        </div>

        <div class="form-group">
          <label for="motDePasse" class="sr-only">Mot de Passe</label>
          <input name="motDePasse" type="password" id="motDePasse" class="form-control" placeholder="Mot de passe" required>
        </div>
        <div class="checkbox">
          <label>
            <input name="auto" type="checkbox" value="remember-me"> Se souvenir de moi
          </label>
        </div>
        <div class="espaceForm"></div>

        <button class="loginBtn btn btn-lg btn-primary btn-block" type="submit">Ok</button>
        <?php
          if (isset($_POST['action']) && $_POST['action']=="login" && (isset($login->error)) ) {
            echo '<p class="errorMessage text-center">'.$login->error.'</p>';
          }
        ?>
    </form>
  </div>  

  <div class='col-md-6 col-sm-12'>
    <hr style="background-color: gray;border: 1px solid gray;"  class='hidden-md hidden-lg hidden-xl'>
    <h2 class="text-center">Ou Nouvel utilisateur</h2>
    <form class="form-signin" action='login.php' method='POST'  >
        <input type="hidden" name="action" value="register">
        <div class="form-group">
          <label for="login" class="sr-only">Login</label>
          <input name="login" type="text" id="login" class="form-control" placeholder="Login"  value='<?php if (isset($_POST['action']) && $_POST['action']=="register" && isset($login)) { echo $login->nom;} ?>' required autofocus>
        </div>

        <div class="form-group">
          <label for="newMotDePasse" class="sr-only">Mot de Passe</label>
          <input name="newMotDePasse" type="password" id="newMotDePasse" class="form-control" placeholder="Mot de passe" required>
        </div>
        <div class="form-group">
          <label for="newMotDePasse2" class="sr-only">Mot de Passe</label>
          <input name="newMotDePasse2" type="password" id="newMotDePasse2" class="form-control" placeholder="Confirmation du mot de passe" required onkeyup="checkPass(); return false;" >
          <span id="confirmMessage" class="confirmMessage"></span>
        </div>

        <div class="form-group">
          <label for="mail" class="sr-only">Mail</label>
          <input name="mail" type="mail" id="mail" class="form-control" placeholder="Mail si mot de passe perdu" value='<?php if (isset($_POST['action']) && $_POST['action']=="register" && isset($login)) { echo $login->mail;} ?>' required>
        </div>

        <button class="loginBtn btn btn-lg btn-primary btn-block" type="submit">Ok</button>
        <?php
          if (isset($_POST['action']) && $_POST['action']=="register" && (isset($login->error)) ) {
            echo '<p class="errorMessage text-center">'.$login->error.'</p>';
          }
        ?>
    </form>
  </div> 
</div>   

<?php
}
include_once "footer.php";
?>

<script>
    function checkPass()
    {
        var pass1 = $('#newMotDePasse');
        var pass2 =  $('#newMotDePasse2');
        if(pass1.val() == pass2.val()){
            pass1.addClass("passWordOk").removeClass("passWordKo");
            pass2.addClass("passWordOk").removeClass("passWordKo");
        } else {
            pass1.addClass("passWordKo").removeClass("passWordOk");
            pass2.addClass("passWordKo").removeClass("passWordOk");
        }
    }  

</script>