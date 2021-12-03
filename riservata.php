<?php
  include "connessione.php";
  session_start();

  // QUERIES LIST
  $query_categorie = mysqli_query($connessione, "SELECT Categoria FROM Categoria");
  // $query_articoliseguiti 
  $query_articoliseguiti = mysqli_query($connessione, "SELECT Articoli.Titolo, Articoli.TESTO FROM Articoli, Segui WHERE Segui.ID_Utente = {$_SESSION['id']} && Segui.CodiceBlog=Articoli.Blog");
  // query elimina utente
   $query_eliminautente = mysqli_query($connessione, "DELETE FROM Utenti WHERE ID_Utente=".$_SESSION['id']); 
  // query login utente
  $query_mostraProfilo = mysqli_query($connessione, "SELECT Nick, Nazione, DatadiNascita, Email FROM Utenti WHERE ID_Utente = {$_SESSION['id']}" );
  
?>


<?php // bottone elimina php
    if (isset($_POST['delete'])) {
        if ($query_eliminautente) {    
            //eliminazione riuscita 
            header('Location: index.php?action=eliminautente');
        }else {
            // eliminazione non riuscita 
            // $error= mysqli_error($connessione);
            header('Location: area_riservata.php?action=erroreliminaut');
        }
    }
    if (isset($_POST['indietro'])) {
        header('Location: area_riservata.php');
    }
 ?>


<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connessione al DB con PHP</title>
    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <!-- è commentato per il bottone elimina -->
    <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" defer></script> -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" defer></script>
    <script src="js/script.js" defer></script>
    <!-- CSS -->
    <!-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="style.css">
    <!-- <link href="reg2.css" rel="stylesheet"/> -->


</head>

<body>

    <div class="container">

        <header>
            <span class="h3" class="navbar-brand mb-0" onclick="toggleMenu()"><i class="fas fa-bars"></i> PORTALE
            </span>
            <a href="logout.php">
                <button type="button" class="btn btn-danger">Logout</button>
            </a>
        </header>


        <div class="title">
            <h1>Area riservata</h1>
            <h3>Ciao <?php echo $_SESSION['utente']; ?>, benvenut nella tua area personale!</h3>
        </div>


        <!-- menu principale -->
        <nav id="menu" class="unvisible">

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
                    <li>
                        <input id="colorpicker" type="color" value="#ffffff">
                        <button id="color"> change Color </button>
                    </li>
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
                    <li>
                        <span>
                            <button name="eliminazione" type="submit" class="btn btn-danger" data-toggle="modal"
                                data-target="#deleteModal"><i class="fas fa-trash"></i> Elimina account</button>
                        </span>
                    </li>
                </ul>
            </div>
        </nav>


        <!-- articoli -->

        <div id="homepage">
            <h3>HOME PAGE - gli articoli del Blog che segui - </h3>
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
        </div>

        <!-- modale elimina account -->

        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Eliminazione account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Sei sicuro di voler eliminare il tuo account? </br> Se lo farai, eliminerai il
                            tuo blog e tutto il suo contenuto!</p>
                    </div>
                    <div class="modal-footer">
                        <form action="area_riservata.php" method="post">
                            <button name="delete" type="submit" class="btn btn-secondary">Si,
                                elimina</button>
                            <button name="indietro" type="submit" class="btn btn-primary">Torna
                                indietro</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!--pulsante aggiungi post -->
        <div>
            <input type="text" name="post_txt" value="Scrivi il tuo post..">
        </div>

    </div>

    <?php include "footer.php" ?>


</body>

</html>