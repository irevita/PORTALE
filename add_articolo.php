<?php 
    include "connessione.php";
    session_start();
    // query categorie
    $query_categorie = mysqli_query($connessione, "SELECT Categoria FROM Categoria");
    // query i miei blog
    $query_mieiblog = mysqli_query($connessione, "SELECT Blog.NomeBlog, Blog.Descrizione, Blog.CodiceBlog FROM Blog WHERE Blog.Autore = {$_SESSION['id']}");
    //QUERY SCEGLI TEMA
    $query_tema = mysqli_query($connessione, "SELECT Tema FROM Blog WHERE Autore = {$_SESSION['id']}");
    // query i blog di cui sono coautore
    $query_coautore = mysqli_query($connessione,"SELECT Blog.CodiceBlog, Blog.NomeBlog FROM Blog, Coautore WHERE Blog.CodiceBlog = Coautore.CodiceBlog && Coautore.ID_Utente = {$_SESSION['id']}");
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

        // $newTitolo = $_POST["titolo_txt"];

        // if(!empty($newTitolo)){

        //     $queryTitolo = "INSERT INTO Articoli(Titolo) VALUES ('{$newTitolo}')";

        //     $creaTitolo = mysqli_query($connessione, $queryTitolo);
        
        //     if(!$creaTitolo){
        //     die('Query fallita'.mysqli_error($connessione));
        //     echo "query fallita";
        //     }else{
        //         echo 'nuovo post aggiunto';
        //     }

        // }
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

    <div class="container-flex">

        <h3>Crea un nuovo articolo </h3>

        <div id="articolo">
            <form action="" method="post">
                
                <input type="text" name="titolo_art" placeholder="Titolo articolo">
                </br>

                <input type="text" name="testo_art" placeholder="Scrivi...">
                </br>
            
                <select name="categories" id="cat">
                        <option> Seleziona la categoria </option>
                        <?php while($row = mysqli_fetch_array($query_categorie)) { ?>
                            <option> <?php echo $row["Categoria"]; ?></option>
                        <?php } ?>
                    
                </select>
                </br>
                <button name="add_art" type="submit" class="button">Pubblica Articolo</button>

            </form>


            <!-- funzione sfoglia  -->
            <form enctype="multipart/form-data" action="/upload/image" method="post">
                <input id="image-file" type="file" />
            </form>
        
        <?php


        $avviso = "";

        if(isset($_POST['add_art'])){

            $titolo = $_POST['titolo_art'];
            $testo = $_POST['testo_art'];
            $blog = $_GET["blog"];
            $category = $_POST["categories"];

            if(!empty($titolo) &&!empty($testo)){ 

            $query = "INSERT INTO Articoli(Titolo, TESTO, Data, AutoreArt, Blog, Categoria)
            VALUES ('{$titolo}','{$testo}', SYSDATE(), '{$_SESSION['id']}', '{$blog}', '{$category}')";
            
            $creaBlog = mysqli_query($connessione, $query);

            if(!$creaBlog){
                die('Query fallita'.mysqli_error($connessione));
                echo "query fallita";
            }

            $avviso = "Dati registrati con successo";
            echo $avviso;
            
            header("Location: area_riservata.php?blog=".$blog);

            }else{
            $avviso = "I campi non devono essere vuoti";
        echo $avviso;
            }
        } 
        ?>
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

        <!-- CATEGORIE CLICCABILI -->

        <!-- TEMA -->
        <div class="list-item">
            <span>
                <h5>Tema</h5>
            </span>
            <!-- sottocategoria -->
            <div class="sub-menu flex row center justify">
                <input id="colorpicker" type="color" value="#ffffff">
                <button id="color" class="btn-primary">Change</button>
            </div>
        </div>


        <!-- IMPOSTAZIONI PROFILO -->
        <div class="list-item">
            <span>
                <h5>Impostazioni profilo</h5>
            </span>
            <!-- sottocategoria -->
            <button name="del_blog" type="button" data-toggle="modal" data-target="#deleteBlogModal" id="mexdel">
                <!-- <span class="material-icons">delete</span>  -->
                Elimina blog
            </button>
        </div>

        <br class="line">
        <!-- I MIEI BLOG -->

        <div class="list-item" id="mieiblog">
            <h5>I miei blog </h5>
            <ul>
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

</body>

</html>