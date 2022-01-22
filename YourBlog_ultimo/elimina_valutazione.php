<?php
if((isset($_GET['id'])) && (is_numeric($_GET['id'])) &&(isset($_GET['blog']))) {  
    $id_valutazione = htmlspecialchars($_GET['id'], ENT_QUOTES);
    $id_post = htmlspecialchars($_GET['id_post'], ENT_QUOTES);
    $blog = htmlspecialchars($_GET['blog'], ENT_QUOTES);
}else {
    echo '<p class="error">ID non valido.</p>'; 
	exit();
}
require('connect.php');

$query = 'DELETE FROM Valutazione WHERE id_valutazione ='.$id_valutazione.'';
$result = mysqli_query($mysqli, $query);
header("Location: apertura_post.php?id=".$id_post."&blog=".$blog."");
?>