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
	<title>Profilo Utente</title> 
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="style_index.css">
</head>
<body>
<header>
	<?php include('header.php'); ?>
</header>
<div class="row" style="padding-left: 0px;">
	<?php
	require('connect.php');
	$query = "SELECT username FROM Utente WHERE id_utente= ".$_SESSION['id_utente']."";
	$result = mysqli_query($mysqli, $query);
	foreach($result as $row){
        $username = $row["username"];
    }
	if(mysqli_num_rows($result) == 1) {
		echo '<h4 class="text-center">Loggato come: '.$username.'</h4>';
	} else {
		echo '<h4>Operazione non riuscita. </h4>';
	}
	mysqli_close($mysqli);
	?>
	<footer>
    	<div id="footerlogin">
     		 <p>Realizzato da Triolo Gabriele e Giurdanella Chiara</p>
    	</div>
    </footer>
</body>
</html>