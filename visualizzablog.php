<?php session_start(); 
 include 'connessione.php';
 $is_logged = isset($_SESSION["id"]);

 $queryUtente = mysqli_query($connessione,"SELECT Utenti.Nick FROM Utenti, Blog WHERE Blog.Autore = Utenti.ID_Utente && Blog.CodiceBlog = {$_GET['blog']} ");

 $queryBlogUtente = mysqli_query($connessione,"SELECT Blog.CodiceBlog, Blog.NomeBlog FROM Utenti, Blog WHERE Blog.Autore = Utenti.ID_Utente && Utenti.ID_Utente = (SELECT Blog.Autore FROM Blog WHERE Blog.CodiceBlog = {$_GET['blog']} )");

 $queryUtenteCoautore = mysqli_query($connessione,"SELECT Blog.CodiceBlog, Blog.NomeBlog FROM Blog, Coautore WHERE Coautore.CodiceBlog = Blog.CodiceBlog && Coautore.ID_Utente = (SELECT Blog.Autore FROM Blog WHERE Blog.CodiceBlog = {$_GET['blog']} )");

 $query_mostraBlog = mysqli_query($connessione,"SELECT NomeBlog, Sfondo, Descrizione FROM Blog WHERE Blog.CodiceBlog = {$_GET['blog']}");

 $query_articoliBlog = mysqli_query($connessione, "SELECT CodiceArt, Titolo, TESTO FROM Articoli WHERE Blog = {$_GET['blog']}");
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
        <a href="index.php">
            <button type="button" class="btn btn-danger">Torna indietro</button>
        </a>
        <?php  //if($is_logged){
            
                //echo '<a href="logout.php">';
                //echo '<button type="button" class="btn btn-danger">Logout</button> </a>';

                //} else {
                
                //echo '';

                //}?> 
      
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

    <div id="homepage">
        <?php while($row = mysqli_fetch_array($query_mostraBlog)) { ?> 
        <div>
            <img src="<?php echo $row["Sfondo"];?>" alt="<?php echo $row["Sfondo"];?>">
            <h3><?php echo $row["NomeBlog"];?></h3>
            <nav class="sticky">				
                <ul id="meno">
                    <form action="visualizzablog.php?blog=<?php echo $_GET['blog'];?>" method="post">
                    <!-- OPPURE CON I BOTTONI -->
                        <li><button title="Home" href="visualizzablog.php?blog=<?php echo $_GET['blog'];?>"><u>Articoli</u></Button></li>
                        <li><button name="infoblog">Info Blog</button></li>
                        <li><input type="submit" name="click_articolo" value="CERCA" />
                            <input type="text" name="cerca_articolo" placeholder="Cerca articolo"/></li>
                    </form>
                </ul>			
            </nav>
        </div>
        <?php } ?>
        <div id = "articoliBlog" class="<?php if(isset($_POST["infoblog"])) {echo "hidden";}; if(isset($_POST['click_articolo'])) {echo "hidden";};?>">
            <?php while($row = mysqli_fetch_array($query_articoliBlog)) { ?> 
            <article>
                <h3><?php echo $row["Titolo"];?></h3>
                <p><?php echo $row["TESTO"];?></p>
            </article>
            <?php } ?>
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
                $search_articolo = $_POST["cerca_articolo"];
                $sql_cerca_articolo = mysqli_query($connessione, "SELECT Articoli.Titolo, Articoli.TESTO, Articoli.Data, Multimedia.Nome FROM Articoli LEFT JOIN Multimedia ON Multimedia.CodiceArt = Articoli.CodiceArt WHERE Articoli.Blog = {$_GET['blog']}) && Articoli.Titolo LIKE '%".$search_articolo."%' GROUP BY Articoli.CodiceArt" );
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