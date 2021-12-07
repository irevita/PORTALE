<?php include "connessione.php"; 
  $query_categorie = mysqli_query($connessione, "SELECT Categoria FROM Categoria");

?>

<?php

$avviso = "";

if(isset($_POST['submit'])){

  $nome = $_POST['nome'];
  $cognome = $_POST['cognome'];
  $nazione = $_POST['nazione'];
  $email = $_POST['email'];
  $dataN = $_POST['data'];
  $telefono = $_POST['tel'];
  $documento = $_POST['documento'];
  $nickname = $_POST['username'];
  $password = $_POST['password'];

  if(!empty($nome) &&!empty($cognome) &&!empty($nazione)
   &&!empty($email) &&!empty($dataN) &&!empty($telefono)
   &&!empty($documento) &&!empty($nickname) &&!empty($password)){

    $query = "INSERT INTO Utenti(Nick, PasswordID, Nome, Cognome, Nazione, Email, DatadiNascita, Telefono, Documento)
     VALUES ('{$nickname}','{$password}','{$nome}','{$cognome}','{$nazione}','{$email}','{$dataN}','{$telefono}','{$documento}')";

    $creaUtenti = mysqli_query($connessione, $query);

    if(!$creaUtenti){
      die('Query fallita'.mysqli_error($connessioneDB));
      echo "query fallita";
    }

    $avviso = "Dati registrati con successo";
    echo $avviso;

  }else{
    $avviso = "I campi non devono essere vuoti";
    echo $avviso;
  }
}

 ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Connessione al DB con PHP</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <!-- <link href="reg2.css" rel="stylesheet"/> -->
</head>

<body>

  <?php include "header.php"?>

  <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Entra nell'area riservata</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="login.php" method="post">
            <div class="form-group">
                <input name="username" type="text" class="form-control" id="exampleInputEmail" placeholder="Inserisci il tuo username" aria-describedby="emailHelp">
            </div>
            <div class="form-group">
                <input name="password" type="password" class="form-control" id="exampleInputPassword" placeholder="Inserisci la tua password">
            </div>
            <button name="login" type="submit" class="btn btn-success">Accedi</button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="regModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Compila i campi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="index.php" method="post">
            <div class="form-group">
                <input name="nome" type="text" class="form-control" id="inputNome" placeholder="Nome">
            </div>
            <div class="form-group">
                <input name="cognome" type="text" class="form-control" id="inputCognome" placeholder="Cognome">
            </div>
            <div class="form-group">
                <input name="nazione" type="text" class="form-control" id="inputNazione" placeholder="Nazione">
            </div>
            <div class="form-group">
                <input name="email" type="email" class="form-control" id="inputEmail" placeholder="Email Address">
            </div>
            <div class="form-group">
                <input name="data" type="date" class="form-control" id="inputData" placeholder="Data di nascita">
            </div>
            <div class="form-group">
                <input name="tel" type="tel" class="form-control" id="inputTel" placeholder="Telefono">
            </div>
            <div class="form-group">
                <input name="documento" type="text" class="form-control" id="inputDocumento" placeholder="Documento">
            </div>
            <div class="form-group">
                <input name="username" type="text" class="form-control" id="inputUsername" placeholder="Nickname">
            </div>
            <div class="form-group">
                <input name="password" type="password" class="form-control" id="inputPassword" placeholder="Password">
            </div>
            <button name="submit" type="submit" class="btn btn-primary">Invia</button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <div class="container">
    <div class="row">
      <div class="col-md-3">
        <br/>
        <h3>CATEGORIE</h3>
        <div class="list-item">
            <!-- sottocategoria -->
          <ul><?php while($row = mysqli_fetch_array($query_categorie)) { ?>
            <button type='button' class='btn btn-block'> <?php echo '<a href="index.php?categoria='.$row["Categoria"].'">'.$row["Categoria"]."</a>"; ?> </button><?php } ?>
          </ul>
        </div>
      </div>


      <div class="col-md-9">  

        <div class="articoli_tendenza">

          <h3>ARTICOLI DI TENDENZA</h3>

          <?php

            $query = "SELECT Articoli.Titolo, Articoli.TESTO
              FROM Likes, Articoli
              WHERE Likes.CodiceArt = Articoli.CodiceArt
              GROUP BY (Articoli.CodiceArt)
              ORDER BY (COUNT(Articoli.CodiceArt)) DESC";

            $mostraArticoli = mysqli_query($connessione,$query);

            while ($row = mysqli_fetch_array($mostraArticoli)){
              $NomeArticolo =$row["Titolo"];
              $TestoArticolo =$row["TESTO"];

              echo "<tr>";
              echo "<td> <h3> {$NomeArticolo} </h3> </td> <br/>";
              echo "<p> {$TestoArticolo} </p> <br/>";
              echo "</tr>";
            }

          ?>

        </div>
            
        <div class="sputa_categoria">

          <?php if (isset($_GET["categoria"])) { ?>
            <div class="contenitori" >
              <?php 
                $query_articolicategoria = mysqli_query($connessione, "SELECT Articoli.Titolo, Articoli.TESTO, Articoli.Data, Articoli.Categoria FROM Articoli WHERE Articoli.Categoria='".$_GET['categoria']."'"); 
                while($row = mysqli_fetch_array($query_articolicategoria)) {
                    //var_dump("SELECT Articoli.Titolo, Articoli.TESTO, Articoli.Data, Articoli.Categoria FROM Articoli WHERE Articoli.Categoria='Libri'"); ?> 
                <article>
                  <h3><?php echo $row["Titolo"];?></h3>
                  <p><?php echo $row["Data"];?></p>
                  <p><?php echo $row["TESTO"];?></p>
                </article>
              <?php } ?>
            </div> 
          <?php } ?>

        </div>    
         
      </div>
    </div>
  </div>

  <?php include "footer.php" ?>

</body>
</html>
