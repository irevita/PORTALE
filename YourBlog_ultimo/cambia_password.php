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
	<title>Cambia Password</title> 
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="style_index.css" />
	<script src= "controllo.js"> </script>
</head>
<body>
<header>
	<?php include('header.php'); ?>
</header>
<?php
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		require('verifica_cambia_password.php');
	}
?>
<div class="row" style="padding-left: 0px;">
	<br>
	<br>
	<form action="cambia_password.php" method="post" id="regform" onsubmit="return controllo();">
		<label> E-mail:
			<input name = "email" type = "text" placeholder = "E-mail" minlength = "9" value= "<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>" />
		</label>
		<p>
			<label> Password corrente: 
				<input type = "password" id = "password" name = "password" placeholder = "Password" minlength = "8" maxlength= "12" />
			</label>
		</p>
		<p>
			<label> Nuova Password: 
				<input type = "password" id = "password1" name = "password1" placeholder = "Password" minlength = "8" maxlength= "12" />
				<span style="margin-top: 33px;"id = 'message'> Tra 8 e 12 caratteri. </span>
			</label>
		</p>
		<p>
			<label> Conferma Password: 
				<input type = "password" id = "password2" name = "password2" placeholder = "Password" minlength = "8" maxlength= "12" />
			</label>
		</p>
		<p>
			<input name = "invia" type = "submit" value = "Cambia Password" />
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
