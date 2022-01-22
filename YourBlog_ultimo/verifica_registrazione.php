<?php 
require ('connect.php');
$errors = array(); //inizializza un array di errori
$nome = filter_var($_POST['nome'], FILTER_SANITIZE_STRING);
$nome = mysqli_real_escape_string($mysqli,$_POST['nome']);
$nome = trim($_POST['nome']);
if ((!empty($nome)) && (preg_match('/^[a-zA-Z ]*$/',$nome)) && (strlen($nome) <= 30)) {
	$nome_controllato = $nome;
} else {
	$errors[] = 'Hai dimenticato di inserire il nome o non rispetta i dovuti parametri.'; 
}


function contiene($stringa, $cerca, $case_sensitive=false) {
  if ($case_sensitive)
    return strpos($stringa, $cerca) !== false;
  else
    return stripos($stringa, $cerca) !== false;
}

if ((contiene($nome, 'select') !== false) || (contiene($nome, 'insert') !== false) || (contiene($nome, 'update') !== false) || (contiene($nome, 'delete') !== false) || (contiene($nome, 'drop') !== false) || (contiene($nome, 'alter') !== false) ) {
  $errors[] = 'Parole vietate nel campo nome.';
}


$cognome = filter_var( $_POST['cognome'], FILTER_SANITIZE_STRING);
$cognome = mysqli_real_escape_string($mysqli,$_POST['cognome']);
$cognome = trim($_POST['cognome']);
if ((!empty($cognome)) && (preg_match('/^[a-zA-Z ]*$/',$cognome)) && (strlen($cognome) <= 40)) { 
	$cognome_controllato = $cognome;
} else {
	$errors[] = 'Hai dimenticato di inserire il cognome o non rispetta i dovuti parametri.'; 
}

if ((contiene($cognome, 'select') !== false) || (contiene($cognome, 'insert') !== false) || (contiene($cognome, 'update') !== false) || (contiene($cognome, 'delete') !== false) || (contiene($cognome, 'drop') !== false) || (contiene($cognome, 'alter') !== false) ) {
  $errors[] = 'Parole vietate nel campo cognome.';
}


$username = filter_var( $_POST['username'], FILTER_SANITIZE_STRING);
$username = mysqli_real_escape_string($mysqli,$_POST['username']);
$username = trim($_POST['username']);
if ((!empty($username)) && (preg_match('/^[a-zA-Z0-9_-]*$/',$username)) && (strlen($username) <= 30)) {
	$username_controllato = $username;
} else {
	$errors[] = 'Hai dimenticato di inserire l\'username o non rispetta i dovuti parametri.'; 
}

if ((contiene($username, 'select') !== false) || (contiene($username, 'insert') !== false) || (contiene($username, 'update') !== false) || (contiene($username, 'delete') !== false) || (contiene($username, 'drop') !== false) || (contiene($username, 'alter') !== false) ) {
  $errors[] = 'Parole vietate nel campo username.';
}

$email = filter_var( $_POST['email'], FILTER_SANITIZE_EMAIL);
$email = mysqli_real_escape_string($mysqli,$_POST['email']);
$email = trim($_POST['email']);
if ((empty($email)) || (!filter_var($email, FILTER_VALIDATE_EMAIL)) || (strlen($email > 60))) {
	$errors[] = 'Hai dimenticato di inserire l\'email';
	$errors[] = 'Oppure la forma non è corretta.';
}

if ((contiene($email, 'select') !== false) || (contiene($email, 'insert') !== false) || (contiene($email, 'update') !== false) || (contiene($email, 'delete') !== false) || (contiene($email, 'drop') !== false) || (contiene($email, 'alter') !== false) ) {
  $errors[] = 'Parole vietate nel campo email.';
}


//controllo se le password corrispondono
$password1 = filter_var( $_POST['password1'], FILTER_SANITIZE_STRING);
$password1 = mysqli_real_escape_string($mysqli,$_POST['password1']);
$password2 = filter_var( $_POST['password2'], FILTER_SANITIZE_STRING);
$password2 = mysqli_real_escape_string($mysqli,$_POST['password2']);
if (!empty($password1)) {
	if ($password1 !== $password2) {
		$errors[] = 'Le password non corrispondono.';
	} 
} else {
		$errors[] = 'Password non inserita.';
}

if (empty($errors)) {
	//controllo se l'email inserita è stata utilizzata per altre registrazioni o no
	require ('connect.php');
	$query = "SELECT id_utente FROM Utente WHERE email = ? OR username = ?";
	$q = mysqli_stmt_init($mysqli);
	mysqli_stmt_prepare($q, $query);
	mysqli_stmt_bind_param($q, 'ss', $email, $username_controllato);
	mysqli_stmt_execute($q);
	$result = mysqli_stmt_get_result($q);
	if (mysqli_num_rows($result) == 0) { //l'email non è stata utilizzata finora e quindi si può continuare 
		$hashed_passcode = password_hash($password1, PASSWORD_DEFAULT);
		require ('connect.php');
		$query = "INSERT INTO Utente (id_utente, nome, cognome, username, email, password, data_registrazione)"; 
		$query .= "VALUES(' ', ?, ?, ?, ?, ?, NOW() )";
		$q = mysqli_stmt_init($mysqli);
		mysqli_stmt_prepare($q, $query);
		mysqli_stmt_bind_param($q, 'sssss', $nome_controllato, $cognome_controllato, $username_controllato, $email, $hashed_passcode);
		mysqli_stmt_execute($q);
		if (mysqli_stmt_affected_rows($q) == 1) {  //record inserito nel database
			header ("location: ringraziamento_registrazione.php");
			exit();
		} else {
			$errorstring = "<p style = 'color:red'>";
			$errorstring .= "System Error<br />Registrazione fallita.</p>";
			echo "<p style = 'color:red'> $errorstring </p>";
			mysqli_close($mysqli);
			exit();
		}
	} else { //l'email è stata utilizzata 
		$errorstring = 'Email o Username già utilizzati.';
		echo "<p style = 'color:red'> $errorstring </p>";
	}
	mysqli_stmt_free_result($q);
	mysqli_stmt_close($q);
}else {
			$errorstring = "Errore! <br /> Si sono verificati i seguenti errori(e):<br>";
			foreach ($errors as $msg) {
				$errorstring .= " - $msg<br>\n";
			}
			$errorstring .= "Prova di nuovo.<br>";
			echo "<p style = 'color:red'>$errorstring</p>";
}
mysqli_close($mysqli);
?>