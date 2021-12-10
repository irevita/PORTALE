<?php
  include "connessione.php";
  session_start();
  // QUERIES LIST
  $query_categorie = mysqli_query($connessione, "SELECT Categoria FROM Categoria");
  // $query_articoliseguiti 
  $query_articoliseguiti = mysqli_query($connessione, "SELECT Articoli.Titolo, Articoli.TESTO FROM Articoli, Segui WHERE Segui.ID_Utente = {$_SESSION['id']} && Segui.CodiceBlog=Articoli.Blog");
  // query login utente
  $query_mostraProfilo = mysqli_query($connessione, "SELECT Nick, Nazione, DatadiNascita, Email FROM Utenti WHERE ID_Utente = {$_SESSION['id']}" );
  // query i miei blog
  $query_mieiblog = mysqli_query($connessione, "SELECT Blog.NomeBlog, Blog.Descrizione, Blog.CodiceBlog FROM Blog WHERE Blog.Autore = {$_SESSION['id']}");
  // query i blog di cui sono coautore
  $query_coautore = mysqli_query($connessione,"SELECT Blog.CodiceBlog, Blog.NomeBlog FROM Blog, Coautore WHERE Blog.CodiceBlog = Coautore.CodiceBlog && Coautore.ID_Utente = {$_SESSION['id']}");
  // $query_blogseguiti 
  $query_blogseguiti = mysqli_query($connessione, "SELECT Blog.CodiceBlog, Blog.NomeBlog FROM Blog, Segui WHERE Segui.ID_Utente = {$_SESSION['id']} && Segui.CodiceBlog=Blog.CodiceBlog"); 
  // query_articoli tutti, problema di scoping per la get, vedere sotto
//   $query_articoli = mysqli_query($connessione, "SELECT Blog.NomeBlog, Articoli.Titolo, Articoli.TESTO, Articoli.Data, Articoli.Categoria FROM Articoli JOIN Blog ON Articoli.Blog = Blog.CodiceBlog WHERE Blog.CodiceBlog={$_GET['blog']} ");
  // query_articolicategoria, problema di scoping della get, vedere sotto
//   $query_articolicategoria = mysqli_query($connessione, "SELECT Articoli.Titolo, Articoli.TESTO, Articoli.Data, Articoli.Categoria FROM Articoli WHERE Articoli.Categoria= {$_GET['categoria']}" ); 
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

            <!-- CATEGORIE CLICCABILI -->
            <div class="list-item">
                <span>Categorie</span>
                <!-- sottocategoria -->
                <ul><?php while($row = mysqli_fetch_array($query_categorie)) { ?>
                    <li> <?php echo '<a href="area_riservata.php?categoria='.$row["Categoria"].'">'.$row["Categoria"]."</a>"; ?>
                    </li><?php } ?>
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

            <!-- I MIEI BLOG -->

            <div id="mieiblog">
                <h4>I miei blog: </h4>
                <ul>
                    <?php while($row = mysqli_fetch_array($query_mieiblog)) { ?>
                    <li class="blog">
                        <!-- assegna ad href il link col nome blog corrente -->
                        <?php echo '<a href="area_riservata.php?blog='.$row["CodiceBlog"].'">'.$row["NomeBlog"]."</a>"; ?>
                        <p><?php echo $row["Descrizione"];?></p>
                    </li>
                    <?php } ?>
                </ul>
            </div>

            <!-- BLOG DI CUI SONO COAUTORE -->
            <div id="blogcoautore">
                <h4>I blog di cui sono coautore </h4>
                <ul>
                    <?php while($row = mysqli_fetch_array($query_coautore)) { ?>
                    <li>
                        <?php echo '<a href="area_riservata.php?blog='.$row["CodiceBlog"].'">'.$row["NomeBlog"]."</a>"; ?>
                    </li>
                    <?php } ?>
                </ul>
            </div>


        </nav>


        <!-- HOMEPAGE -->

        <div id="homepage">
            <h3>HOME PAGE </h3>

            <!--pulsante aggiungi post -->
            <div id="nuovopost">
                <input type="text" name="post_txt" value="Scrivi nuovo post..">
                <button class="button">+</button>
            </div>



            <!-- CATEOGORIE -->

            <?php if (isset($_GET["categoria"])) { ?>
            <div class="contenitori">
                <?php 
                    $query_articolicategoria = mysqli_query($connessione, "SELECT Articoli.Titolo, Articoli.TESTO, Articoli.Data, Articoli.Categoria FROM Articoli WHERE Articoli.Categoria='".$_GET['categoria']."'"); 
                    while($row = mysqli_fetch_array($query_articolicategoria)) { ?>
                <article>
                    <h3><?php echo $row["Titolo"];?></h3>
                    <p><?php echo $row["Data"];?></p>
                    <p><?php echo $row["TESTO"];?></p>


                    <div>
                        <h4>Post : Your Comment</h4>
                    </div>
                    <div class="full comment_form">
                        <!-- <form action="index.html">    -->
                    </div>
                    <textarea placeholder="Comment"></textarea>
                    <button>Send</button>
                    </form>



                </article>
                <?php } ?>
            </div>
            <?php } ?>


            <?php if (isset($_GET["blog"])) { ?>
            <div class="contenitori">
                <?php 
                    $query_articoli = mysqli_query($connessione, "SELECT Blog.NomeBlog, Articoli.Titolo, Articoli.TESTO, Articoli.Data, Articoli.Categoria FROM Articoli JOIN Blog ON Articoli.Blog = Blog.CodiceBlog WHERE Blog.CodiceBlog='".$_GET['blog']."'");
                    while($row = mysqli_fetch_array($query_articoli)) {?>
                <article>
                    <h3><?php echo $row["Titolo"];?></h3>
                    <p><?php echo $row["Data"];?></p>
                    <p><?php echo $row["TESTO"];?></p>

                    <div>
                        <h4>Post : Your Comment</h4>
                    </div>
                    <div class="full comment_form">
                        <!-- <form action="index.html">    -->
                    </div>
                    <textarea placeholder="Comment"></textarea>
                    <button>Send</button>
                    </form>



                        <h5>Categoria:<p>
                                <?php echo '<a href="area_riservata.php?categoria='.$row["Categoria"].'">'.$row["Categoria"]."</a>"; ?>
                            </p>
                        </h5>
                </article>
                <?php } ?>
            </div>
            <?php } ?>



            <div id="blogseguiti" class="contenitori">
                <h3>I blog che seguo</h3>
                <ul>
                    <?php while($row = mysqli_fetch_array($query_blogseguiti)) { ?>
                    <li>
                        <?php echo '<a href="area_riservata.php?blog='.$row["CodiceBlog"].'">'.$row["NomeBlog"]."</a>"; ?>
                    </li>
                    <?php } ?>
                </ul>
            </div>


            <div id='articoli' class="contenitori">
                <?php  while($row = mysqli_fetch_array($query_articoliseguiti)) { ?>
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
                        <form action="elimina_account.php" method="post">
                            <button name="delete" type="submit" class="btn btn-secondary">Si,
                                elimina</button>
                            <button name="indietro" type="submit" class="btn btn-primary">Torna
                                indietro</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>




    </div>

    <?php include "footer.php" ?>


</body>

</html>