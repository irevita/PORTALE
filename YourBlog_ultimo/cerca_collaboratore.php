<?php
include('check_session.php');
require('connect.php');
$output = '';
if(isset($_POST["query"])) {
	$search = mysqli_real_escape_string($mysqli, $_POST["query"]);
	$query = "SELECT * FROM Utente WHERE (username LIKE '%".$search."%' OR nome LIKE '%".$search."%' OR cognome LIKE '%".$search."%' or email LIKE '%".$search."%') and id_utente <> '".$_SESSION['id_utente']."'";
} else {
	$query = "SELECT * FROM Utente WHERE id_utente <> '".$_SESSION['id_utente']."' ORDER BY id_utente";
}
$result = mysqli_query($mysqli, $query);

if(mysqli_num_rows($result) > 0) {
	$output .= '<div class="table-responsive">
					<table class="table table bordered">
						<tr>
							<th>Username</th>
							<th>Nome</th>
							<th>cognome</th>
							<th>Email</th>
						</tr>';
	while($row = mysqli_fetch_array($result)) {
		$output .= '
					<tr>
						<td>'.$row["username"].'</td>
						<td>'.$row["nome"].'</td>
						<td>'.$row["cognome"].'</td>
						<td>'.$row["email"].'</td>
						<td scope="row"><a href = "add_collaboratore.php?id=' . $row["id_utente"].'"> Aggiungi </a></td>
					</tr>
					';
	}
	echo $output;
} else {
	echo 'Nessun risultato.';
}
?>