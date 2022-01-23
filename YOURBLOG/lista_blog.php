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
	<title>I tuoi Blog</title> 
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
$id = $_SESSION['id_utente'];
$query = "SELECT id_blog, id_utente, data_blog, titolo, descrizione, nomeC, immagine FROM Blog, Utente, Categoria WHERE id_utente = ? AND Utente.id_utente = Blog.autore and Blog.categoria = Categoria.id_categoria ORDER BY data_blog ASC";
$q = mysqli_stmt_init($mysqli);
mysqli_stmt_prepare($q, $query);
mysqli_stmt_bind_param($q, "i", $id);
mysqli_stmt_execute($q);
$result = mysqli_stmt_get_result($q);
if(mysqli_num_rows($result) > 0) {
	 
?>

<?php
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$id_blog = htmlspecialchars($row['id_blog'], ENT_QUOTES);
		$id_utente = htmlspecialchars($row['id_utente'], ENT_QUOTES);
		$data_blog = htmlspecialchars($row['data_blog'], ENT_QUOTES);
		$titolo = htmlspecialchars($row['titolo'], ENT_QUOTES);
		$descrizione = htmlspecialchars($row['descrizione'], ENT_QUOTES);
		$categoria = htmlspecialchars($row['nomeC'], ENT_QUOTES);

		echo '
		  		<div class="blogcontainer">
         			 <img class="imgBlog" src='.$row["immagine"].' width="50%" height="200">
         			 <h4 class="card-title">Titolo: '.$row["titolo"].'</h4>
		 			 <p class="card-subtitle mb-2 text-muted">Categoria: '.$row["nomeC"].'</p>
        			 <p class="card-subtitle mb-2 text-muted">Pubblicato il: '.$row["data_blog"].'</p>
         			 <a href="apertura_blog.php?id=' . $row["id_blog"].'" id = "leggi">Vai al Blog </a> &emsp;
		 			 <a href = "edit_blog.php?id=' . $id_blog.'" id = "leggi"> Edit </a> &emsp;
		  			 <a href = "delete_blog.php?id=' . $id_blog.'" id = "leggi"> Delete </a>';
        echo'   </div>';
 	}
 	
 	mysqli_stmt_close($q);
 	mysqli_free_result($result);

} else {
	echo '<p> Non sei autore di nessun Blog </p>';
	//echo '<p>' . mysqli_error($mysqli) . '<br><br>Query: ' . $q . '</p>';
}


$query1 = "SELECT * FROM Blog WHERE 
				EXISTS (SELECT blog,utente,username FROM Collaboratore,Utente WHERE Blog.id_blog = Collaboratore.blog AND Utente.id_utente = Collaboratore.utente AND Utente.id_utente =".$_SESSION['id_utente'].")";
$result = mysqli_query($mysqli, $query1);
if(mysqli_num_rows($result) > 0) {
	echo '<h3 class="text-center">Sei collaboratore dei seguenti blog:</h3>'; 
?>



<?php
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$id_blog = htmlspecialchars($row['id_blog'], ENT_QUOTES);
		$titolo = htmlspecialchars($row['titolo'], ENT_QUOTES);


	echo '
			<div class="blogcontainer">
			 	<img class="imgBlog" src='.$row["immagine"].' width="50%" height="200">
				<h4 class="card-title">Titolo: '.$row["titolo"].'</h4>
				<a href = "edit_blog.php?id=' . $id_blog.'" id = "leggi"> Edit </a> &emsp;
				<a href = "apertura_blog.php?id=' . $id_blog.'" id = "leggi"> Vai al Blog </a> &emsp;
				<a href = "delete_collaboratore_blog.php?id=' . $id_blog.'" id = "leggi"> Rimuovimi </a>
 			</div>';
 	}


} else {
	echo '<p> Non sei collaboratore di nessun Blog </p>';
	//echo '<p>' . mysqli_error($mysqli) . '<br><br>Query: ' . $q . '</p>';
}

mysqli_free_result($result);
mysqli_close($mysqli);
?>
<footer >
	<div id="footerlogin">
			<p>Realizzato da Triolo Gabriele e Giurdanella Chiara</p>
	</div>
</footer>
</body>
</html>
