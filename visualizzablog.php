<?php session_start(); 
 include 'connessione.php';
 $is_logged = isset($_SESSION["id"]);

 $queryUtente = mysqli_query($connessione,"SELECT Utenti.Nick FROM Utenti, Blog WHERE Blog.Autore = Utenti.ID_Utente && Blog.CodiceBlog = {$_GET['blog']} ");

 $queryBlogUtente = mysqli_query($connessione,"SELECT Blog.CodiceBlog, Blog.NomeBlog FROM Utenti, Blog WHERE Blog.Autore = Utenti.ID_Utente && Utenti.ID_Utente = (SELECT Blog.Autore FROM Blog WHERE Blog.CodiceBlog = {$_GET['blog']} )");

 $queryUtenteCoautore = mysqli_query($connessione,"SELECT Blog.CodiceBlog, Blog.NomeBlog FROM Blog, Coautore WHERE Coautore.CodiceBlog = Blog.CodiceBlog && Coautore.ID_Utente = (SELECT Blog.Autore FROM Blog WHERE Blog.CodiceBlog = {$_GET['blog']} )");

 $query_mostraBlog = mysqli_query($connessione,"SELECT NomeBlog, Sfondo, Descrizione FROM Blog WHERE Blog.CodiceBlog = {$_GET['blog']} ");
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
        <?php  //if($is_logged){
            
                //echo '<a href="logout.php">';
                //echo '<button type="button" class="btn btn-danger">Logout</button> </a>';

                //} else {
                
                //echo '';

                //}?> 
      
</header>

<nav id="menu" class="unvisible">

                    <!--  altri blog dell' utente 
                    blog di cui l'utente è coautore -->

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


    <div id="homepage">
        <?php while($row = mysqli_fetch_array($query_mostraBlog)) { ?> 
        <div>
            <img src="<?php echo $row["Sfondo"];?>" alt="<?php echo $row["Sfondo"];?>">
            <h3><?php echo $row["NomeBlog"];?></h3>
            <p><?php echo $row["Descrizione"];?></p>
        </div>
        <?php } ?>

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