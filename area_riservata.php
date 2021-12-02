<?php
  include "connessione.php";
  session_start();

  // QUERIES LIST
  $query_categorie = mysqli_query($connessione, "SELECT Categoria FROM Categoria");
  // $query_articoliseguiti = "SELECT Articoli.Titolo, Articoli.TESTO FROM Articoli, Segui WHERE Segui.ID_Utente = {$_SESSION['id']} && Segui.CodiceBlog=Articoli.Blog";
  $query_articoliseguiti = mysqli_query($connessione, "SELECT Articoli.Titolo, Articoli.TESTO FROM Articoli, Segui WHERE Segui.ID_Utente = {$_SESSION['id']} && Segui.CodiceBlog=Articoli.Blog");
  
?>

<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Connessione al DB con PHP</title>
  <!-- JS -->
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" defer></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" defer></script>
  <!-- CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
    integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="style.css">

  <!--menu -->
  <style>
    .hidden {
      display: none !important;
    }

    .sidenav {
      height: 100%;
      width: 0;
      position: fixed;
      z-index: 1;
      top: 0;
      left: 0;
      background-color: #111;
      overflow-x: hidden;
      transition: 0.5s;
      padding-top: 60px;
      --
    }

    .sidenav a {
      padding: 8px 8px 8px 32px;
      text-decoration: none;
      font-size: 25px;
      color: #818181;
      display: block;
      transition: 0.3s;
    }

    .sidenav a:hover {
      color: #f1f1f1;
    }

    .sidenav .closebtn {
      position: absolute;
      top: 0;
      right: 25px;
      font-size: 36px;
      margin-left: 50px;
    }

    #main {
      transition: margin-left .5s;
      padding: 16px;
    }

    @media screen and (max-height: 450px) {
      .sidenav {
        padding-top: 15px;
      }

      .sidenav a {
        font-size: 18px;
      }
    }

    .button {
      background-color: #4CAF50;
      /* Green */
      border: none;
      color: white;
      padding: 15px 32px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin: 4px 2px;
      cursor: pointer;
    }
  </style>

  <link href="reg2.css" rel="stylesheet" />

</head>

<body>

  <div class="container">
    <nav class="navbar navbar-dark bg-dark">
      <span class="h3" class="navbar-brand mb-0">Esci dalla tua area personale :'(</span>
      <span>
        <a href="logout.php">
          <button type="button" class="btn btn-danger">
            Logout
          </button></a>
      </span>
    </nav>
  </div>

  <br>

  <h1>Area riservata</h1>
  <h3>Ciao <?php echo $_SESSION['utente']; ?>, benvenut nella tua area personale!</h3>

  <br>

  <div class="w3-col m3">

    <h4 class="w3-marginLeft">Il mio profilo</h4>
    <p class="w3-marginLeft"><img src="iconaut.png" class="w3-circle" style="height:106px;width:106px" alt="Avatar"></p>
    <?php
          $query = "SELECT Nick, Nazione, DatadiNascita, Email FROM Utenti WHERE ID_Utente={$_SESSION['id']}";
          $mostraProfilo = mysqli_query($connessione, $query);
          while($row = mysqli_fetch_array($mostraProfilo))
            {
          	$Nick = $row["Nick"];
            $Nazione = $row["Nazione"];
            $Compleanno = $row["DatadiNascita"];
            $Email = $row["Email"];
            echo "<p> <i class='fa fa-angellist'> </i> $Nick </p>";
            echo "<p> <i class='fa fa-home fa-fw w3-margin-right w3-text-theme'> </i> $Nazione </p>";
            echo "<p> <i class='fa fa-birthday-cake fa-fw w3-margin-right w3-text-theme'> </i> $Compleanno </p>";
            echo "<p> <i class='fa fa-paper-plane-o'> </i> $Email </p>";
            }
          ?>
  </div>

  <!--pulsante aggiungi post -->

  <div class="w3-center">
    <button class="w3-button w3-xlarge w3-circle w3-teal">+</button>
    <input type="text" name="post_txt" value="Sceiv il tuo post..">
    </br>

    <!-- menu principale -->
    <nav class="">

      <!-- CATEGORIA -->
      <div class="list-item">
        <span>Categorie</span>
        <!-- sottocategoria -->
        <ul><?php while($row = mysqli_fetch_array($query_categorie)) { ?>
          <li><?php echo $row["Categoria"]; ?></li><?php }?>
        </ul>
      </div>

      <!-- TEMA -->
      <div class="list-item">
        <span>Tema</span>
        <!-- sottocategoria -->
        <ul>
          <li></li>
        </ul>
      </div>

      <!-- BLOG SEGUITI -->
      <div class="list-item">
        <span>Blog seguiti</span>
        <!-- sottocategoria -->
        <ul>
          <li></li>
        </ul>
      </div>

      <!-- IMPOSTAZIONI PROFILO -->
      <div class="list-item">
        <span>Impostazioni profilo</span>
        <!-- sottocategoria -->
        <ul>
          <li></li>
        </ul>
      </div>
    </nav>


    <ul>
      <li>
        <a href="#">Categorie</a>
        <ul>
          <li>
            <?php
            $mostraCategorie = mysqli_query($connessione, "SELECT Categoria FROM Categoria");
            while($row = mysqli_fetch_array($mostraCategorie))
            {
            	$NomeCategoria = $row["Categoria"];
              echo "<button name='{$NomeCategoria}' type='button' class='btn btn-block categorie'>{$NomeCategoria}</button>";
            }
            ?>
          </li>
        </ul>
      </li>

      <li> <a href="#">Tema</a> </li>
      <li> <a href="#">Blog che segui</a> </li>
      <li>
        <a href="#">Impostazioni profilo</a>
        </li>



        <h3>HOME PAGE - gli articoli del Blog che segui - </h3>
        <div class="w3-container w3-card w3-white w3-round w3-margin"></div>

        <div id='articoli'>
          <?php while($row = mysqli_fetch_array($query_articoliseguiti)) { ?>
          <article>
            <h3><?php echo $row["Titolo"];?></h3>
            <p><?php echo $row["TESTO"];?></p>
            <span class="likes">
              <i class="fas fa-thumbs-up"></i> Like
              <span class="likes_number"></span>
            </span>
          </article>
          <?php } ?>
        </div>

        <!-- bottone elimina utente -->
        
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
        echo "Error deleting record: " . mysqli_error($connessione);
        header('Location: area_riservata.php');
    }
  }

  if (isset($_POST['indietro'])) {

    header('Location: area_riservata.php');
  }

 ?>

  <?php include "footer.php" ?>


</body>

</html>