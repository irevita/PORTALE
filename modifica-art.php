<?php 
    include "connessione.php";
    session_start();
    // query i miei blog
    $query_mieiblog = mysqli_query($connessione, "SELECT Blog.NomeBlog, Blog.Descrizione, Blog.CodiceBlog FROM Blog WHERE Blog.Autore = {$_SESSION['id']}");
    //QUERY SCEGLI TEMA
    $query_tema = mysqli_query($connessione, "SELECT Tema FROM Blog WHERE Autore = {$_SESSION['id']}");
    // query i blog di cui sono coautore
    $query_coautore = mysqli_query($connessione,"SELECT Blog.CodiceBlog, Blog.NomeBlog FROM Blog, Coautore WHERE Blog.CodiceBlog = Coautore.CodiceBlog && Coautore.ID_Utente = {$_SESSION['id']}");
    $query_blog = mysqli_query($connessione, "SELECT * FROM Blog WHERE CodiceBlog={$_GET['blog']}");
    
    $query_mieiart = mysqli_query($connessione, "SELECT Articoli.Titolo, Articoli.TESTO FROM Articoli WHERE Articoli.AutoreArt = {$_SESSION['id']}");
    $query_art = mysqli_query($connessione, "SELECT Articoli.Titolo, Articoli.TESTO, Articoli.Blog FROM Articoli WHERE CodiceArt={$_GET['CodiceArt']}");
    $row = mysqli_fetch_array($query_art);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <title>Il mio bloggo</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <?php include "header.php"?>
</head>

<?php 

    if(isset($_POST["delete"])){

        header("Location: area_riservata.php");
    }

?>

<body class="theme-<?php //$theme = mysqli_fetch_field($query_tema); echo $theme; ?>">

    <header>
        <span onclick="toggleMenu()">
            <h1 class="pointer" id="pbmenu">&#9776;</h1>
            <a href="area_riservata.php" id="pb">
                <h1 class="pointer">Portale Blog</h1>
            </a>
            <!-- <h1 class="pointer"> PORTALE</h1> -->
        </span>
        <a href="logout.php">
            <button type="button" class="btn btn-danger">Logout</button>
        </a>
    </header>

    <div class="container-fluid">
        <div class="row text-center" style="padding-top:130px;">
            <div class="col-md-4">
            </div>

            <div class="col-md-4">
                <h3>Modifica l'articolo</h3>

                <div id="infoblog">
                    <!-- funzione sfoglia  -->

                    <form method="post">
                        <div class="input-group mb-3">
                        </div>

                        <script>
                            date = new Date();
                            year = date.getFullYear();
                            month = date.getMonth() + 1;
                            day = date.getDate();
                            document.getElementById("current_date").value = year + "-" + month + "-" + day;
                        </script>

                        <input type="hidden" name="id_blog" value="<?php echo $_GET["blog"]; ?>">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Titolo</span>
                            </div>
    
                            <input type="text" name="titolo_txt" placeholder="Titolo dell'articolo" value="<?php echo addslashes($row["Titolo"]); ?>">
                        </div>   

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Testo</span>
                            </div>
                            <textarea cols="20" rows="6" name="testo_txt" placeholder="Testo dell'articolo..."><?php echo $row["TESTO"]; ?></textarea>
                        </div>
                        <button name="update_art" type="submit" class="btn btn-success">Modifica articolo</button>

                    </form>

                    <?php

                        $avviso = "";

                        if(isset($_POST['update_art'])){

                            $titolo = $_POST['titolo_txt'];
                            $testo = $_POST['testo_txt'];
                            // $querydata = "SELECT current_time()"; 
                            // $data = mysqli_query($connessione,$querydata); 

                            
                            if(!empty($titolo) &&!empty($testo)){ //&&!empty($data)){

                            $query = "UPDATE Articoli SET Titolo = '{$titolo}', TESTO = '{$testo}', Data = SYSDATE(), AutoreArt = '{$_SESSION['id']}'";
                            
                            $modificaArt = mysqli_query($connessione, $query);

                            if(!$modificaArt){
                                die('Query fallita'.mysqli_error($connessione));
                                echo "query fallita";
                            }

                            $avviso = "Dati registrati con successo";
                            echo $avviso;
                            

                            
                            // $queryCodArt = mysqli_query($connessione,"SELECT Articoli.CodiceArt FROM Articoli WHERE Articoli.Titolo ='{$titolo}'AND Articoli.TESTO = '{$testo}'AND Blog.Autore = '{$_SESSION['id']}'");
                            // $row = mysqli_fetch_array($queryCodArt);
                                
                            // header("Location: area_riservata.php?blog=".$row['CodiceBlog']);
                            
                            // }else{
                            //     $avviso = "I campi non devono essere vuoti";
                            //     echo $avviso;
                            }
                        } 
                    ?>
                    
                    <a href="area_riservata.php?blog=<?php echo $row['Blog']?>">Torna al tuo blog</a>


                </div>
            </div>
        </div>
        <!-- modale elimina blog -->

        <div class="modal fade" id="deleteBlogModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Eliminazione blog</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Sei sicuro di voler eliminare il tuo blog? </br> Se lo farai, eliminerai tutto il suo
                            contenuto!</p>
                    </div>
                    <div class="modal-footer">
                        <form action="blog.php" method="post">
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

    <nav id="menu" class="unvisible">

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

</body>

</html>