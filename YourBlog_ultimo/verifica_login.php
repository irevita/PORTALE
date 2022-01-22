<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') { 
	require('connect.php'); 

function contiene($stringa, $cerca, $case_sensitive=false) {
  if ($case_sensitive)
    return strpos($stringa, $cerca) !== false;
  else
    return stripos($stringa, $cerca) !== false;
}

$email = filter_var( $_POST['email'], FILTER_SANITIZE_EMAIL);
$email = mysqli_real_escape_string($mysqli,$_POST['email']);
if ((empty($email)) || (!filter_var($email, FILTER_VALIDATE_EMAIL)) || (strlen($email > 60))) {
	$errors[] = 'Hai dimenticato di inserire l\'email';
	$errors[] = 'oppure la forma non è corretta.';
}

if ((contiene($email, 'select') !== false) || (contiene($email, 'insert') !== false) || (contiene($email, 'update') !== false) || (contiene($email, 'delete') !== false) || (contiene($email, 'drop') !== false) || (contiene($email, 'alter') !== false) ) {
  $errors[] = 'Parole vietate nel campo email.';
}


$password = filter_var( $_POST['password'], FILTER_SANITIZE_STRING);
$password = mysqli_real_escape_string($mysqli,$_POST['password']);
if (empty($password)) {
	$errors[] = 'Hai dimenticato di inserire la password.';
}

if(empty($errors)) {
	$query = "SELECT id_utente, password, email, nome FROM Utente WHERE email=?";
	$q = mysqli_stmt_init($mysqli); 
	mysqli_stmt_prepare($q, $query); 
	mysqli_stmt_bind_param($q, "s", $email); 
	mysqli_stmt_execute($q); 
	$result = mysqli_stmt_get_result($q);
	$row = mysqli_fetch_array($result, MYSQLI_NUM);
	if(mysqli_num_rows($result) == 1) {
		if (password_verify($password, $row[1])) { 
			session_start();
			$_SESSION['id_utente'] = $row[0];
			$_SESSION['email'] = $row[2];
			$_SESSION['nome'] = $row[3];
			header("Location: profilo.php");
		} else { //la password inserita dall'utente non corrisponde con quella del db --> hashed
			$errors[] = 'E-mail/password non esistenti nel database. ';
			$errors[] = 'Assicurati di esserti registrato.';
		}
	} else {
			$errors[] = 'E-mail/password non esistenti nel database. ';
			$errors[] = 'Assicurati di esserti registrato.';
		
	}	
	mysqli_stmt_free_result($q);
	mysqli_stmt_close($q);
}


if (!empty($errors)) {
	$errorstring = "Errore! <br /> Ecco i seguenti errori(e):<br>";
	foreach ($errors as $msg) {
		$errorstring .= " - $msg<br>\n";
	}
	$errorstring .= "Riprova!<br>";
	echo "<p style = 'color:red'>$errorstring</p>";
}
mysqli_close($mysqli);
}
?>