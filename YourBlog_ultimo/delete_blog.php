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
$query = "SELECT id_categoria FROM Categoria,Blog WHERE id_blog=".$id." and Blog.categoria = Categoria.id_categoria ";
$result= mysqli_query($mysqli,$query);
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $id_categoria = $row["id_categoria"];
}

$query2 = "DELETE FROM Categoria WHERE id_categoria =".$id_categoria."";
$result= mysqli_query($mysqli,$query2);

 
$q = mysqli_stmt_init($mysqli);
mysqli_stmt_prepare($q, 'DELETE FROM Blog WHERE id_blog =? LIMIT 1');
mysqli_stmt_bind_param($q, "i", $id);
mysqli_stmt_execute($q);
if (mysqli_stmt_affected_rows($q) == 1) {
	header("Location: lista_blog.php");
} else {
	header("Location: lista_blog.php");
	// echo '<p>' . mysqli_error($mysqli) . '<br />Query: ' . $q . '</p>'; //debugging 
}
mysqli_free_result($result);
mysqli_stmt_close($q);
mysqli_close($mysqli);
?>