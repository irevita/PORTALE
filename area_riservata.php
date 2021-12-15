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
  $query_blogseguiti = mysqli_query($connessione, "SELECT Blog.CodiceBlog, Blog.NomeBlog, Blog.Descrizione FROM Blog, Segui WHERE Segui.ID_Utente = {$_SESSION['id']} && Segui.CodiceBlog=Blog.CodiceBlog"); 
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
    <title>Area riservata</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <?php include "header.php"?>
</head>


<body class="theme-<?php //$theme = mysqli_fetch_field($query_tema); echo $theme; ?>">
    <header>
        <span onclick="toggleMenu()">
            <h1 class="pointer" id="pbmenu">&#9776;</h1>
            <a href="area_riservata.php" id="pb"><h1 class="pointer">Portale Blog</h1></a>
            <!-- <h1 class="pointer"> PORTALE</h1> -->
        </span>
        <a href="logout.php">
            <button type="button" class="btn btn-danger">Logout</button>
        </a>
    </header>

    <div class="container-flex">

        <div class="title">
            <h1>Homepage</h1>
            <h4>Ciao <?php echo $_SESSION['utente']; ?>, benvenutə nella tua area personale!<br />Questi sono i blog che segui.</h4>
            
        </div>



        <!-- HOMEPAGE -->

        <div id="homepage">

            <!-- HO COMMENTATO AL MOMENTO QUESTA PARTE SOLO PERCHÈ VA SPOSTATA -->
            
            <!-- <h3>HOME PAGE </h3>

            <div id="nuovopost">
                <form action="area_riservata.php" method="post">
                    <input type="text" name="titolo_txt" placeholder="Scrivi titolo">
                    <button name ="add_titolo" type= "submit" class="button">+</button>
                </form>
            </div> -->






            <!-- CATEOGORIE -->

            <?php if (isset($_GET["categoria"])) { ?>
                <div>
                    <?php 
                    $query_articolicategoria = mysqli_query($connessione, "SELECT Articoli.Titolo, Articoli.TESTO, Articoli.Data, Articoli.Categoria FROM Articoli WHERE Articoli.Categoria='".$_GET['categoria']."'"); 
                    while($row = mysqli_fetch_array($query_articolicategoria)) { ?> 
                    <div class="contenitori">
                        <h3><?php echo $row["Titolo"];?></h3>
                        <p><?php echo $row["Data"];?></p>
                        <p><?php echo $row["TESTO"];?></p>
                        
                        <div class="full comment_form">
                            <h4>Post your comment</h4>
                            <!-- <form action="index.html">    -->
                            <input placeholder="Comment"></input>
                            <button type="button" class="btn btn-success">Send</button>
                            <!-- </form> -->
                        </div>

                    </div>
                    <?php } ?>
                </div> 
            <?php } ?>


            <?php if (isset($_GET["blog"])) { ?>
                <div>
                    <?php 
                    $query_articoli = mysqli_query($connessione, "SELECT Blog.NomeBlog, Articoli.Titolo, Articoli.TESTO, Articoli.Data, Articoli.Categoria FROM Articoli JOIN Blog ON Articoli.Blog = Blog.CodiceBlog WHERE Blog.CodiceBlog='".$_GET['blog']."'");
                    while($row = mysqli_fetch_array($query_articoli)) {?> 
                    <div class="contenitori">
                        <h3><?php echo $row["Titolo"];?></h3>
                        <p><?php echo $row["Data"];?></p>
                        <p><?php echo $row["TESTO"];?></p>
                        
                            
                        <div class="full comment_form">
                            <h4>Post your comment</h4>
                            <!-- <form action="index.html">    -->
                            <input  placeholder="Comment"></input>
                            <button type="button" class="btn btn-success">Send</button>
                            <!-- </form> -->
                        </div>
                        <div class="vedi-ctg">
                            <h5>Categoria:<p><?php echo '<a href="area_riservata.php?categoria='.$row["Categoria"].'">'.$row["Categoria"]."</a>"; ?></p></h5>
                        </div>
                    </div>
                    <?php } ?>
                </div> 
            <?php } ?>
            



            <div id="blogseguiti" class="<?php if(isset($_GET["categoria"])) {echo "hidden";}; if(isset($_GET["blog"])) {echo "hidden";}  ?>">
                <!-- <h3>I blog che seguo</h3> -->
                <?php while($row = mysqli_fetch_array($query_blogseguiti)) { ?>
                <div class="contenitori">    
                    <h6>
                        <?php echo '<a href="area_riservata.php?blog='.$row["CodiceBlog"].'">'.$row["NomeBlog"]."</a>"; ?>
                    </h6>
                    <p><?php echo $row["Descrizione"];?></p>
                </div>
                <?php } ?>
            </div>

            
            <!-- QUESTA L'HO COMMENTATA PERCHÈ NON VIENE USATA DA NESSUNA PARTE MA POTREBBE SERVIRE -->

            <!-- <div id='articoli'>
                <?php  //while($row = mysqli_fetch_array($query_articoliseguiti)) { ?>
                <div class="contenitori">
                    <h3><?php //echo $row["Titolo"];?></h3>
                    <p><?php //echo $row["TESTO"];?></p>
                    <span class="likes">
                        <i class="fas fa-thumbs-up"></i> Like
                        <span class="likes_number"></span>
                    </span>
                </div>
                <?php //} ?>
            </div> -->
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
    
    
     <!-- menu principale -->
        <nav id="menu" class="unvisible">

        <!-- CATEGORIE CLICCABILI -->
        <div class="list-item">
            <span><h5>Categorie</h5></span>
            <!-- sottocategoria -->
            <ul><?php while($row = mysqli_fetch_array($query_categorie)) { ?>
                <h6> <?php echo '<a href="area_riservata.php?categoria='.$row["Categoria"].'">'.$row["Categoria"]."</a>"; ?>
                </h6><?php } ?>
            </ul>
        </div>

        <!-- TEMA -->
        <div class="list-item">
            <span><h5>Tema</h5></span>
            <!-- sottocategoria -->
            <div class="sub-menu flex row center justify">
                <input id="colorpicker" type="color" value="#ffffff">
                <button id="color" class="btn-primary">Change</button>
            </div>


        <!-- IMPOSTAZIONI PROFILO -->
        <div class="list-item">
            <span><h5>Impostazioni profilo</h5></span>
            <!-- sottocategoria -->
            <button name="eliminazione" type="submit" class="btn btn-danger sub-menu" data-toggle="modal" data-target="#deleteModal" id="mexdel">
                <!-- <span class="material-icons">delete</span>  -->
                Elimina account
            </button>
        </div>

            <br class="line">
            <!-- I MIEI BLOG -->

        <div class="list-item" id="mieiblog">
            <h5>I miei blog </h5>
            <ul>
                <?php while($row = mysqli_fetch_array($query_mieiblog)) { ?>
                <h6><a href="blog.php">+ CREA NUOVO</a></h6>
                <h6 class="blog">
                    <!-- assegna ad href il link col nome blog corrente -->
                    <?php echo '<a href="area_riservata.php?blog='.$row["CodiceBlog"].'">'.$row["NomeBlog"]."</a>"; ?>
                </h6>
                <?php } ?>
            </ul>
        </div>

        <!-- BLOG DI CUI SONO COAUTORE -->
        <div class="list-item" id="blogcoautore">
            <h5>I blog di cui sono coautore </h5>
            <ul>
                <?php while($row = mysqli_fetch_array($query_coautore)) { ?>
                <h6>
                    <?php echo '<a href="area_riservata.php?blog='.$row["CodiceBlog"].'">'.$row["NomeBlog"]."</a>"; ?>
                </h6>
                <?php } ?>
            </ul>
        </div>

        </nav>

    <?php include "footer.php" ?>


</body>

</html>
                         
                  