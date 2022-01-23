<?php
	$servername = "localhost";
	$username = "root";
	$password = '';
	$dbname = "yourblog";

	$mysqli = mysqli_connect($servername, $username, $password, $dbname);
	mysqli_set_charset($mysqli, 'utf8');

	if (!$mysqli) {
		// die("Connessione fallita: " . mysqli_connect_error());
		die("impossibile connettersi");
	} else {
		echo '<script> console.log("Connesso.") </script>';
	}
?>