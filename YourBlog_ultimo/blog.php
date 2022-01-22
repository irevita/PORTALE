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
	<title> Pagina creazione blog </title>
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
	<?php
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		require('verifica_blog.php');
	}
	?>
	<div class ="main">
		<br />
		<br />
		<form name = "form_blog" method = "post" action = "blog.php" accept-charset = "utf-8">
			<label> Titolo:
				<input name = "titolo" type = "text" placeholder= "Inserire qui il titolo" minlength= "4" value="<?php if (isset($_POST['titolo'])) echo htmlspecialchars($_POST['titolo'], ENT_QUOTES); ?>" />
			</label>
			<p>
				<label> Descrizione: </label>
				<input type = "text" name = "descrizione" rows="5" cols="30" minlength= "4" placeholder = "Inserire qui la descrizione" value="<?php if (isset($_POST['descrizione'])) echo htmlspecialchars($_POST['descrizione'], ENT_QUOTES); ?>" > 
				</input>			
			</p>
			<p>
				<label> Categoria:
					<input type = "text" name = "categoria" placeholder = "Inserire qui la categoria" minlength = "2" value="<?php if (isset($_POST['categoria'])) echo htmlspecialchars($_POST['categoria'], ENT_QUOTES); ?>"  >
				</label>
			</p>
			<p>
				<input name = "invia" type = "submit" value = "Crea" />
			</p>
		</form>	
			<form id="uploadimage" action="" method="post" enctype="multipart/form-data">
				<div id="image_preview"> 
					<img id="previewing" src="images/noimage.png" width="200" height="200" border="1px" />
				</div>
					<div id="selectImage">
						<label>Seleziona l'immagine </label><br/>
						<input type="file" name="file" id="file" required />
						<input type="submit" value="Upload" class="submit" />
					</div>
			</form>		
		</div>
		<div id="message"></div>
	<footer>
    	<div id="footerlogin">
      		<p>Realizzato da Triolo Gabriele e Giurdanella Chiara</p>
    	</div>
  </footer>
</body>
</html>