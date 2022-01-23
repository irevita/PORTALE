<?php
$menu = 2;
?>
<!DOCTYPE html>
<html lang="it">
<head>
	<title> Pagina di registrazione </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="style_index.css">
	<script src= "controllo.js"> </script>
</head>
<body>
	<header>
		<?php include('header.php'); ?>
	</header>
<?php
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		require('verifica_registrazione.php');
	}
?>
<div class="form">
	<br />
	<br />
<form name = "form_registrazione" method = "post" action = "registrazione.php" onsubmit= "return controllo();">
	<label> Nome:
	<input name = "nome" type = "text" placeholder = "Nome" minlength = "3" value="<?php if (isset($_POST['nome'])) echo htmlspecialchars($_POST['nome'], ENT_QUOTES); ?>" />
	</label>
	<p>
		<label> Cognome:
		<input name = "cognome" type = "text" placeholder = "Cognome" minlength = "2" value="<?php if (isset($_POST['cognome'])) echo htmlspecialchars($_POST['cognome'], ENT_QUOTES); ?>" />
		</label>
	</p>
	<p>
		<label> Username:
		<input name = "username" type = "text" placeholder = "Username" minlength = "4" value="<?php if (isset($_POST['username'])) echo htmlspecialchars($_POST['username'], ENT_QUOTES); ?>" />
		</label>
	</p>
	<p>
		<label> E-mail:
		<input name = "email" type = "text" placeholder = "E-mail" minlength = "10" value="<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>" /> 
		</label>
	</p>
	<p>
		<label> Password: 
		<input type = "password" id = "password1" name = "password1" placeholder = "Password" minlength = "8" maxlength= "12" />
		<span style="margin-top: 30px"id = 'message'> Tra 8 e 12 caratteri. </span>
		</label>
	</p>
	<p>
		<label> Conferma Password: 
		<input type = "password" id = "password2" name = "password2" placeholder= "Conferma Password" minlength = "8" maxlength= "12" />
		</label>
	</p>

	<p>
		<input name = "invia" type = "submit" value = "Invia" />
	</p>
</form>
</div>
<footer>
    <div id="footerlogin">
      <p>Realizzato da Triolo Gabriele e Giurdanella Chiara</p>
    </div>
  </footer>
</body>
</html>


