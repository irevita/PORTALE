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
<html lang="it">
<head>
  <title>Connessione al DB con PHP</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <?php include "header.php"?>
</head>

<body>

  <header>
      <a href="index.php"><h1>Portale Blog</h1></a>
      <span>
        <button name="registrazione" type="submit" class="btn btn-success" data-toggle ="modal" data-target="#regModal">Registrati</button>
        <button type="button" class="btn btn-success" data-toggle ="modal" data-target="#loginModal">Accedi</button>
      </span>
  </header>

  <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Entra nell'area riservata</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="login.php" method="post">
          <div class="form-group">
              <input name="username" type="text" class="form-control" placeholder="Inserisci il tuo username" aria-describedby="emailHelp">
          </div>
          <div class="form-group">
              <input name="password" type="password" class="form-control" placeholder="Inserisci la tua password">
          </div>
          <button name="login" type="submit" class="btn btn-success">Accedi</button>
        </form>
      </div>
    </div>
  </div>


  <div class="modal fade" id="regModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Compila i campi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="index.php" method="post">
          <div class="form-group">
              <input name="nome" type="text" class="form-control" placeholder="Nome">
              <input name="cognome" type="text" class="form-control" placeholder="Cognome">
              <input name="nazione" type="text" class="form-control" placeholder="Nazione">
              <input name="email" type="email" class="form-control" placeholder="Email Address">
              <input name="data" type="date" class="form-control" placeholder="Data di nascita">
              <input name="tel" type="tel" class="form-control" placeholder="Telefono">
              <input name="documento" type="text" class="form-control" placeholder="Documento">
              <input name="username" type="text" class="form-control" placeholder="Nickname">
              <input name="password" type="password" class="form-control" placeholder="Password">
          </div>
          <button name="submit" type="submit" class="btn btn-primary">Invia</button>
        </form>
      </div> 
    </div>
  </div>


  <div class="container horizontal">
    <!-- <div class="row"> -->
      <div id="sinistra">
        <br/>
        <h3>CATEGORIE</h3>
            <!-- sottocategoria -->
        <ul><?php while($row = mysqli_fetch_array($query_categorie)) { ?>
          <p> <?php echo '<a class="menu-ctg" href="index.php?categoria='.$row["Categoria"].'">'.$row["Categoria"]."</a>"; ?> </p><?php } ?>
        </ul>
      </div>


      <div id="destra">  
      
        <div id="articoli_tendenza" class="<?php if(isset($_GET["categoria"])){echo "hidden";}  ?>">
          
          <br />
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
              echo "<td> <h3> {$NomeArticolo} </h3> </td> ";
              echo "<p> {$TestoArticolo} </p> <br/>";
              echo "</tr>";
            }

          ?>

        </div>
            
        <div id="sputa_categoria">

          <?php if (isset($_GET["categoria"])) { ?>
            
            <!-- <div class="cosesopra"> -->
              <div class="annunciazio">
                <?php echo "<br/><h5>Stai cercando in:&nbsp</h5><h4> ".$_GET["categoria"]."</h4>";?>             
              </div>
              <!-- <div>
                <a href="index.php">Torna inditro</a>
              </div> -->
            <!-- </div> -->

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
    <!-- </div> -->
  </div>

  <?php include "footer.php" ?>

</body>
</html>
