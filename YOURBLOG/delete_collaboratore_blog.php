<?php
if((isset($_GET['id'])) && (is_numeric($_GET['id']))) {
	$id = htmlspecialchars($_GET['id'], ENT_QUOTES);
}elseif((isset($_POST['id'])) && (is_numeric($_POST['id']))) {
	$id = $_POST['id'];
}else {
	header("Location: login.php"); 
	exit();
}

require("connect.php");

$q = mysqli_stmt_init($mysqli);
mysqli_stmt_prepare($q, 'DELETE FROM Collaboratore WHERE blog =? LIMIT 1');
mysqli_stmt_bind_param($q, "i", $id);
mysqli_stmt_execute($q);
if (mysqli_stmt_affected_rows($q) == 1) {
	header("Location: lista_blog.php");
} else {
	echo '<p>Collaboratore non rimosso.</p>';
	// echo '<p>' . mysqli_error($mysqli) . '<br />Query: ' . $q . '</p>'; //debugging 
}

mysqli_stmt_close($q);
mysqli_close($mysqli);
?>