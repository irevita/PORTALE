<?php 
 session_start(); 
 include 'connessione.php';
 $is_logged = isset($_SESSION["id"]);

 $queryUtente = mysqli_query($connessione,"SELECT Utenti.Nick FROM Utenti, Blog WHERE Blog.Autore = Utenti.ID_Utente && Blog.CodiceBlog = {$_GET['blog']} ");

 $queryBlogUtente = mysqli_query($connessione,"SELECT Blog.CodiceBlog, Blog.NomeBlog FROM Utenti, Blog WHERE Blog.Autore = Utenti.ID_Utente && Utenti.ID_Utente = (SELECT Blog.Autore FROM Blog WHERE Blog.CodiceBlog = {$_GET['blog']} )");

 $queryUtenteCoautore = mysqli_query($connessione,"SELECT Blog.CodiceBlog, Blog.NomeBlog FROM Blog, Coautore WHERE Coautore.CodiceBlog = Blog.CodiceBlog && Coautore.ID_Utente = (SELECT Blog.Autore FROM Blog WHERE Blog.CodiceBlog = {$_GET['blog']} )");

 $query_mostraBlog = mysqli_query($connessione,"SELECT NomeBlog, Sfondo, Descrizione FROM Blog WHERE Blog.CodiceBlog = {$_GET['blog']}");

 $query_articoliBlog = mysqli_query($connessione, "SELECT Articoli.CodiceArt, Articoli.Titolo, Articoli.TESTO FROM Articoli WHERE Articoli.Blog = {$_GET['blog']}");
 $query_immagini = mysqli_query($connessione, "SELECT Articoli.CodiceArt, Articoli.Titolo, Articoli.TESTO, Multimedia.Nome FROM Articoli LEFT JOIN Multimedia ON Multimedia.CodiceArt = Articoli.CodiceArt WHERE Articoli.Blog = {$_GET['blog']}");
 
//  $logged = false;
//   if(isset($_SESSION["id"])){
//       $logged = true;
//   }
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <title>Portale Blog</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <?php include "header.php"?>
</head>


<body>

    <header>
        <span onclick="toggleMenu()">
            <h1 class="pointer" id="pbmenu">&#9776;</h1>
            <a href="visualizzablog.php?blog=<?php echo $_GET['blog'];?>" id="pb">
                <h1 class="pointer">Portale Blog</h1>
            </a>
            <!-- <h1 class="pointer"> PORTALE</h1> -->
        </span>

        <?php  
            // if(isset($_POST['torna_indietro'])){
                if($is_logged){
           
                echo '<a href="area_riservata.php">';
                echo '<button type="button" class="btn btn-danger">Torna indietro</button> </a>';
                // header ("Location: area_riservata.php");

                } else {
                
                echo '<a href="index.php">';
                echo '<button type="button" class="btn btn-danger">Torna indietro</button> </a>';
                // header ("Location: index.php");

                }
            // }
        ?> 
      
    </header>

    <nav id="menu" class="unvisible">

        <!-- PROFILO UTENTE -->
        <div class="list-item">
            <form action="visualizzablog.php?blog=<?php echo $_GET['blog'];?>" method="post">
                <button name="vedi_profilo" class="menu-btn">Profilo di <?php while($row = mysqli_fetch_array($queryUtente)){echo $row["Nick"];}?></button>
            </form>
        </div>

        <!-- I SUOI BLOG -->
        <div class="list-item">
            <button class="menu-btn">Tutti i suoi blog</button>
            <ul>
                <?php while($row = mysqli_fetch_array($queryBlogUtente)) {; ?>
                
                <h6 class="blog">
                    <?php echo '<a href="visualizzablog.php?blog='.$row["CodiceBlog"].'">'.$row["NomeBlog"]."</a>"; ?>
                </h6>
                <?php } ?>
            </ul>
        </div>

        <!-- BLOG DI CUI E' COAUTORE -->
        <div class="list-item">
            <button class="menu-btn">Blog di cui <?php while($row = mysqli_fetch_array($queryUtente)){echo $row["Nick"];}?> è coautore</button>
            <ul>
                <?php while($row = mysqli_fetch_array($queryUtenteCoautore)) {;  ?>
                <h6>
                    <?php echo '<a href="visualizzablog.php?blog='.$row["CodiceBlog"].'">'.$row["NomeBlog"]."</a>"; ?>
                </h6>
                <?php } ?>
            </ul>
        </div>
    
    </nav>

    <?php while($row = mysqli_fetch_array($queryUtente)){echo $row["Nick"];}?>

    <div id="homepage_blog">
        <?php while($row = mysqli_fetch_array($query_mostraBlog)) { ?> 
        
            <!-- <div class="sfondo_blog">
                <img src="<?php //echo $row["Sfondo"];?>" alt="<?php //echo $row["Sfondo"];?>">  
            </div> -->
            <div class="nome_blog">
            
                
                <img src="<?php echo $row["Sfondo"];?>" alt="<?php echo $row["Sfondo"];?>">  
                <h3 class="centered"><b><?php echo $row["NomeBlog"];?></b></h3> 
                
            </div>

            <div class="navbar">
                <div class="naviga">
                    <h3 id="fai">Naviga nel blog...</h3>
                </div>
                <form action="visualizzablog.php?blog=<?php echo $_GET['blog'];?>" method="post" id="formadestra"> 
                    <button class="btn_menuVB" title="Home" href="visualizzablog.php?blog=<?php echo $_GET['blog'];?>">Articoli</button>
                    <button class="btn_menuVB" name="infoblog">Info Blog</button>
                    
                    <div id="cercanellarticolo">
                        <input type="text" name="cerca_articolo" placeholder="Cerca articolo" id="inputasinistra"/>  
                        <button class="btn_menuVB" type="submit" name="click_articolo">CERCA</button>
                    </div>  
                </form>
            </div>       			

        <?php } ?>
        
        <div id = "articoliBlog" class="<?php if(isset($_POST["infoblog"])) {echo "hidden";}; if(isset($_POST['click_articolo'])) {echo "hidden";};?>">
            <?php while($row = mysqli_fetch_array($query_articoliBlog)) { ?> 
                <div class="contenitori">
                    <article>
                        <h3><?php echo $row["Titolo"];?></h3>
                        <p><?php echo $row["TESTO"];?></p>
                    </article>
                </div>    
            <?php } ?>
        </div>   

        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php while($row = mysqli_fetch_array($query_immagini)) { ?>
                <div class="carousel-item">
                    <img class="d-block w-100" src="<?php $prev = $row["Nome"].''; echo $row["Nome"]; ?>" alt="First slide">
                </div>
                <?php } ?>
                <div class="carousel-item active">
                    <img class="d-block w-100" src="<?php echo ($prev); ?>" alt="First slide">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>

        <?php 
            if (isset($_POST['infoblog'])){
                $query_mostraBlog = mysqli_query($connessione,"SELECT NomeBlog, Sfondo, Descrizione FROM Blog WHERE Blog.CodiceBlog = {$_GET['blog']}");
                while($row = mysqli_fetch_array($query_mostraBlog)) { ?>
                <div class = "descrizione">
                    <h3>Descrizione</h3>
                    <p><?php echo $row["Descrizione"];?></p>
                </div>
            <?php } ?>
        <?php } ?>

        <?php 
            if(isset($_POST['click_articolo'])){
                // LEFT JOIN Multimedia ON Multimedia.CodiceArt = Articoli.CodiceArt     , Multimedia.Nome       && Articoli.Titolo LIKE '%".$search_articolo."%' GROUP BY Articoli.CodiceArt
                $search_articolo = $_POST["cerca_articolo"];
                $sql_cerca_articolo = mysqli_query($connessione, "SELECT Articoli.Titolo, Articoli.TESTO, Articoli.Data, Blog.NomeBlog, Multimedia.Nome, Blog.CodiceBlog FROM Blog JOIN Articoli ON Articoli.Blog = Blog.CodiceBlog LEFT JOIN Multimedia ON Multimedia.CodiceArt = Articoli.CodiceArt WHERE Blog.CodiceBlog = {$_GET['blog']} &&  Articoli.Titolo LIKE '%".$search_articolo."%' GROUP BY Articoli.CodiceArt" );
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
                        </div>
                        
                    <?php }

                }else{
                    echo "<br/>Al momento non sono stati trovati articoli con questo nome.";
                }
            }    
        ?>
        
        <?php 
            if (isset($_POST['vedi_profilo'])){
                $query_mostraUtente = mysqli_query($connessione, "SELECT Nick, Nome, Cognome, Nazione, DatadiNascita, Email FROM Utenti, Blog WHERE Blog.Autore=Utenti.ID_Utente && Blog.CodiceBlog={$_GET['blog']} ");
                while($row = mysqli_fetch_array($query_mostraUtente)) { ?> 

                    <div class="contenitori">

                        <h3><?php echo $row["Nick"];?></h3>
                        <h3><?php echo $row["Nome"];?></h3>
                        <h3><?php echo $row["Cognome"];?></h3>
                        <h3><?php echo $row["Nazione"];?></h3>
                        <h3><?php echo $row["DatadiNascita"];?></h3>
                        <h3><?php echo $row["Email"];?></h3>
                        
                    </div>
            <?php } ?> 
        <?php } ?> 



    </div>    

</body>

<?php include "footer.php" ?>