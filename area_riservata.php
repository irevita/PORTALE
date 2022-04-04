<?php
  session_start();
  include "connessione.php";
  // QUERIES LIST
  $query_categorie = mysqli_query($connessione, "SELECT Categoria FROM Categoria");
  // $query_articoliseguiti 
  $query_immagini = mysqli_query($connessione, "SELECT Articoli.CodiceArt, Articoli.Titolo, Articoli.TESTO, Articoli.Blog, Blog.NomeBlog, Multimedia.Nome FROM Blog JOIN Articoli ON Articoli.Blog = Blog.CodiceBlog LEFT JOIN Multimedia ON Multimedia.CodiceArt = Articoli.CodiceArt JOIN Segui ON Segui.CodiceBlog=Articoli.Blog WHERE Segui.ID_Utente = {$_SESSION['id']} ");
  $query_articoliseguiti = mysqli_query($connessione, "SELECT Articoli.CodiceArt, Articoli.Titolo, CONCAT(SUBSTRING(Articoli.TESTO, 1, 500), '...') AS TESTO, Articoli.Blog, Blog.NomeBlog, IF((SELECT COUNT(*) FROM Multimedia WHERE Multimedia.CodiceArt = Articoli.CodiceArt) > 0, (SELECT Multimedia.Nome FROM Multimedia WHERE Multimedia.CodiceArt = Articoli.CodiceArt LIMIT 1), \"img/default.jpeg\") AS Nome FROM Blog JOIN Articoli ON Articoli.Blog = Blog.CodiceBlog LEFT JOIN Segui ON Segui.CodiceBlog=Articoli.Blog WHERE Segui.ID_Utente = {$_SESSION['id']} ");
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
  
  $logged = false;
  if(isset($_SESSION["id"])){
      $logged = true;
  }

  /*
  if($logged){
    ?>
    <p>Loggato</p>
    <?php
  }else{
    ?>
    <p>non loggato</p>
    <?php
  }
  */
  
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

        <div id="right" class="<?php if(isset($_GET["blog"])) {echo "hidden";};?>">
            <br/>
            <h3>Cosa ti interessa?</h3>

            <form method="post" action="area_riservata.php">
                <input type="text" name="cerca_utente" placeholder="Cerca utente" />
                <button type="submit" name="click_utente" value="CERCA" class="btn_cerca">CERCA</button>
            </form>
            <form method="post" action="area_riservata.php">
                <input type="text" name="cerca_blog" placeholder="Cerca blog" />
                <button type="submit" name="click_blog" value="CERCA"  class="btn_cerca">CERCA</button>
            </form>
            <form method="post" action="area_riservata.php">
                <input type="text" name="cerca_articolo" placeholder="Cerca articolo" />
                <button type="submit" name="click_articolo" value="CERCA"  class="btn_cerca">CERCA</button>
            </form>
        </div> 

        <!-- HOMEPAGE -->

        <div id="homepage">
            <?php if (!isset($_GET["blog"])) { ?>
                <div>
                    <h1>Homepage</h1>
                    <h4>Ciao <?php echo $_SESSION['utente']; ?>, benvenutə nella tua area personale!</h4>    
                    <h6> Articoli dei blog che segui... </h6>
                </div>
               
                <div id="articoli" class="<?php if(isset($_GET["categoria"])) {echo "hidden";}; if(isset($_GET["blog"])) {echo "hidden";}; if(isset($_POST["blog_seguiti"])) {echo "hidden";};
                    if(isset($_POST["click_utente"])){echo "hidden";}; if(isset($_POST["click_blog"])){echo "hidden";}; if(isset($_POST["click_articolo"])){echo "hidden";}; if(isset($_GET["profilo"])){echo "hidden";}?>">
                    
                    <?php  while($row = mysqli_fetch_array($query_articoliseguiti)) { ?>    
                    <div class="contenitori">
                        <div class="scatola">
                            <h5>Blog: </h5><a class="pulsante" href='<?php echo "visualizzablog.php?blog=".$row["Blog"] ?>'><?php echo $row["NomeBlog"]?></a>
                            <!-- BOTTONI SEGUI DA MOFICARE CON IF -->
                            <button class="btn default float">Segui già</button>
                        </div>
                        <a class="clicca" href='<?php echo "visualizzablog.php?blog=".$row["Blog"] ?>'>

                            <img style="min-height: 400px; height: 400px;" src="<?php echo $row["Nome"];?>" alt="<?php echo $row["Nome"];?>">
                            <h3><?php echo $row["Titolo"];?></h3>
                            <p><?php echo $row["TESTO"];?></p>

                            <span class="likes ">
                            <?php 
                                    if(isset($_POST['like_'.$row['CodiceArt'].''])){

                                        $query_add_like =  mysqli_query($connessione, "INSERT INTO Likes(ID_Utente, Data, CodiceArt)
                                        VALUES ('{$_SESSION['id']}', SYSDATE(), '{$row['CodiceArt']}')");

                                        if(!$query_add_like){

                                            header('Location: area_riservata.php');
                                            die('Query fallita'.mysqli_error($connessione));
                                            echo "query fallita";
                                        }

                                    }

                                    if(isset($_POST['unlike_'.$row['CodiceArt'].''])){

                                        $query_remove_like =  mysqli_query($connessione, "DELETE FROM Likes
                                        WHERE ID_Utente = '{$_SESSION['id']}' AND CodiceArt = '{$row['CodiceArt']}'");

                                        if(!$query_remove_like){

                                            header('Location: area_riservata.php');
                                            die('Query fallita'.mysqli_error($connessione));
                                            echo "query fallita";
                                        }

                                    }

                                    $query_like = mysqli_query($connessione, "SELECT COUNT(*) FROM Likes WHERE CodiceArt = {$row['CodiceArt']}");
                                    $likes = mysqli_fetch_array($query_like);
                                    
                                    if(empty($likes)) { ?>
                                        <span>Likes: 0 </span>
                                    <?php } else {?> 
                                        <span>Likes: <?php echo $likes[0];?></span>
                                    <?php } 
                                ?> 
                                <form method="post">

                                    <!-- LIKESSSSS  -->

                                    <button name=<?php echo "like_".$row['CodiceArt'].""?> type='submit' class="btn btn-success"
                                    <?php 
                                        $query_check_like = mysqli_query($connessione, "SELECT * FROM Likes WHERE ID_Utente = '{$_SESSION['id']}' AND CodiceArt = '{$row['CodiceArt']}'");
                                        $check_like = mysqli_fetch_array($query_check_like);

                                        if(!empty($check_like)){
                                            echo ' disabled=disabled ';
                                        }

                                        //if(isset($_POST['like_'.$row['CodiceArt'].''])){
                                            //echo ' disabled=disabled ';
                                        //}
                                    ?> 
                                    >Like</button>

                                    <button name=<?php echo "unlike_".$row['CodiceArt'].""?> type='submit' class="btn btn-success" 
                                    <?php 
                                        
                                        if(empty($check_like)){
                                            echo ' disabled=disabled ';
                                        }
                                        
                                        //if(isset($_POST['unlike_'.$row['CodiceArt'].''])){
                                        //echo ' disabled=disabled ';
                                        //}
                                    ?>
                                    >Unlike</button>

                                </form>

                                <!-- <span class="likes_number"></span> -->


                            </span>
                    
                            <!-- COMMENTI -->
                    
                            <div class="full comment_form">
                                <h4>Post your comment</h4>
                                <form method="post">
                                    <input name=<?php echo "text_comment_".$row['CodiceArt'].""?> type="text" placeholder="Comment"></input>
                                    <button name=<?php echo "add_comm_".$row['CodiceArt'].""?> type="submit" class="btn btn-success">Send</button>
                                </form>

                                <?php                                        
                                    if(isset($_POST['add_comm_'.$row['CodiceArt'].''])){
                                        
                                        $testo = $_POST['text_comment_'.$row['CodiceArt'].''];
                                        if(!empty($testo)){ 

                                            $query_insert_comment = "INSERT INTO Commenta(Testo, Data, ID_Utente, CodiceArt)
                                            VALUES ('{$testo}', SYSDATE(), '{$_SESSION['id']}', '{$row['CodiceArt']}')";
                                                                                                
                                            $creaCommento = mysqli_query($connessione, $query_insert_comment);
                                            if(!$creaCommento){
                                                header('Location: area_riservata.php');
                                                die('Query fallita'.mysqli_error($connessione));
                                                echo "query fallita";
                                            }
                                        }
                                    }  
                                ?>
                            </div>

                            <div id='commenti'>
                                <h6> Commenti: </h6>
                                <?php // query commenti
                                $query_commenti =  mysqli_query($connessione, "SELECT C.Testo, C.Data, U.Nick FROM Commenta AS C JOIN Utenti AS U ON C.ID_Utente = U.ID_Utente WHERE CodiceArt = {$row['CodiceArt']}");
                                while($row_comm = mysqli_fetch_array($query_commenti)) {?>
                                    <p><?php echo $row_comm["Testo"];?></p>
                                    <p><?php echo $row_comm["Data"];?> write by <?php echo $row_comm["Nick"];?> </p>
                                <?php } ?>
                            </div>
                        </a>
                    </div>
                    <?php } ?>      
            </div>
            <?php } ?> 

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
                        $query_blogprofilo = mysqli_query($connessione, "SELECT Blog.Sfondo, Blog.NomeBlog, Blog.Descrizione, Blog.CodiceBlog FROM Blog, Utenti WHERE Blog.Autore = Utenti.ID_Utente && Utenti.Nick='".$_GET['profilo']."'"); 
                        if(mysqli_num_rows($query_blogprofilo) > 0){
                        while($row = mysqli_fetch_array($query_blogprofilo)) { ?> 

                            <div class="contenitori">
                            <a class="clicca" href='<?php echo "visualizzablog.php?blog=".$row["CodiceBlog"] ?>'>
   
                                <img src="<?php echo $row["Sfondo"];?>" alt="<?php echo $row["Sfondo"];?>">
                                <h3><?php echo $row["NomeBlog"];?></h3>
                                <button class="btn success">Segui</button>
                                <p><?php echo $row["Descrizione"];?></p>
                            </a>    
                            </div>
                        <?php } ?>  
                    <?php }else echo "<b>".$_GET['profilo']."</b> non è autore di blog" ?>

                    <br/>  
                    <h4>Blog di cui <?php echo"<b>".$_GET['profilo']."</b>" ?> è coautore </h4>
                    <?php 
                        $query_blogprofilocoautore = mysqli_query($connessione, "SELECT Blog.Sfondo, Blog.NomeBlog, Blog.Descrizione, Blog.CodiceBlog FROM Blog, Utenti, Coautore WHERE Coautore.CodiceBlog = Blog.CodiceBlog && Coautore.ID_Utente = Utenti.ID_Utente && Utenti.Nick='".$_GET['profilo']."'"); 
                        if(mysqli_num_rows($query_blogprofilocoautore) > 0){
                        while($row = mysqli_fetch_array($query_blogprofilocoautore)) { ?> 

                            <div class="contenitori">
                            <a class="clicca" href='<?php echo "visualizzablog.php?blog=".$row["CodiceBlog"] ?>'>
    
                                <img src="<?php echo $row["Sfondo"];?>" alt="<?php echo $row["Sfondo"];?>">
                                <h3><?php echo $row["NomeBlog"];?></h3>
                                <button class="btn success">Segui</button>
                                <p><?php echo $row["Descrizione"];?></p>
                            </a>
                            </div>
                        <?php } ?>
                        <?php }else echo "<b>".$_GET['profilo']."</b> non è coautore di blog" ?>    

                    <?php }?>
            </div>   

            <!-- CATEOGORIE -->

            <?php if (isset($_GET["categoria"])) { ?>
                <div>
                    <?php 
                    $query_articolicategoria = mysqli_query($connessione, "SELECT Articoli.CodiceArt, Articoli.Titolo, Articoli.TESTO, Articoli.Data, Articoli.Categoria, Articoli.Blog, Multimedia.Nome, Blog.NomeBlog FROM Blog JOIN Articoli ON Articoli.Blog = Blog.CodiceBlog LEFT JOIN Multimedia ON Multimedia.CodiceArt = Articoli.CodiceArt WHERE Articoli.Categoria='".$_GET['categoria']."'"); 
                    while($row = mysqli_fetch_array($query_articolicategoria)) { ?> 
                        <div class="contenitori">
                            <div>Blog: <a href='<?php echo "visualizzablog.php?blog=".$row["Blog"] ?>'><?php echo $row["NomeBlog"]?></a></div>
                            <!-- BOTTONI SEGUI DA MOFICARE CON IF -->

                            <button name="segui" type="submit" class="btn-success" 
                             <?php
                                $query = "INSERT INTO Segui(CodiceBlog, ID_Utente, Data)
                                       VALUES ({$row['Blog']},'{$_SESSION['id']}', SYSDATE())";
                                       
                                       $segui_q = mysqli_query($connessione, $query);
                                        if(!$segui_q){
                                            die('Query fallita SEGUI'.mysqli_error($connessione));
                                            echo "query fallita SEGUI";
                                        }
                            
                                
                                $avviso = "Dati registrati con successo";
                                echo $avviso;
                            ?>
                                >Segui
                            </button>
                            <a class="clicca" href='<?php echo "visualizzablog.php?blog=".$row["Blog"] ?>'>

                                <img src="<?php echo $row["Nome"];?>" alt="<?php echo $row["Nome"];?>">
                                <h3><?php echo $row["Titolo"];?></h3>
                                <p><?php echo $row["TESTO"];?></p>
                        
                            </a>
                        </div>
                    
                    <?php } ?>
                </div> 
            <?php } ?>

            <!-- BLOGGGGG!!!!!! -->
            <?php if (isset($_GET["blog"])) { ?>
                <div>
                    
                    <?php 
                        $query_nomeblog = mysqli_query($connessione, "SELECT Blog.NomeBlog, Blog.Descrizione FROM Blog WHERE Blog.CodiceBlog='".$_GET['blog']."'");
                        $info = mysqli_fetch_array($query_nomeblog);
                    ?>
                    <div>
                        <br />
                        <h1><?php echo $info[0]; ?></h1>   
                        <h6><?php echo $info[1]; ?></h6>
                    </div>

                    <!-- MODIFICA BLOG + AGGIUNGI ARTICOLO -->

                    <form action="<?php echo 'blog-modifica.php?blog='.$_GET["blog"] ?>" method="post">
                        <!--<input type="text" name="titoloart_txt" placeholder="Titolo..."> -->
                        <button name="modifica_blog" type="submit" class="button">Modifica blog</button>           
                    </form>

                    <form action=<?php echo 'add_articolo.php?blog='.$_GET["blog"].'&AutoreArt='.$_SESSION["id"].'' ?> method="post">
                        <!-- <input type="text" name="testoart_txt" placeholder="Testo..."> -->
                        <button name="add_articolo" type="submit" class="button">Aggiungi nuovo articolo </button>
                    </form>

                   

                    <form method="post" id="form_coautore">
                            <input type="text" id="box_coautore" name="titoloart_txt" placeholder="Titolo...">
                            <button name="add_coautore" id="btn_coautore"  type="submit" class="button">Aggiungi coautore</button>           
                        </form>

                    <?php 
                    $query_articoli = mysqli_query($connessione, "SELECT Blog.NomeBlog, Articoli.Titolo, Articoli.TESTO, Articoli.Data, Articoli.Categoria, Blog.CodiceBlog, Articoli.CodiceArt FROM Articoli JOIN Blog ON Articoli.Blog = Blog.CodiceBlog WHERE Blog.CodiceBlog='".$_GET['blog']."'");
                    while($row= mysqli_fetch_array($query_articoli)) {?> 

                        <div class="contenitori">
                            <h3><?php echo $row["Titolo"];?></h3><?php echo '<a href="visualizzablog.php?blog='.$row["CodiceBlog"].'">Leggi Articolo</a>'; ?></p>
                            <p><?php echo $row["Data"];?></p>
                            <p><?php echo $row["TESTO"];?></p>
                            <div class="vedi-ctg">
                                <h5>Categoria:<p><?php echo '<a href="area_riservata.php?categoria='.$row["Categoria"].'">'.$row["Categoria"]."</a>"; ?></p></h5>
                                <form action="<?php echo 'modifica-art.php?blog='.$_GET["blog"].'&CodiceArt='.$row["CodiceArt"] ?>" method="post">
                                    <!--<input type="text" name="titoloart_txt" placeholder="Titolo..."> -->
                                       <button name="modifica_art" type="submit" class="button">Modifica articolo</button>           
                                </form>
                        
                            </div>
                        </div>
                    <?php } ?>
                </div> 
            <?php } ?>
            


            <?php if(isset($_POST["blog_seguiti"])){ ?>
                <div>
                    <?php while($row = mysqli_fetch_array($query_blogseguiti)) { ?> 
                    <div class="contenitori">
                    <a class="clicca" href='<?php echo "visualizzablog.php?blog=".$row["CodiceBlog"] ?>'>
                     
                        <img src="<?php echo $row["Sfondo"];?>" alt="<?php echo $row["Sfondo"];?>">
                        <h3><?php echo $row["NomeBlog"];?></h3></a> 
                        <a class="clicca" href='<?php echo "visualizzablog.php?blog=".$row["CodiceBlog"] ?>'>
                        <p><?php echo $row["Descrizione"];?></p>
                    </a>    
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

                            echo '<li><a href="area_riservata.php?profilo='.$row["Nick"].'">'.$row["Nick"]."</a></li>";
                        }
                        
                    }else{
                        echo "<br/>Al momento non sono stati trovati utenti con questo nome.";
                    }
                }
            ?>    

            <?php 
                if(isset($_POST['click_blog'])){
                    $search_blog = $_POST["cerca_blog"];
                    $sql_cerca_blog = mysqli_query($connessione, "SELECT NomeBlog, Descrizione, Sfondo, CodiceBlog FROM Blog WHERE NomeBlog LIKE '%" . $search_blog . "%'");
                    if(mysqli_num_rows($sql_cerca_blog) > 0){
                        echo "<br/><h4>Risultati della tua ricerca</h4>";
                        echo "<p style='margin-left:25px;'>Trovate ". mysqli_num_rows($sql_cerca_blog)." voci per il termine <b>".stripslashes($search_blog)."</b></p>\n";

                        while($row = mysqli_fetch_array($sql_cerca_blog)) { ?>

                            <div class="contenitori">
                            <a class="clicca" href='<?php echo "visualizzablog.php?blog=".$row["CodiceBlog"] ?>'>

                                <img src="<?php echo $row["Sfondo"];?>" alt="<?php echo $row["Sfondo"];?>">
                                <h3><?php echo $row["NomeBlog"];?></h3></a>
                                <button class="btn-success">Segui</button>
                                <a class="clicca" href='<?php echo "visualizzablog.php?blog=".$row["CodiceBlog"] ?>'>
                                <p><?php echo $row["Descrizione"];?></p> 
                            </a>                
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
                    $sql_cerca_articolo = mysqli_query($connessione, "SELECT Articoli.Titolo, Articoli.TESTO, Articoli.Data, Blog.NomeBlog, Multimedia.Nome, Blog.CodiceBlog FROM Blog JOIN Articoli ON Articoli.Blog = Blog.CodiceBlog LEFT JOIN Multimedia ON Multimedia.CodiceArt = Articoli.CodiceArt WHERE Articoli.Titolo LIKE '%".$search_articolo."%' GROUP BY Articoli.CodiceArt" );
                    if(mysqli_num_rows($sql_cerca_articolo) > 0){
                        echo "<br/><h4>Risultati della tua ricerca</h4>";
                        echo "<p style='margin-left:25px;'>Trovate ". mysqli_num_rows($sql_cerca_articolo)." voci per il termine <b>".stripslashes($search_articolo)."</b></p>\n";

                        while($row = mysqli_fetch_array($sql_cerca_articolo)) { ?>
                            
                            <div class="contenitori">
                                <a class="clicca" href='<?php echo "visualizzablog.php?blog=".$row["CodiceBlog"] ?>'>

                                <article>
                                    <img src="<?php echo $row["Nome"];?>" alt="<?php echo $row["Nome"];?>">  
                                    <h3><?php echo $row["Titolo"];?></h3>
                                    <p><?php echo $row["Data"];?></p>
                                    <p><?php echo $row["TESTO"];?></p>
                                </article>
                                <div class="info_blog">
                                    <h4>Blog: &nbsp</h4><a href='<?php echo "visualizzablog.php?blog=".$row["CodiceBlog"] ?>'><?php echo $row["NomeBlog"]?></a>
                                    <button class="btn-success">Segui</button>
                                </div>
                                </a>
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
            
            <button nome ="pulsante_coautore" class="menu-btn">I blog di cui sono coautore</button>
            
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

            <button name="modificadati" type="submit" class="btn btn-success sub-menu" data-toggle="modal" data-target="#modDati" id="mexdel">
                <!-- <span class="material-icons">delete</span>  -->
                Modifica dati personali
            </button>
            <button name="eliminazione" type="submit" class="btn btn-danger sub-menu" data-toggle="modal" data-target="#deleteModal" id="mexdel">
                <!-- <span class="material-icons">delete</span>  -->
                Elimina account
            </button>
        </div>
   
    
    </nav>

    <div class="modal fade" id="modDati" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Modifica i campi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="area_riservata.php" method="post">
            <div class="form-group">
                <input name="username" type="text" class="form-control" placeholder="Nickname">
                <input name="password" type="password" class="form-control" placeholder="Password">
                <input name="email" type="email" class="form-control" placeholder="Email Address">
                <input name="tel" type="tel" class="form-control" placeholder="Telefono">      
            </div>
            <button name="submit_modifiche" type="submit" class="btn btn-primary">Modifica</button>
            </form>
        </div> 
        </div>
    </div> 

    <?php

    $avviso = "";

    if(isset($_POST['submit_modifiche'])){

        $email = $_POST['email'];
        $telefono = $_POST['tel'];
        $nickname = $_POST['username'];
        $password = $_POST['password'];
        
        if(!empty($email) &&!empty($telefono) &&!empty($nickname) &&!empty($password)){

            $query = "UPDATE Utenti SET Nick = $nickname, PasswordID = $password, Email = $email, Telefono = $telefono WHERE ID_Utente = {$_SESSION['id']})";
        
            // $query = "UPDATE Utenti INTO Utenti SET Nick=?, PasswordID=?, Telefono=?, Email=? WHERE ID_Utente={$_SESSION['id']}";
            $modUtente = mysqli_query($connessione, $query);

            if(!$modUtente){
                die('Query fallita'.mysqli_error($connessione));
                echo "query fallita";
            }

            $avviso = "Dati modificati con successo";
            echo $avviso;

            }else{
            $avviso = "I campi non devono essere vuoti";
            echo $avviso;
            }
        }

    ?>


    <?php include "footer.php" ?>



    <script>
$(".btn-segui").click(function() {
    //console.log();
    id_btn = $(this).attr("id");
    $.ajax({
  method: "GET",
  url: "segui.php",
  data: { id: id_btn}
})
  .done(function( msg ) {
    const myArr = JSON.parse(msg);

    if(myArr["status"] == 201){
        $("#"+id_btn).html("Segui");
    } else {
        $("#"+id_btn).html("Seguito");
    }
  });
});

$("#form_coautore").submit(function( event ) {
  coautore = $("#box_coautore").val();
  $.ajax({
  method: "GET",
  url: "coautore.php",
  data: { id: coautore, blog: <?php echo $_GET["blog"]; ?>}
})
  .done(function( msg ) {
    const myArr = JSON.parse(msg);
    alert(myArr["msg"]);
    if(myArr["status"] == 200){
        $("#box_coautore").html("");
    }
  });
  event.preventDefault();
});


</script>


</body>

</html>             