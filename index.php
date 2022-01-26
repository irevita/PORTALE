<?php include "connessione.php"; 
  //elenco categorie
  $query_categorie = mysqli_query($connessione, "SELECT Categoria FROM Categoria");
  //elenco articoli in ordine di popolarità
  $query_articolitendenza = mysqli_query($connessione, "SELECT Articoli.Titolo, Articoli.TESTO, Blog.NomeBlog
    FROM Likes, Articoli, Blog
    WHERE Likes.CodiceArt = Articoli.CodiceArt && Articoli.Blog=Blog.CodiceBlog
    GROUP BY (Articoli.CodiceArt)
    ORDER BY (COUNT(Articoli.CodiceArt)) DESC");

?>

<?php

  $avviso = "";

  if(isset($_POST['submit'])){

    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $nazione = $_POST['nazione'];
    $email = $_POST['email'];
    $dataN = $_POST['data'];
    $telefono = $_POST['tel'];
    $documento = $_POST['documento'];
    $nickname = $_POST['username'];
    $password = $_POST['password'];

    if(!empty($nome) &&!empty($cognome) &&!empty($nazione)
    &&!empty($email) &&!empty($dataN) &&!empty($telefono)
    &&!empty($documento) &&!empty($nickname) &&!empty($password)){

      $query = "INSERT INTO Utenti(Nick, PasswordID, Nome, Cognome, Nazione, Email, DatadiNascita, Telefono, Documento)
      VALUES ('{$nickname}','{$password}','{$nome}','{$cognome}','{$nazione}','{$email}','{$dataN}','{$telefono}','{$documento}')";

      $creaUtenti = mysqli_query($connessione, $query);

      if(!$creaUtenti){
        die('Query fallita'.mysqli_error($connessione));
        echo "query fallita";
      }

      $avviso = "Dati registrati con successo";
      echo $avviso;

    }else{
      $avviso = "I campi non devono essere vuoti";
      echo $avviso;
    }
  }

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
    <a href="index.php" id="pb"><h1 class="pointer">Portale Blog</h1></a>
    <span>
      <button name="registrazione" type="submit" class="btn btn-success" data-toggle ="modal" data-target="#regModal">Registrati</button>
      <button type="button" class="btn btn-success" data-toggle ="modal" data-target="#loginModal">Accedi</button>
    </span>
  </header>

  <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Entra nell'area riservata</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="login.php" method="post">
          <div class="form-group">
              <input name="username" type="text" class="form-control" placeholder="Inserisci il tuo username" aria-describedby="emailHelp">
          </div>
          <div class="form-group">
              <input name="password" type="password" class="form-control" placeholder="Inserisci la tua password">
          </div>
          <button name="login" type="submit" class="btn btn-success">Accedi</button>
        </form>
      </div>
    </div>
  </div>


  <div class="modal fade" id="regModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Compila i campi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="index.php" method="post">
          <div class="form-group">
              <input name="nome" type="text" class="form-control" placeholder="Nome">
              <input name="cognome" type="text" class="form-control" placeholder="Cognome">
              <input name="nazione" type="text" class="form-control" placeholder="Nazione">
              <input name="email" type="email" class="form-control" placeholder="Email Address">
              <input name="data" type="date" class="form-control" placeholder="Data di nascita">
              <input name="tel" type="tel" class="form-control" placeholder="Telefono">
              <input name="documento" type="text" class="form-control" placeholder="Documento">
              <input name="username" type="text" class="form-control" placeholder="Nickname">
              <input name="password" type="password" class="form-control" placeholder="Password">
          </div>
          <button name="submit" type="submit" class="btn btn-primary">Invia</button>
        </form>
      </div> 
    </div>
  </div>


  <div class="container horizontal">
    
    <div id="left">

      <br/>
      <h3>CATEGORIE</h3>
          <!-- sottocategoria -->
      <ul><?php while($row = mysqli_fetch_array($query_categorie)) { ?>
        <p> <?php echo '<a class="menu-ctg" href="index.php?categoria='.$row["Categoria"].'">'.$row["Categoria"]."</a>"; ?> </p><?php } ?>
      </ul>

      <!-- <div class="contenitori" style="background-color:lightyellow">Qua vanno i vari cerca per..</div> -->
    </div>


    <div id="center">

      <div id="articoli_tendenza" class="<?php if(isset($_GET["categoria"])){echo "hidden";}; if(isset($_POST["click_utente"])){echo "hidden";}; if(isset($_POST["click_blog"])){echo "hidden";}; if(isset($_POST["click_articolo"])){echo "hidden";}; if(isset($_GET["profilo"])){echo "hidden";} ?>">
        <br />
        <h3>ARTICOLI DI TENDENZA</h3>
        <?php while($row = mysqli_fetch_array($query_articolitendenza)) { ?>
          <div class="contenitori">
            <h3><?php echo $row["Titolo"];?></h3>
            <p><?php echo $row["TESTO"];?></p>

            <div class="info_blog">
              <h4>Blog: &nbsp</h4><a href='"<?php echo $row["NomeBlog"] ?>".".php"'><?php echo $row["NomeBlog"] ?></a>
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
          

      <div id="sputa_categoria">
        <?php if (isset($_GET["categoria"])) { ?>
          
          <div class="annunciazio">
            <?php echo "<br/><h5>Stai cercando in:&nbsp</h5><h4> ".$_GET["categoria"]."</h4>";?>             
          </div>

          <div class="contenitori" >
            <?php 
              $query_articolicategoria = mysqli_query($connessione, "SELECT Articoli.Titolo, Articoli.TESTO, Articoli.Data, Blog.NomeBlog FROM Articoli, Blog WHERE Articoli.Blog=Blog.CodiceBlog && Articoli.Categoria='".$_GET['categoria']."'"); 
              while($row = mysqli_fetch_array($query_articolicategoria)) { ?> 
                <article>
                  <h3><?php echo $row["Titolo"];?></h3>
                  <p><?php echo $row["Data"];?></p>
                  <p><?php echo $row["TESTO"];?></p>
                </article>

                <div class="info_blog">
                  <h4>Blog: &nbsp</h4><a href='"<?php echo $row["NomeBlog"] ?>".".php"'><?php echo $row["NomeBlog"] ?></a>
                </div>
            <?php } ?>
          </div> 
          
        <?php } ?>

      </div>   
      
      <?php include "cerca.php"; ?>

    </div>

    <div id="right">
      <br/>
      <h3>Cosa ti interessa?</h3>
      <form method="post" action="index.php">
          <input type="text" name="cerca_utente" placeholder="Cerca utente" />
          <input type="submit" name="click_utente" value="CERCA"  /><br  />
          <input type="text" name="cerca_blog" placeholder="Cerca blog" />
          <input type="submit" name="click_blog" value="CERCA"  /><br  />
          <input type="text" name="cerca_articolo" placeholder="Cerca articolo" />
          <input type="submit" name="click_articolo" value="CERCA"  /><br  />
      </form>
    </div> 
  </div>

  <?php include "footer.php" ?>

</body>
</html>
