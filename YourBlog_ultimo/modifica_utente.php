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
	<title>Modifica profilo</title> 
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	 <link rel="stylesheet" href="style_index.css">
</head>
<body>
<header>
	<?php include('header.php'); ?>
</header>
<?php
	require('connect.php');
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$errors = array();
		$nome = filter_var($_POST['nome'], FILTER_SANITIZE_STRING);
		$nome = mysqli_real_escape_string($mysqli,$_POST['nome']);
		if ((!empty($nome)) && (preg_match('/^[a-zA-Z ]*$/',$nome)) && (strlen($nome) <= 30)) {
			$nome_controllato = $nome;
		} else {
			$errors[] = 'Hai dimenticato di inserire il nome o non rispetta i dovuti parametri.'; 
		}
		$cognome = filter_var( $_POST['cognome'], FILTER_SANITIZE_STRING);
		$cognome = mysqli_real_escape_string($mysqli,$_POST['cognome']);
		if ((!empty($cognome)) && (preg_match('/^[a-zA-Z ]*$/',$cognome)) && (strlen($cognome) <= 40)) { 
			$cognome_controllato = $cognome;
		} else {
			$errors[] = 'Hai dimenticato di inserire il cognome o non rispetta i dovuti parametri.'; 
		}
		$username = filter_var( $_POST['username'], FILTER_SANITIZE_STRING);
		$username = mysqli_real_escape_string($mysqli,$_POST['username']); 
		if ((!empty($username)) && (preg_match('/^[a-zA-Z0-9_-]*$/',$username)) && (strlen($username) <= 30)) {
			$username_controllato = $username;
		} else {
			$errors[] = 'Hai dimenticato di inserire l\'username o non rispetta i dovuti parametri.'; 
		}
		$email = filter_var( $_POST['email'], FILTER_SANITIZE_EMAIL);
		$email = mysqli_real_escape_string($mysqli,$_POST['email']);
		if ((empty($email)) || (!filter_var($email, FILTER_VALIDATE_EMAIL)) || (strlen($email > 60))) {
			$errors[] = 'Hai dimenticato di inserire l\'email';
			$errors[] = 'Oppure la forma non è corretta.';
		}

		if (empty($errors)) {
			$q = mysqli_stmt_init($mysqli);
			$query = 'SELECT id_utente FROM Utente WHERE (email=? OR username=?) AND id_utente !=?';
			mysqli_stmt_prepare($q, $query);
			mysqli_stmt_bind_param($q, 'ssi', $email, $username_controllato, $_SESSION['id_utente']);
			mysqli_stmt_execute($q);
			$result = mysqli_stmt_get_result($q);
			if (mysqli_num_rows($result) == 0) {
				$query = 'UPDATE Utente SET nome=?, cognome=?, username=?, email=? WHERE id_utente=? LIMIT 1';
				$q = mysqli_stmt_init($mysqli);
				mysqli_stmt_prepare($q, $query);
				mysqli_stmt_bind_param($q, 'ssssi', $nome_controllato, $cognome_controllato, $username_controllato, $email, $_SESSION['id_utente']);
				mysqli_stmt_execute($q);
				if (mysqli_stmt_affected_rows($q) == 1) { 
					echo '<h3 style = color:green class="text-center">Dati utente modificati.</h3>';
				} else {
					echo '<p style = color:red class="text-center">Dati utente non modificati.</p>';
					//echo '<p>' . mysqli_error($mysqli) . '<br />Query: ' . $q . '</p>';
				}
			} else { //utente già registrato con l'email o username inseriti 
				echo '<p style = color:red class="text-center">Email o Username già utilizzati.</p>';
			}
			mysqli_free_result($result);
			mysqli_stmt_close($q);
		} else {
			echo '<p class="text-center">Errore! <br /> Si sono verificati i seguenti errori(e):<br />';
			foreach ($errors as $msg) { 
				echo " - $msg<br />\n";
			}
			echo '</p><p>Riprova di nuovo.</p>';
		}
	}

	$q = mysqli_stmt_init($mysqli);
	$query = "SELECT nome, cognome, username, email FROM Utente WHERE id_utente=?";
	mysqli_stmt_prepare($q, $query);
	mysqli_stmt_bind_param($q, 'i', $_SESSION['id_utente']);
	mysqli_stmt_execute($q);
	$result = mysqli_stmt_get_result($q);
	$row = mysqli_fetch_array($result, MYSQLI_NUM); 
	if(mysqli_num_rows($result) == 1) {

	?>
		<form action="modifica_utente.php" method="post" name="editform" id="editform">
			<br>
			<br>
			<label> Nome:
				<input name="nome" type="text" placeholder = "Nome" minlength = "3" value="<?php echo htmlspecialchars($row[0], ENT_QUOTES); ?>" />
			</label>
			<p>
				<label> Cognome:
					<input name = "cognome" type = "text" placeholder = "Cognome" mainlength = "2" value="<?php echo htmlspecialchars($row[1], ENT_QUOTES); ?>" />
				</label>
			</p>
			<p>
				<label> Username:
					<input name = "username" type = "text" placeholder = "Username" mainlength = "4" value="<?php echo htmlspecialchars($row[2], ENT_QUOTES); ?>" />
				</label>
			</p>
			<p>
				<label> E-mail:
					<input name = "email" type = "text" placeholder = "E-mail" mainlength = "12" value="<?php echo htmlspecialchars($row[3], ENT_QUOTES); ?>"/> 
				</label>
			</p>
			<p>
				<input name = "invia" type = "submit" value = "Modifica dati" />
			</p>
		</form>	
	<?php
	} else{
		echo '<p class="text-center">Errore con la form.</p>';
	}
	mysqli_free_result($result);
	mysqli_stmt_free_result($q);
   	mysqli_close($mysqli);
   	?>
   	<footer>
    <div id="footerlogin">
      <p>Realizzato da Triolo Gabriele e Giurdanella Chiara</p>
    </div>
  </footer>
</body>
</html>
