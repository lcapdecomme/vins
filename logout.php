<?php 
	// Remove session
	session_start();
	session_unset();
	session_destroy();
	include_once 'config/util.php';
	// Delete cookie
	deleteCookie();
	// Redirect main page
	header('Location: index.php');
?>
