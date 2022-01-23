<?php
include('check_session.php');
if (!isset($_SESSION['id_utente'])) {
	header("Location: login.php");
	exit();
}
if((isset($_GET['id'])) && (is_numeric($_GET['id']))) { 
	$id_blog = htmlspecialchars($_GET['id'], ENT_QUOTES);
}else {
	echo '<p class="error">ID non valido.</p>'; 
    exit();
}
$menu = 1;
?>

<!DOCTYPE html>
<html lang="it">
<head>
	<title> Pagina creazione post </title>
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require ('connect.php');
	
$titolo = filter_var( $_POST['titolo'], FILTER_SANITIZE_STRING); 
$titolo = mysqli_real_escape_string($mysqli,$_POST['titolo']);
if ((!empty($titolo)) && (preg_match('/^[a-zA-Z ]*$/',$titolo)) && (strlen($titolo) <= 40)) { 
	$titolo_ok = $titolo;
} else {
	$errors[] = 'Hai dimenticato di inserire il titolo o non rispetta i dovuti parametri.'; 
}
	
$testo = filter_var( $_POST['testo'], FILTER_SANITIZE_STRING);
$testo = mysqli_real_escape_string($mysqli,$_POST['testo']);
if ((!empty($testo)) && (strlen($testo) <= 500)) {
		$patterns = array("/http/", "/https/", "/\:/","/\/\//","/www./");
		$testo_ok = preg_replace($patterns," ", $testo);
} else {
	$errors[] = "Hai dimenticato di inserire il testo.";
}

	
$query = "SELECT * FROM Post WHERE Post.titolo=?";
$q = mysqli_stmt_init($mysqli);
mysqli_stmt_prepare($q, $query);
mysqli_stmt_bind_param($q, 's', $titolo_ok);
mysqli_stmt_execute($q);
$result = mysqli_stmt_get_result($q);
if (mysqli_num_rows($result) == 1) {
	$errors[] = 'Titolo del Post già utilizzato.'; 
}


if (empty($errors)) {
	
$query = "SELECT id_blog FROM Blog WHERE id_blog ='".$id_blog."' ";
$result = mysqli_query($mysqli,$query);
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	$id_blog1 = htmlspecialchars($row['id_blog'], ENT_QUOTES);
}
       
    if (mysqli_num_rows($result) == 1) {
    	if($id_blog1 == $id_blog) {

    		if(!isset($_SESSION['nomefile'])) {
    			$_SESSION['nomefile'] = "images/noimage2.png";
    		}
 
		$query = "INSERT INTO Post (id_post, titolo, testo, autore, blog, immagine, data_creazione)";
		$query .= "VALUES(' ', ?, ?, ?, ?, ?, NOW() )"; 
		$q = mysqli_stmt_init($mysqli);
		mysqli_stmt_prepare($q, $query);
		mysqli_stmt_bind_param($q, 'ssiis', $titolo_ok, $testo_ok, $_SESSION['id_utente'], $id_blog, $_SESSION['nomefile']);
		mysqli_stmt_execute($q);
			if (mysqli_stmt_affected_rows($q) == 1) {
				echo "<p style = 'color:green'> Post aggiunto con successo. </p>";
				unset($_SESSION['nomefile']);
				echo '<a href="apertura_blog.php?id='.$id_blog.'"> Vai al blog</a>';
				exit();
			}else {
				$errorstring = "<p style = 'color:red'>";
				$errorstring .= "System Error<br />Creazione Post fallita.</p>";
				echo "<p style = 'color:red'> $errorstring </p>";
				exit();
			}
			mysqli_free_result($result);
			mysqli_stmt_close($q);
			mysqli_close($mysqli);

		}
	}else {
			echo "<p style = 'color:red'> Non puoi creare post in blog non tuoi. </p>";
			echo '<a href="lista_blog.php"> Torna ai tuoi Blog </a>';
			exit();
		}
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
		<h2> Crea un Post </h2>
		<form name = "form_post" method = "post" action = "" accept-charset = "utf-8">
			<label> Titolo:
				<input name = "titolo" type = "text" placeholder= "Titolo" minlength= "4"  />
			</label>
			<p>
				<label> Testo: </label>
				<input  type = "text" name = "testo" rows="5" cols="30" placeholder = "Inserire qui il testo" /> 
				</input>			
			</p>
			
			<p>
				<input name = "invia" type = "submit" value = "Crea" />
			</p>
		</form>	
			<form id="uploadimage" action="" method="post" enctype="multipart/form-data">
				<div id="image_preview"> 
					<img id="previewing" src="images/noimage2.png" width="200" height="200" border="1px" />
				</div>
					<div id="selectImage">
						<label>Selezione la tua immagine</label><br/>
						<input type="file" name="file" id="file"  />
						<input type="submit" value="Upload" class="submit" />
					</div>
			</form>		
		</div>
		<div id="message"></div>
</body>
</html>