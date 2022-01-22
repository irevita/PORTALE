<?php
	$servername = "localhost";
	$username = "root";
	$password = '';
	$dbname = "YourBlog";

	$mysqli = mysqli_connect($servername, $username, $password, $dbname);
	mysqli_set_charset($mysqli, 'utf8');

	if (!$mysqli) {
		die("Connessione fallita: " . mysql_connect_error());
	} else {
		echo '<script> console.log("Connesso.") </script>';
	}
?>