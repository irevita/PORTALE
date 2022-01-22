<?php
session_start(); //accesso alla sessione corrispondente
if (!isset($_SESSION['id_utente'])) {
	header("location:index.php");
	exit();
} else { //cancella la sessione
	$_SESSION = array();
	$params = session_get_cookie_params();
	Setcookie(session_name(), time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
	if (session_status() == PHP_SESSION_ACTIVE) {
		session_destroy();
	}
	header("location:index.php");
}
?>