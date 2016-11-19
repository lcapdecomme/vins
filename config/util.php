<?php

// Initialize cookie
$nom_cookie = 'my-wines';
$sepCookie  = 'aicd45ez432dsf43d432';
$timeCookie = time() + 10*24*3600;

// Remove cookie
function deleteCookie() {
    global $nom_cookie;
    setcookie($nom_cookie,'',0,'/');
}

// Set cookie
function addCookie($user_id, $user_name,$nbWines) {
    global $nom_cookie;
    global $sepCookie;
    global $timeCookie;
    setcookie($nom_cookie,$user_id.$sepCookie.$user_name.$sepCookie.$nbWines, 
        $timeCookie,'/');
}

// is cookie ? 
function isCookieOk() {
    global $nom_cookie;
    global $sepCookie;
    // Test du cookie sur toutes les pages du site
    if (isset($_COOKIE[$nom_cookie]) && !isset($_SESSION["id_joueur"]) ) {
        $wineCookie = explode($sepCookie, $_COOKIE[$nom_cookie]);
        // User id
        $id=$wineCookie[0];
        $name=$wineCookie[1];
        $nbWines=$wineCookie[2];
        // Refresh Cookie 
        addCookie($id, $name,$nbWines);
        // Update session
        $_SESSION["id_utilisateur"]    = $id;
        $_SESSION["nom_utilisateur"]   = $name;
        $_SESSION["nb_vins_affiches"]  = $nbWines;
      }   
}

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
?>