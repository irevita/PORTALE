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
  //QUERY SCEGLI TEMA
  $query_tema = mysqli_query($connessione, "SELECT Tema FROM Blog WHERE Autore = {$_SESSION['id']}");
  // query i blog di cui sono coautore
  $query_coautore = mysqli_query($connessione,"SELECT Blog.CodiceBlog, Blog.NomeBlog FROM Blog, Coautore WHERE Blog.CodiceBlog = Coautore.CodiceBlog && Coautore.ID_Utente = {$_SESSION['id']}");
  // $query_blogseguiti 
  $query_blogseguiti = mysqli_query($connessione, "SELECT Blog.CodiceBlog, Blog.NomeBlog FROM Blog, Segui WHERE Segui.ID_Utente = {$_SESSION['id']} && Segui.CodiceBlog=Blog.CodiceBlog"); 
  // query_articoli tutti, problema di scoping per la get, vedere sotto
//   $query_articoli = mysqli_query($connessione, "SELECT Blog.NomeBlog, Articoli.Titolo, Articoli.TESTO, Articoli.Data, Articoli.Categoria FROM Articoli JOIN Blog ON Articoli.Blog = Blog.CodiceBlog WHERE Blog.CodiceBlog={$_GET['blog']} ");
  // query_articolicategoria, problema di scoping della get, vedere sotto
//   $query_articolicategoria = mysqli_query($connessione, "SELECT Articoli.Titolo, Articoli.TESTO, Articoli.Data, Articoli.Categoria FROM Articoli WHERE Articoli.Categoria= {$_GET['categoria']}" ); 
?>

<?php 


if(isset($_POST["add_post"])){

    $newpost = $_POST["post_txt"];

    if(!empty($newpost)){

        $querypost = "INSERT INTO Articoli(TESTO, AutoreArt) VALUES ('{$newpost}', '{$_SESSION['id']}')";

        $creaPost = mysqli_query($connessione, $querypost);
    
        if(!$creaPost){
        die('Query fallita'.mysqli_error($connessione));
        echo "query fallita";
        }else{
            echo 'nuovo post aggiunto';
        }

    }
}

    ?>


<!DOCTYPE html>
<html lang="it">

<head>
    <title>Connessione al DB con PHP</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <?php include "header.php"?>
</head>


<body class="theme-<?php //$theme = mysqli_fetch_field($query_tema); echo $theme; ?>">
    <header>
        <span onclick="toggleMenu()">
            <h1 class="pointer">&#9776; PORTALE</h1>
        </span>
        <a href="logout.php">
            <button type="button" class="btn btn-danger">Logout</button>
        </a>
    </header>

    <div class="container-flex">

        <div class="title">
            <h1>Area riservata</h1>
            <h4>Ciao <?php echo $_SESSION['utente']; ?>, benvenut nella tua area personale!</h4>
        </div>



        <!-- HOMEPAGE -->

        <div id="homepage">
            <h3>HOME PAGE </h3>




            <!--pulsante aggiungi post -->
            <div id="nuovopost">
                <input type="text" name="post_txt" value="Scrivi nuovo post..">
                <button name="add_post" class="button">+</button>
            </div>






            <!-- CATEOGORIE -->

            <?php if (isset($_GET["categoria"])) { ?>
                <div class="contenitori" >
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
                            <textarea placeholder="Comment"></textarea>
                            <button>Send</button>
                             <!-- </form> -->
                        </div>

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
                            <textarea  placeholder="Comment"></textarea>
                            <button>Send</button>
                             <!-- </form> -->
                        </div>

                        <h5>Categoria:<p><?php echo '<a href="area_riservata.php?categoria='.$row["Categoria"].'">'.$row["Categoria"]."</a>"; ?></p></h5>
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
                <div class="sub-menu flex row center justify">
                    <input id="colorpicker" type="color" value="#ffffff">
                    <button id="color" class="btn-primary">Change</button>
                </div>
            </div>


            <!-- IMPOSTAZIONI PROFILO -->
            <div class="list-item">
                <span>Impostazioni profilo</span>
                <!-- sottocategoria -->
                <button name="eliminazione" type="submit" class="btn btn-danger sub-menu" data-toggle="modal" data-target="#deleteModal">
                    <span class="material-icons">delete</span> 
                    Elimina account
                </button>
            </div>

            <br class="line">
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

</body>

</html>
                         
                  