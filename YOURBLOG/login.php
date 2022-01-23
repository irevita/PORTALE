<?php
$menu = 2;
?>
<!DOCTYPE html>
<html>
<head>
	<title> Pagina di Login </title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="style_index.css">
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap" rel="stylesheet">
</head>
<header>
	<?php include('header.php'); ?>
</header>
<body>
<div style="padding-left: 0px; height: auto;">
<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		require('verifica_login.php');
	}
?>
<div>
	<br />

	
	<form name = "form_login" method = "post" action = "login.php" >
		<label> E-mail: 
		<input name = "email" type = "text" placeholder= "E-mail" minlength= "8" value= "<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>" />
		</label>
		
		<p>
			<label> Password: 
			<input type = "password" id = "password" name = "password" placeholder = "Password" minlength= "8" maxlength= "12" />
			</label>
		</p>

		<p>
			<input name = "invia" type = "submit" value = "Login" />
		</p>
	</form>
</div>
</div>
<footer>
    <div id="footerlogin">
      <p>Realizzato da Triolo Gabriele e Giurdanella Chiara</p>
    </div>
  </footer>
</body>
</html>











