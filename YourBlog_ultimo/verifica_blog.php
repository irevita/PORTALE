<?php
if (!isset($_SESSION['id_utente'])) {
	header("Location: login.php");
	exit();
}
require ('connect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
$titolo = filter_var( $_POST['titolo'], FILTER_SANITIZE_STRING);
$titolo = mysqli_real_escape_string($mysqli,$_POST['titolo']);
if ((!empty($titolo)) && (preg_match('/^[a-zA-Z ]*$/',$titolo)) && (strlen($titolo) <= 30)) { 
	$titolo_ok = $titolo;
} else {
	$errors[] = 'Hai dimenticato di inserire il titolo o non rispetta i dovuti parametri.'; 
}
	
$descrizione = filter_var($_POST['descrizione'], FILTER_SANITIZE_STRING);
$descrizione = mysqli_real_escape_string($mysqli,$_POST['descrizione']);
if ((!empty($descrizione)) && (strlen($descrizione) <= 500)) {
		$patterns = array("/http/", "/https/", "/\:/","/\/\//","/www./");
		$descrizione_ok = preg_replace($patterns," ", $descrizione);
} else {
	$errors[] = "Hai dimenticato di inserire la descrizione.";
}

$categoria = filter_var( $_POST['categoria'], FILTER_SANITIZE_STRING);
$categoria = mysqli_real_escape_string($mysqli,$_POST['categoria']);
if ((!empty($categoria)) && (preg_match("/^[a-zA-Z ]*$/",$categoria)) && (strlen($categoria) <= 50)) { 
	$categoria_ok = $categoria;
} else {
	$errors[] = 'Hai dimenticato di inserire la categoria o non rispetta i dovuti parametri.'; 
}

	$query = "SELECT * FROM Blog WHERE Blog.titolo = ? ";
	$q = mysqli_stmt_init($mysqli);
	mysqli_stmt_prepare($q, $query);
	mysqli_stmt_bind_param($q, 's', $titolo_ok);
	mysqli_stmt_execute($q);
	$result = mysqli_stmt_get_result($q);
	if (mysqli_num_rows($result) == 1) { 
		$errors[] = 'Il titolo che hai inserito è stato utilizzato.';
	}

	if (empty($errors)) {

	$query = "SELECT * FROM Categoria WHERE nomeC = ? ";
	$q = mysqli_stmt_init($mysqli);
	mysqli_stmt_prepare($q, $query);
	mysqli_stmt_bind_param($q, 's', $categoria_ok);
	mysqli_stmt_execute($q);
	$result = mysqli_stmt_get_result($q);
	if($result) { 
		$query = 'INSERT INTO Categoria VALUES (null,"'.$categoria_ok.'")';
		$results = mysqli_query($mysqli,$query);
	}	

	
    $sql= 'SELECT id_categoria FROM Categoria WHERE Categoria.nomeC ="'.$categoria_ok.'"';
    $result= mysqli_query($mysqli,$sql);
    	foreach($result as $row){
        	$id_categoria = $row["id_categoria"];
      	}


    if(!isset($_SESSION['nomefile'])) {
    	$_SESSION['nomefile'] = "images/noimage.png";
    }
  
	$query = "INSERT INTO Blog (id_blog, titolo, descrizione, categoria, immagine, autore, data_blog)";
	$query .= "VALUES(' ', ?, ?, ?, ?, ?, NOW() )";
	$q = mysqli_stmt_init($mysqli);
	mysqli_stmt_prepare($q, $query);
	mysqli_stmt_bind_param($q, 'ssisi', $titolo_ok, $descrizione_ok, $id_categoria, $_SESSION['nomefile'], $_SESSION['id_utente'] );
	mysqli_stmt_execute($q);
	if (mysqli_stmt_affected_rows($q) == 1) {
		echo "<p style = 'color:green'> Blog creato con successo! </p>";
		unset($_SESSION['nomefile']);
		exit();
	} else {
		$errorstring = "<p style = 'color:red'>";
		$errorstring .= "System Error<br />Creazione Blog fallita.</p>";
		echo "<p style = 'color:red'> $errorstring </p>";
		mysqli_close($mysqli);
		exit();
	}
	mysqli_free_result($result);
	mysqli_stmt_close($q);
	mysqli_close($mysqli);
} else {
			$errorstring = "Errore! <br /> Si sono verificati i seguenti errori(e):<br>";
			foreach ($errors as $msg) {
				$errorstring .= " - $msg<br>\n";
			}
			$errorstring .= "Prova di nuovo.<br>";
			echo "<p style = 'color:red'>$errorstring</p>";
}
}
?>