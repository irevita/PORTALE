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
	<title>Elimina profilo</title> 
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
		$sure = htmlspecialchars($_POST['sure'], ENT_QUOTES);
		if ($sure == 'Yes') {
			$q = mysqli_stmt_init($mysqli);
			mysqli_stmt_prepare($q, 'DELETE FROM Utente WHERE id_utente=? LIMIT 1');
			mysqli_stmt_bind_param($q, "s", $_SESSION['id_utente']);
			mysqli_stmt_execute($q);
			if (mysqli_stmt_affected_rows($q) == 1) {
				header('location:logout.php');
			} else {
				echo '<p class="text-center">Utente non eliminato.</p>';
				// echo '<p>' . mysqli_error($mysqli) . '<br />Query: ' . $q . '</p>';
			}
		} else { //l'utente non ha confermato 
			echo '<h3 class="text-center">Utente non eliminato.</h3>';
		}
	} else {
		$q = mysqli_stmt_init($mysqli);
		$query = "SELECT CONCAT(nome, ' ', cognome) FROM Utente WHERE id_utente=?";
		mysqli_stmt_prepare($q, $query);
		mysqli_stmt_bind_param($q, "s", $_SESSION['id_utente']);
		mysqli_stmt_execute($q);
		$result = mysqli_stmt_get_result($q);
		$row = mysqli_fetch_array($result, MYSQLI_NUM);
		if (mysqli_num_rows($result) == 1) {
			$user = htmlspecialchars($row[0], ENT_QUOTES);
?>
	<h2 class="h2 text-center"> Sei sicuro di voler eliminare definitivamente il tuo account: <?php echo $user; ?>?</h2>
	<form action="elimina_utente.php" method="post"name="deleteform" id="deleteform">
		<input type="hidden" name="id" value="<?php echo $_SESSION['id_utente']; ?>">
		<input id="submit-yes" type="submit" name="sure" value="Yes"> -
		<input id="submit-no" type="submit" name="sure" value="No">
	</form>
	<?php
	}
mysqli_stmt_close($q);
mysqli_close($mysqli);
}  
?>
<footer>
    <div id="footerlogin">
      <p>Realizzato da Triolo Gabriele e Giurdanella Chiara</p>
    </div>
  </footer>
</body>
</html>


























