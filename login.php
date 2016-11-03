<?php
$page_title = "Connexion";
include_once "header.php";

// Not authenticate 
$authentification= false;

// id and Pass ?
if (isset($_POST['login']) && (isset($_POST['motDePasse']))) 
{  
    // include database and object files
    include_once 'config/database.php';
    include_once 'objects/Utilisateur.php';

    // Init
    $pseudo=strip_tags (stripslashes ($_POST["login"]));
    $motpasse=strip_tags (stripslashes ($_POST["motDePasse"]));

    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();
    $login = new Utilisateur($db);
    $login->nom = $_POST['login'];
    $login->mdp = $_POST['motDePasse'];
    // check user and pass
    $resultat = $login->check();

    if ($resultat)
    {
          // Create session 
          $_SESSION["id_utilisateur"]   = $login->id;
          $_SESSION["pseudo_utilisateur"]   = $login->pseudo;
          // Connection auto ?
          if (isset($_POST['auto']))
          {
              // Define cookie
              $nom_cookie = 'auth';
              $sepCookie  = 'aicd45ez432dsf43d432';
              setcookie($nom_cookie,$pseudo.$sepCookie.sha1($pseudo).$sepCookie.sha1($_SERVER['REMOTE_ADDR']), time() + 10*24*3600,'/');
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
    <form class="form-signin" action='login.php' method='POST'  >

        <div class="form-group">
          <label for="login" class="sr-only">Login</label>
          <input name="login" type="text" id="login" class="form-control" placeholder="Login" required autofocus>
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
        <button class="btn btn-lg btn-primary btn-block" type="submit">Ok</button>
      </form>
<?php
}
include_once "footer.php";
?>