<!DOCTYPE html>
<html lang = "it">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  	<link rel="stylesheet" href="style_index.css">
 </head>
 <body>
<?php
require('connect.php');
$output = '';
if(isset($_POST["query"])) {
	$search = mysqli_real_escape_string($mysqli, $_POST["query"]);
	$query = "SELECT id_blog,titolo,autore,descrizione,data_blog,immagine,username,Categoria.nomeC FROM Blog,Categoria,Utente WHERE Blog.categoria = Categoria.id_categoria and Blog.autore = Utente.id_utente and username LIKE '%".$search."%'";
	$result = mysqli_query($mysqli, $query);

if(mysqli_num_rows($result) > 0) {
	echo'<p>Ecco i risultati della ricerca:</p>';
	while($row = mysqli_fetch_array($result))
	{
		 echo '
        <div class="blogcontainer">
          <img class="imgBlog" src='.$row["immagine"].' width="50%" height="200">
          <h4 class="card-title">'.$row["titolo"].'</h4>
          <p class="card-subtitle">Autore: '.$row["username"].'</p>
          <p class="card-subtitle mb-2 text-muted">Categoria: '.$row["nomeC"].'</p>
          <p class="card-subtitle mb-2 text-muted">Pubblicato il: '.$row["data_blog"].'</p>
          <a href="apertura_blog.php?id=' . $row["id_blog"].'" id = "leggi">Vai al Blog </a>';
        echo'</div>';
	}
	
}else {
	echo 'Blog non presente.';
} 
mysqli_free_result($result); 
}
mysqli_close($mysqli);
?>
</body>
</html>