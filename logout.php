<?php 
	session_start();
	// pour la buter
	session_unset();
	session_destroy();
	// Définition du nom du cookie
	$nom_cookie = 'auth';

	// suppression du cookie
	setcookie($nom_cookie,'',0,'/');
	
	// On redirige vers la page principale
	header('Location: index.php');
?>
