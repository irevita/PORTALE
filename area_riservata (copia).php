<?php include "connessione.php"; ?>
<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Connessione al DB con PHP</title>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

</head>

<body>

<div class="container">

  <nav class="navbar navbar-dark bg-dark">
    <span class="h3" class="navbar-brand mb-0">Esci dalla tua area personale :'(</span>
    <span>
      <a href="logout.php">
      <button type="button"class="btn btn-danger">
        Logout
      </button></a>
    </span>
</nav>

  <h1>Area riservata</h1>
  <h3>Ciao <?php echo $_SESSION['utente'] ?>, benvenut nella tua area personale!</h3>
  <div class="row">

  <div class="col-md-3" id="categorie">


  <h4>CATEGORIE</h4>

<?php

$query = "SELECT Categoria FROM Categoria";
$mostraCategorie = mysqli_query($connessione, $query);
while($row = mysqli_fetch_array($mostraCategorie)){
	$NomeCategoria = $row["Categoria"];

  echo "<button name='{$NomeCategoria}' type='button' class='btn btn-block categorie'>{$NomeCategoria}</button>";


}
?>

</div>
  <div class="col-md-9">
<h3>I miei articoli</h3>

<?php

$query2 = "SELECT Articoli.Titolo, Articoli.TESTO FROM Articoli
    WHERE Articoli.AutoreArt = {$_SESSION['id']}";

  $mostraArticoli = mysqli_query($connessione, $query2);

  while($row = mysqli_fetch_array($mostraArticoli)){

	   $NomeTitolo = $row["Titolo"];
     $TestoArticolo = $row["TESTO"];

	    echo "<br />";
	    echo "<h3>{$NomeTitolo}</h3>";
      echo "<p>{$TestoArticolo}</p><br />";
      echo '<button type="button" class="btn btn-success"><i class="fas fa-thumbs-up"></i> Like</button>';
}

?>
</div>
<form name=”casellaTesto” method=”post” action=””>
  <textarea name="message" style="width:200px; height:600px;">
The cat was playing in the garden.
</textarea>
<span>
    <button name="eliminazione" type="submit" class="btn btn-danger" data-toggle ="modal" data-target="#loginModal"><i class="fas fa-trash"></i> Elimina account</button>
  </span>
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Eliminazione account</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Sei sicuro di voler eliminare il tuo account? </br> Se lo farai, eliminerai il tuo blog e tutto il suo contenuto!</p>
      </div>
      <div class="modal-footer">
        <form action="area_riservata.php" method="post">
          <button name="delete" type="submit" class="btn btn-secondary">Si, elimina</button>
          <button name="indietro" type="submit" class="btn btn-primary">Torna indietro</button>
        </form>
      </div>
    </div>
  </div>
</div>


<?php

  if (isset($_POST['delete'])) {

    $query = "DELETE FROM Utenti WHERE ID_Utente={$_SESSION['id']}";

    if (mysqli_query($connessione, $query)) {
       echo "Account eliminato";
       header('Location: index.php');
    }else {
        echo "Error deleting record: " .mysqli_error($connessione);
        header('Location: area_riservata.php');
    }
  }

  if (isset($_POST['indietro'])) {

    header('Location: area_riservata.php');
  }

 ?>
  </div>
</div>

 <?php include "footer.php" ?>

</body>
</html>
