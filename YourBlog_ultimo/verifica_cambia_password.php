<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') { 
	require('connect.php'); 


//controllo email   
$email = filter_var( $_POST['email'], FILTER_SANITIZE_EMAIL);
$email = mysqli_real_escape_string($mysqli,$_POST['email']);
if ((empty($email)) || (!filter_var($email, FILTER_VALIDATE_EMAIL)) || (strlen($email > 60))) {
	$errors[] = 'Hai dimenticato di inserire l\'email';
	$errors[] = 'oppure la forma non è corretta.';
}
//controllo che l'utente abbia inserito esclusivamente la sua email per poter cambiare password
$query = "SELECT email FROM Utente WHERE id_utente=".$_SESSION['id_utente']."";
$result= mysqli_query($mysqli,$query);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
if($email != $row["email"] && !empty($email)) {
	$errors[] = 'L\'email inserita non è associata al tuo account.';
}
//controllo password
$password = filter_var( $_POST['password'], FILTER_SANITIZE_STRING);
$password = mysqli_real_escape_string($mysqli,$_POST['password']);
if (empty($password)) {
	$errors[] = 'Hai dimenticato di inserire la password.';
}

//controllo la nuova password
$new_password = trim($_POST['password1']);
$verify_password = trim($_POST['password2']);
if (!empty($new_password)) {
	if (($new_password != $verify_password) || ( $password == $new_password )) {
		$errors[] = 'Le password non corrispondono e/o non hai modificato la tua attuale password.';
	}
} else {
	$errors[] = 'Non hai inserito la nuova password.';
}

if (empty($errors)) {

	//controllo che l'utente abbia inserito la giusta combinazione email/password 
	$query = "SELECT id_utente, password FROM Utente WHERE ( email=? )";
	$q = mysqli_stmt_init($mysqli);
	mysqli_stmt_prepare($q, $query);
	mysqli_stmt_bind_param($q, 's', $email);
	mysqli_stmt_execute($q);
	$result = mysqli_stmt_get_result($q);
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	if ((mysqli_num_rows($result) == 1) && (password_verify($password, $row['password']))) {
		//aggiorniamo la password
		$hashed_passcode = password_hash($new_password, PASSWORD_DEFAULT);
		$query = "UPDATE Utente SET password=? WHERE email=?";
		$q = mysqli_stmt_init($mysqli);
		mysqli_stmt_prepare($q, $query);
		mysqli_stmt_bind_param($q, 'ss', $hashed_passcode, $email);
		mysqli_stmt_execute($q);
		if (mysqli_stmt_affected_rows($q) == 1) {
			echo '<h3 style = color:green class="text-center">Password cambiata con successo!</h3>';
			exit();
		} else {
			$errorstring = "Errore! <br /> Password non modificata.</p>";
			echo "<p class='text-center col-sm-2' style='color:red'>$errorstring</p>";
			//echo '<p>' . mysqli_error($mysqli) . '<br><br>Query: ' . $query . '</p>';
			exit();
		}
		mysqli_free_result($result);
		mysqli_stmt_close($q);
	} else {
		$errorstring = "Email e/o password correnti non corrispondono.</p>";
		echo "<p class='text-center col-sm-2' style='color:red'>$errorstring</p>";
	}
	mysqli_free_result($result);
	mysqli_stmt_close($q);
} else {
	$errorstring = "Errore! <br /> Si sono verificati i seguenti errori(e):<br>";
	foreach ($errors as $msg) {
		$errorstring .= " - $msg<br>\n";
}
$errorstring .= "Prova di nuovo.<br>";
echo "<p style='color:red'>$errorstring</p>";
}
mysqli_close($mysqli);
}
?>
































