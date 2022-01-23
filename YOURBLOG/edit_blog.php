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
	<title> Modifica Blog </title>
	<meta charset ="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
	<script src="script.js"></script>
	<link rel="stylesheet" href="style_index.css" />
	
</head>
<body>
	<header>
		<?php include('header.php'); ?>
	</header>
	<div class ="main">
		<?php
		if((isset($_GET['id'])) && (is_numeric($_GET['id']))) { 
			$id = htmlspecialchars($_GET['id'], ENT_QUOTES);
		} elseif((isset($_POST['id'])) && (is_numeric($_POST['id']))) {
			$id = htmlspecialchars($_POST['id'], ENT_QUOTES);
		} else {
			echo '<p class="error">ID non valido.</p>'; 
			exit();
		}

require('connect.php');

$query1 = "SELECT id_utente FROM Utente, Blog WHERE id_blog='" .$id. "' and Blog.autore = Utente.id_utente ";
$query2 = "SELECT * FROM Collaboratore, Utente, Blog WHERE Collaboratore.blog = Blog.id_blog AND Collaboratore.utente = Utente.id_utente and Collaboratore.blog = ".$id."";
$result1 = mysqli_query($mysqli, $query1);
$result2 = mysqli_query($mysqli, $query2);

$row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);
$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);

if (($_SESSION["id_utente"] == $row1["id_utente"]) || ($_SESSION["id_utente"] == $row2["utente"] && $row2["blog"] == $id)) {

if($_SERVER['REQUEST_METHOD'] == 'POST') { 
$errors = array();

$titolo = filter_var( $_POST['titolo'], FILTER_SANITIZE_STRING);
$titolo = mysqli_real_escape_string($mysqli,$_POST['titolo']);
if ((!empty($titolo)) && (preg_match("/^[a-zA-Z ]*$/",$titolo)) && (strlen($titolo) <= 30)) { 
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

if(empty($errors)) {
		$query = 'UPDATE Blog, Categoria SET titolo=?, descrizione=?, nomeC=? WHERE Blog.categoria = Categoria.id_categoria and id_blog=?';
		$q = mysqli_stmt_init($mysqli);
		mysqli_stmt_prepare($q, $query);
		mysqli_stmt_bind_param($q, 'sssi', $titolo_ok, $descrizione_ok, $categoria_ok, $id);
		mysqli_stmt_execute($q);
		if(mysqli_stmt_affected_rows($q) > 0) { //update ok
			echo '<h3 style = color:green class="text-center">Blog modificato.</h3>';
		} else {
			echo '<p style = color:red class="text-center">Non hai modificato nessun campo.';
			//echo '<p>' . mysqli_error($mysqli) . '<br />Query: ' . $q . '</p>';
		}
} else {
	echo '<p class="text-center"> Si sono verificati i seguenti errori(e):<br />';
	foreach ($errors as $msg) { 
		echo " - $msg<br />\n";
	}
	echo '</p><p>Riprova.</p>';
}
}
		$q = mysqli_stmt_init($mysqli);
		$query = "SELECT titolo, descrizione, nomeC FROM Blog, Categoria WHERE id_blog=? and Blog.categoria = Categoria.id_categoria ";
		mysqli_stmt_prepare($q, $query);
		mysqli_stmt_bind_param($q, 'i', $id);
		mysqli_stmt_execute($q);
		$result = mysqli_stmt_get_result($q);
		$row = mysqli_fetch_array($result, MYSQLI_NUM);
		//printf ("%s %s %s\n", $row[0], $row[1], $row[2]);

		if(mysqli_num_rows($result) == 1) { 
			//crea la form edit	
		?>
		<h2 class="h2 text-center"> Edit Blog </h2>
		<form name = "editform" id="editform" method = "post" action = "" accept-charset = "utf-8">
			<label> Titolo:
				<input name = "titolo" type = "text" minlength= "4" value="<?php echo htmlspecialchars($row[0], ENT_QUOTES); ?>" >
			</label>
			<p>
				<label> Descrizione:
					<input type = "text" name = "descrizione" minlength = "4" value="<?php echo htmlspecialchars($row[1], ENT_QUOTES); ?>">  </input>	
				</label>		
			</p>
			<p>
				<label> Categoria:
					<input type = "text" name = "categoria" minlength = "2" value="<?php echo htmlspecialchars($row[2], ENT_QUOTES); ?>">
				</label>
			</p>
			<p>
				<input name = "invia" type = "submit" value = "Edit" />
			</p>
		</form>	
		<?php
		} else {
			echo '<p class="text-center">Errore con la form.</p>';
		}
	} else {
		echo "<p style = 'color:red'>Non puoi modificare Blog non creati da te.</p>";
		echo '<a href="edit_blog.php?id='.$id.'> Torna indietro </a>';
		exit();
	}
		mysqli_free_result($result1);
		mysqli_free_result($result2);
		mysqli_stmt_free_result($q);
   		mysqli_close($mysqli);
   		?>
	</div>
</body>
</html>