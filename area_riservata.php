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
  $query_blogseguiti = mysqli_query($connessione, "SELECT Blog.CodiceBlog, Blog.NomeBlog, Blog.Descrizione, Blog.Sfondo FROM Blog, Segui WHERE Segui.ID_Utente = {$_SESSION['id']} && Segui.CodiceBlog=Blog.CodiceBlog"); 
  // query_articoli tutti, problema di scoping per la get, vedere sotto
//   $query_articoli = mysqli_query($connessione, "SELECT Blog.NomeBlog, Articoli.Titolo, Articoli.TESTO, Articoli.Data, Articoli.Categoria FROM Articoli JOIN Blog ON Articoli.Blog = Blog.CodiceBlog WHERE Blog.CodiceBlog={$_GET['blog']} ");
  // query_articolicategoria, problema di scoping della get, vedere sotto
//   $query_articolicategoria = mysqli_query($connessione, "SELECT Articoli.Titolo, Articoli.TESTO, Articoli.Data, Articoli.Categoria FROM Articoli WHERE Articoli.Categoria= {$_GET['categoria']}" ); 
?>


<!DOCTYPE html>
<html lang="it">

<head>
    <title>Area riservata</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <?php include "header.php"?>
</head>


<body>
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
            <br />
            <h1>Homepage</h1>
            <h4>Ciao <?php echo $_SESSION['utente']; ?>, benvenutə nella tua area personale!</h4>    
        </div>

        <div id="right">
            <br/>
            <h3>Cosa ti interessa?</h3>
            <form method="post" action="area_riservata.php">
                <input type="text" name="cerca_utente" placeholder="Cerca utente" />
                <input type="submit" name="click_utente" value="CERCA"  /><br  />
                <input type="text" name="cerca_blog" placeholder="Cerca blog" />
                <input type="submit" name="click_blog" value="CERCA"  /><br  />
                <input type="text" name="cerca_articolo" placeholder="Cerca articolo" />
                <input type="submit" name="click_articolo" value="CERCA"  /><br  />
            </form>
        </div> 

        <!-- HOMEPAGE -->

        <div id="homepage">
            <h3> Articoli dei blog che segui... </h3>
            <div id="articoli" class="<?php if(isset($_GET["categoria"])) {echo "hidden";}; if(isset($_GET["blog"])) {echo "hidden";}; if(isset($_POST["blog_seguiti"])) {echo "hidden";};
            if(isset($_POST["click_utente"])){echo "hidden";}; if(isset($_POST["click_blog"])){echo "hidden";}; if(isset($_POST["click_articolo"])){echo "hidden";}; if(isset($_GET["profilo"])){echo "hidden";}?>">
                
                <?php  while($row = mysqli_fetch_array($query_articoliseguiti)) { ?>
                <div class="contenitori">
                    <h3><?php echo $row["Titolo"];?></h3>
                    <p><?php echo $row["TESTO"];?></p>
                    <span class="likes">
                        <button class="btn btn-success"><i class="fas fa-thumbs-up"></i> Like</button>
                        <!-- <span class="likes_number"></span> -->
                    </span>
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

            <div>
                <?php if(isset($_GET["profilo"])) {

                    echo "<br/><h3>".$_GET['profilo']."</h3>";
                    $query_mostraProfilo = mysqli_query($connessione, "SELECT Nick, Nazione, DatadiNascita, Email FROM Utenti WHERE Nick = '".$_GET['profilo']."'");
                    while($row = mysqli_fetch_array($query_mostraProfilo)){ ?>

                        <li> <i class='fa fa-home fa-fw w3-margin-right w3-text-theme'> </i> <?php echo $row["Nazione"];?> </li>
                        <li> <i class='fa fa-birthday-cake fa-fw w3-margin-right w3-text-theme'> </i> <?php echo $row["DatadiNascita"];?> </li>
                        <li> <i class='fa fa-paper-plane-o'> </i> <?php echo $row["Email"];?> </li>
                    <?php }?>

                    <br/>  
                    <h4>Blog di cui <?php echo"<b>".$_GET['profilo']."</b>" ?> è autore </h4>
                    <?php 
                        $query_blogprofilo = mysqli_query($connessione, "SELECT Blog.Sfondo, Blog.NomeBlog, Blog.Descrizione FROM Blog, Utenti WHERE Blog.Autore = Utenti.ID_Utente && Utenti.Nick='".$_GET['profilo']."'"); 
                        if(mysqli_num_rows($query_blogprofilo) > 0){
                        while($row = mysqli_fetch_array($query_blogprofilo)) { ?> 

                            <div class="contenitori">
                            <img src="<?php echo $row["Sfondo"];?>" alt="<?php echo $row["Sfondo"];?>">
                            <h3><?php echo $row["NomeBlog"];?></h3>
                            <p><?php echo $row["Descrizione"];?></p>
                            </div>
                        <?php } ?>  
                    <?php }else echo "<b>".$_GET['profilo']."</b> non è autore di blog" ?>

                    <br/>  
                    <h4>Blog di cui <?php echo"<b>".$_GET['profilo']."</b>" ?> è coautore </h4>
                    <?php 
                        $query_blogprofilocoautore = mysqli_query($connessione, "SELECT Blog.Sfondo, Blog.NomeBlog, Blog.Descrizione FROM Blog, Utenti, Coautore WHERE Coautore.CodiceBlog = Blog.CodiceBlog && Coautore.ID_Utente = Utenti.ID_Utente && Utenti.Nick='".$_GET['profilo']."'"); 
                        if(mysqli_num_rows($query_blogprofilocoautore) > 0){
                        while($row = mysqli_fetch_array($query_blogprofilocoautore)) { ?> 

                            <div class="contenitori">
                            <img src="<?php echo $row["Sfondo"];?>" alt="<?php echo $row["Sfondo"];?>">
                            <h3><?php echo $row["NomeBlog"];?></h3>
                            <p><?php echo $row["Descrizione"];?></p>
                            </div>
                        <?php } ?>
                        <?php }else echo "<b>".$_GET['profilo']."</b> non è coautore di blog" ?>    

                    <?php }?>
            </div>   

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


            <?php if(isset($_POST["blog_seguiti"])){ ?>
                <div>
                    <?php while($row = mysqli_fetch_array($query_blogseguiti)) { ?> 
                    <div class="contenitori">
                        <img src="<?php echo $row["Sfondo"];?>" alt="<?php echo $row["Sfondo"];?>">
                        <h3><?php echo $row["NomeBlog"];?></h3>
                        <p><?php echo $row["Descrizione"];?></p>
                        
                    </div>
                    <?php } ?>
                </div> 
            <?php } ?>

            <?php             
                if(isset($_POST['click_utente'])){
                    $search_utente = $_POST["cerca_utente"];
                    $sql_cerca_utente = mysqli_query($connessione, "SELECT Nick FROM Utenti WHERE Nick LIKE '%" . $search_utente . "%'");
                    if(mysqli_num_rows($sql_cerca_utente) > 0){

                        echo "<br/><h4>Risultati della tua ricerca</h4>";
                        echo "<p style='margin-left:25px;'>Trovate ". mysqli_num_rows($sql_cerca_utente)." voci per il termine <b>".stripslashes($search_utente)."</b></p>\n";

                        while($row = mysqli_fetch_array($sql_cerca_utente)) {

                            echo '<a href="area_riservata.php?profilo='.$row["Nick"].'">'.$row["Nick"]."</a>";
                        }
                        
                    }else{
                        echo "<br/>Al momento non sono stati trovati utenti con questo nome.";
                    }
                }
            ?>    

            <?php 
                if(isset($_POST['click_blog'])){
                    $search_blog = $_POST["cerca_blog"];
                    $sql_cerca_blog = mysqli_query($connessione, "SELECT NomeBlog, Descrizione, Sfondo FROM Blog WHERE NomeBlog LIKE '%" . $search_blog . "%'");
                    if(mysqli_num_rows($sql_cerca_blog) > 0){
                        echo "<br/><h4>Risultati della tua ricerca</h4>";
                        echo "<p style='margin-left:25px;'>Trovate ". mysqli_num_rows($sql_cerca_blog)." voci per il termine <b>".stripslashes($search_blog)."</b></p>\n";

                        while($row = mysqli_fetch_array($sql_cerca_blog)) { ?>

                            <div class="contenitori">
                                <img src="<?php echo $row["Sfondo"];?>" alt="<?php echo $row["Sfondo"];?>">
                                <h3><?php echo $row["NomeBlog"];?></h3>
                                <p><?php echo $row["Descrizione"];?></p>             
                            </div>   
                            
                        <?php }

                    }else{
                        echo "<br/>Al momento non sono stati trovati blog con questo nome.";
                    }
                }
            ?>    

            <?php 
                if(isset($_POST['click_articolo'])){
                    $search_articolo = $_POST["cerca_articolo"];
                    $sql_cerca_articolo = mysqli_query($connessione, "SELECT Articoli.Titolo, Articoli.TESTO, Articoli.Data, Blog.NomeBlog, Multimedia.Nome FROM Blog JOIN Articoli ON Articoli.Blog = Blog.CodiceBlog LEFT JOIN Multimedia ON Multimedia.CodiceArt = Articoli.CodiceArt WHERE Articoli.Titolo LIKE '%".$search_articolo."%' GROUP BY Articoli.CodiceArt" );
                    if(mysqli_num_rows($sql_cerca_articolo) > 0){
                        echo "<br/><h4>Risultati della tua ricerca</h4>";
                        echo "<p style='margin-left:25px;'>Trovate ". mysqli_num_rows($sql_cerca_articolo)." voci per il termine <b>".stripslashes($search_articolo)."</b></p>\n";

                        while($row = mysqli_fetch_array($sql_cerca_articolo)) { ?>
                            
                            <div class="contenitori">
                                <article>
                                    <img src="<?php echo $row["Nome"];?>" alt="<?php echo $row["Nome"];?>">  
                                    <h3><?php echo $row["Titolo"];?></h3>
                                    <p><?php echo $row["Data"];?></p>
                                    <p><?php echo $row["TESTO"];?></p>
                                </article>
                                <div class="info_blog">
                                    <h4>Blog: &nbsp</h4><a href='"<?php echo $row["NomeBlog"] ?>".".php"'><?php echo $row["NomeBlog"] ?></a>
                                </div>
                            </div>
                            
                        <?php }

                    }else{
                        echo "<br/>Al momento non sono stati trovati articoli con questo nome.";
                    }
                }    
            ?>

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

    </div >
    
    
     <!-- menu principale -->
    <nav id="menu" class="unvisible">

        <!-- CATEGORIE CLICCABILI -->
        <div class="list-item">
            <button class="menu-btn">Categorie</button>
            <!-- sottocategoria -->
            <ul><?php while($row = mysqli_fetch_array($query_categorie)) { ?>
                <h6> <?php echo '<a href="area_riservata.php?categoria='.$row["Categoria"].'">'.$row["Categoria"]."</a>"; ?>
                </h6><?php } ?>
            </ul>
        </div>

        <!-- TEMA -->
        <div class="list-item">
            <form action="area_riservata.php" method="post">
                <button name="blog_seguiti" class="menu-btn">Blog seguiti</button>
            </form>
            <!-- sottocategoria NON FUNZIONA-->
        </div>

            <!-- I MIEI BLOG -->
        <div class="list-item" id="mieiblog">
            <button class="menu-btn">I miei blog</button>
            <ul>
                <h6><a href="blog.php">+ CREA NUOVO</a></h6>
                <?php while($row = mysqli_fetch_array($query_mieiblog)) { ?>
                
                <h6 class="blog">
                    <!-- assegna ad href il link col nome blog corrente -->
                    <?php echo '<a href="area_riservata.php?blog='.$row["CodiceBlog"].'">'.$row["NomeBlog"]."</a>"; ?>
                </h6>
                <?php } ?>
            </ul>
        </div>

        <!-- BLOG DI CUI SONO COAUTORE -->
        <div class="list-item" id="blogcoautore">
            <button class="menu-btn">I blog di cui sono coautore</button>
            <ul>
                <?php while($row = mysqli_fetch_array($query_coautore)) { ?>
                <h6>
                    <?php echo '<a href="area_riservata.php?blog='.$row["CodiceBlog"].'">'.$row["NomeBlog"]."</a>"; ?>
                </h6>
                <?php } ?>
            </ul>
        </div>


        <br class="line">
        
        <!-- IMPOSTAZIONI PROFILO -->
        <div class="list-item">
            <button class="menu-btn">Impostazioni profilo</button>
            <!-- sottocategoria -->
            <button name="eliminazione" type="submit" class="btn btn-danger sub-menu" data-toggle="modal" data-target="#deleteModal" id="mexdel">
                <!-- <span class="material-icons">delete</span>  -->
                Elimina account
            </button>
        </div>

    
    </nav>

    <?php include "footer.php" ?>


</body>

</html>             