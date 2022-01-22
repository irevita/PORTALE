<?php
include('check_session.php');
if (!isset($_SESSION['id_utente'])) {
	header("Location: login.php");
	exit();
}
$menu = 1; 
?>
<!DOCTYPE html>
<html lang="it">
<head>
	<title> Aggiungi Collaboratore </title>
	<meta charset ="utf-8">
	<link rel="stylesheet" href="style_index.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
	<header>
		<?php include('header.php'); ?>
	</header>
<div>
<?php
		
if((isset($_GET['id'])) && (is_numeric($_GET['id']))) { 
	$id_utente = htmlspecialchars($_GET['id'], ENT_QUOTES);
} else {
	echo '<p class="error">ID non valido.</p>'; 
	exit();
}

require('connect.php');
if($id_utente <> $_SESSION['id_utente']) {

$query = "SELECT id_blog FROM Blog WHERE autore = '".$_SESSION['id_utente']."'";
$result = mysqli_query($mysqli, $query);

	foreach($result as $row){
		$id_blog = $row["id_blog"];
	}

	if (mysqli_num_rows($result) > 0) {
		$query = "INSERT INTO Collaboratore (blog, utente)"; 
		$query .= "VALUES(?, ?)";
		$q = mysqli_stmt_init($mysqli);
		mysqli_stmt_prepare($q, $query);
		mysqli_stmt_bind_param($q, 'ii', $id_blog, $id_utente);
		mysqli_stmt_execute($q);
		if(mysqli_stmt_affected_rows($q) == 1) {
			echo "<p style = 'color:green'>Collaboratore aggiunto con successo!</p>";
			echo '<a href="lista_blog.php" id ="leggi"> Torna ai tuoi Blog. </a>';
			exit();
		}else {
			echo "<p style = 'color:red'>Hai già aggiunto questo utente come collaboratore del tuo Blog.</p>";
     		echo '<a href="lista_blog.php"> Torna ai tuoi Blog. </a>';
			//echo '<p>' . mysqli_error($mysqli) . '<br />Query: ' . $q . '</p>';
		}
		mysqli_free_result($result);
		mysqli_stmt_close($q);

	} else {
		echo "<p style = 'color:red'>Il tuo ID non è associato a nessun Blog";
	}	
} else {
	echo "<p style = 'color:red'>Non puoi aggiungerti come collaboratore del tuo stesso blog.";
}

mysqli_close($mysqli);
?>
</div>
</body>
</html>