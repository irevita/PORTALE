<?php

  $server = "localhost";
	$utente = "root";
	$password = "";
	$database = "PORTALE";

	$connessione = mysqli_connect($server, $utente, $password, $database);

  if ($connessione) {

  }else{
    die("impossibile connettersi");
  }

?>
