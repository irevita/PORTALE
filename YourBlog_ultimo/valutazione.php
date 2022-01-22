<?php
include('check_session.php');
if((isset($_GET['id'])) && (is_numeric($_GET['id'])) &&(isset($_GET['blog']))) { 
	$id_post = htmlspecialchars($_GET['id'], ENT_QUOTES);
	$blog = htmlspecialchars($_GET['blog'], ENT_QUOTES);
} elseif((isset($_POST['id'])) && (is_numeric($_POST['id'])) &&(isset($_POST['blog']))) { 
    $id_post = htmlspecialchars($_POST['id'], ENT_QUOTES);
    $blog = htmlspecialchars($_POST['blog'], ENT_QUOTES);
}else {
   	echo '<p class="error">ID non valido.</p>'; 
    exit();
    }

require('connect.php');

$query = 'INSERT INTO Valutazione VALUES(null,'.$id_post.','.$_SESSION['id_utente'].','.$_POST["rating"].')';
$result = mysqli_query($mysqli, $query);
header('Location: apertura_post.php?id='.$id_post.'&blog='.$blog.'');

mysqli_free_result($result);
mysqli_close($mysqli);
?>