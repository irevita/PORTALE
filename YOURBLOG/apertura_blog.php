<?php
include('check_session.php');
if (isset($_SESSION['id_utente'])) { $menu = 1; }
  else { $menu = 2; }
?>
<!DOCTYPE html>
<html lang="it"> 
<head>
	<title>Apertura Blog</title> 
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="style_index.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>
<body>
  <header>
    <?php include('header.php'); ?>
  </header>
	<?php 
	require('connect.php');

    if((isset($_GET['id'])) && (is_numeric($_GET['id']))) { 
      $id_blog = htmlspecialchars($_GET['id'], ENT_QUOTES);
    } elseif((isset($_POST['id'])) && (is_numeric($_POST['id']))) {
      $id_blog = htmlspecialchars($_POST['id'], ENT_QUOTES);
    } else {
      echo '<p class="error">ID non valido.</p>'; 
      exit();
    }

    $query1 = "SELECT id_utente FROM Utente, Blog WHERE id_blog='" . $id_blog . "' and Blog.autore = Utente.id_utente ";
    $query2 = "SELECT * FROM Collaboratore, Utente, Blog WHERE Collaboratore.blog = Blog.id_blog AND Collaboratore.utente = Utente.id_utente and Collaboratore.blog = ".$id_blog."";
    $result1 = mysqli_query($mysqli, $query1);
    $result2 = mysqli_query($mysqli, $query2);
    $row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);
    $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);

if (isset($_SESSION['id_utente'])) {
    if (($_SESSION["id_utente"] == $row1["id_utente"])|| ($_SESSION["id_utente"] == $row2["utente"] && $row2["blog"] == $id_blog)) { //se l'utente è il creatore o il collaboratore del blog selezionato 
      $queryDati = "SELECT autore, username, titolo, descrizione, immagine FROM Blog, Utente WHERE id_blog='".$id_blog. "' and Blog.autore = Utente.id_utente ";
      $result = mysqli_query($mysqli, $queryDati);
      if (mysqli_num_rows($result) == 1) {
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo '
                   <div class="contenitore">
                      <img class="blogImg" src='.$row["immagine"].' width="50%" height="200">
                      <h2 class="titleBlog"><b>Blog: ' . $row["titolo"] . '</b></h2>
                      <h5><b>Autore: ' . $row["username"] . '</b></h5>
                      <h5><b>Descrizione: ' . $row["descrizione"] . '</b></h5>
                      <a href="post.php?id=' . $id_blog . '" id = "leggi">Crea Post</a>
                   </div>';
        }
      }else {
             echo "Nessun dato dell'utente";
      }
  ?>

  <br />
  <br />

  <?php
    $query = "SELECT id_post, Post.titolo as titolopost, Post.testo as testopost, Utente.username as autorepost FROM Post,Blog,Utente WHERE Post.autore = Utente.id_utente AND Post.blog = Blog.id_blog AND Blog.id_blog='" . $id_blog . "'";
    $result = mysqli_query($mysqli, $query);
  
        if (mysqli_num_rows($result) > 0) {
          while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo '
                <div class="blogcontainer">
                    <h4><b>Titolo Post: ' . $row["titolopost"] . '</b></h4> 
                    <h5><b>Testo: ' . $row["testopost"] . '</b></h5>
                    <h5><b>Autore: ' . $row["autorepost"] . '</b></h5>
                    <a href="apertura_post.php?id=' . $row["id_post"] . '&blog='. $id_blog . '" id="leggi">Vai al Post</a>
                    <a href = "delete_post.php?id=' . $row["id_post"].'&blog='.$id_blog.'" id="leggi"> Delete</a>
                </div>';
          }
      } else {
        echo "Non hai aggiunto ancora nessun post.";
      }

    } else { //utente registrato non creatore o collaboratore del blog selezionato
      
      $queryDati = "SELECT autore, username,immagine, titolo, descrizione FROM Blog, Utente WHERE id_blog='".$id_blog. "' and Blog.autore = Utente.id_utente ";
      $result = mysqli_query($mysqli, $queryDati);
      if (mysqli_num_rows($result) == 1) {
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
               echo '
                      <div class="contenitore">
                        <img class="blogImg" src='.$row["immagine"].' width="50%" height="200">
                        <h2 class="titleBlog"><b>Titolo blog: ' . $row["titolo"] . '</b></h2>
                        <h5><b>Autore: ' . $row["username"] . '</b></h5>
                        <h5><b>Descrizione: ' . $row["descrizione"] . '</b></h5>
                      </div>';
        }

        $query = "SELECT id_post, Post.titolo as titolopost, Post.testo as testopost, Utente.username as autorepost FROM Post,Blog,Utente WHERE Post.autore = Utente.id_utente AND Post.blog = Blog.id_blog AND Blog.id_blog='" . $id_blog . "'";
        $result = mysqli_query($mysqli, $query);
 
        if (mysqli_num_rows($result) > 0) {
          while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo '
                  <div class="blogcontainer">
                    <h4><b>Titolo Post: ' . $row["titolopost"] . '</b></h4> 
                    <h5><b>Testo: ' . $row["testopost"] . '</b></h5>
                    <h5><b>Autore: ' . $row["autorepost"] . '</b></h5>
                    <a href="apertura_post.php?id=' . $row["id_post"] . '&blog='. $id_blog . '" id="leggi">Vai al Post</a>
                  </div>';
          }
        }else {
          echo "Non è stato aggiunto nessun post.";
        }

      }else {
             echo "nessun dato dell'utente";
      }
    }//fine secondo if

  }else { //utente non registrato che visualizza il blog selezionato
      $queryDati = "SELECT autore, username,immagine, titolo, descrizione FROM Blog, Utente WHERE id_blog='".$id_blog. "' and Blog.autore = Utente.id_utente ";
      $result = mysqli_query($mysqli, $queryDati);
      if (mysqli_num_rows($result) == 1) {
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
               echo '
                    <div class="contenitore">
                      <img class="blogImg" src='.$row["immagine"].' width="50%" height="200">
                      <h2 class="titleBlog"><b>Blog: ' . $row["titolo"] . '</b></h2>
                      <h5><b>Autore: ' . $row["username"] . '</b></h5>
                      <h5><b>Descrizione: ' . $row["descrizione"] . '</b></h5>
                    </div>';
        }
      }else {
             echo "Nessun dato dell'utente";
      }
    
    $query = "SELECT Post.id_post,Post.titolo as titolopost, Post.testo as testopost, Utente.username as autorepost FROM Post,Blog,Utente WHERE Post.autore = Utente.id_utente AND Post.blog = Blog.id_blog AND Blog.id_blog='" . $id_blog . "'";
    $result = mysqli_query($mysqli, $query);

      if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
          echo '
                <div class="blogcontainer">
                  <h4><b>Titolo Post: ' . $row["titolopost"] . '</b></h4> 
                  <h5><b>Testo: ' . $row["testopost"] . '</b></h5>
                  <h5><b>Autore: ' . $row["autorepost"] . '</b></h5>
                </div>';
        //visualizzazione delle valutazioni per gli utenti non registrati al blog        
        $queryval = "SELECT recensione,username FROM Valutazione,Utente WHERE Valutazione.utente=Utente.id_utente AND post =".$row["id_post"]."";
        $resultval = mysqli_query($mysqli, $queryval);
        if (mysqli_num_rows($resultval) > 0) {
          while($rowval = mysqli_fetch_array($resultval, MYSQLI_ASSOC)) {
            echo'<p>Valutazione inserita da: <b>'.$rowval["username"].'</b> con un voto di: <b>'.$rowval["recensione"].'</b></p>';
          }
        } else {
          echo'<p>Questo post non è ancora stato valutato da nessun utente.</p>';
        }


        //visualizzazione dei commenti per gli utenti non registrati al blog
        $query1 = "SELECT testo,data_commento,Utente.username FROM Commento,Utente WHERE Commento.utente=Utente.id_utente AND post =".$row["id_post"]."";
        $result1 = mysqli_query($mysqli, $query1);
        if (mysqli_num_rows($result1) > 0) {
          echo"<p>Tutti i commenti relativi a questo post:</p>";
        } else {
          echo"<p>Nessun commento relativo a questo post.</p>";
        }
        if (mysqli_num_rows($result) > 0) {
          while($row4 = mysqli_fetch_array($result1, MYSQLI_ASSOC)) {
            echo'<br><p>'.$row4["testo"].'</p>';
            echo'<br><p>Scritto da: '.$row4["username"].'</p>';
            echo'<h5>Pubblicato il: '.$row4["data_commento"].'</h5>';
          }
        }
      }
    }
  }
  ?>

  <br />
  <br />
  <br />

  <div>
    <?php
      if (isset($_SESSION['id_utente'])) {
        if (($_SESSION["id_utente"] == $row1["id_utente"])) { //se si tratta dell'utente creatore del blog
    ?>
    <span>Cerca collaboratore</span>
    <input type="text" name="search_text" id="search_text" placeholder="Cerca collaboratore" />
  </div>
  <br />
  <div id="result"></div>
    <?php
      mysqli_free_result($result);
      mysqli_free_result($result1);
      mysqli_free_result($result2);
      mysqli_close($mysqli);
      }
    }
  ?>
  <footer>
    <div id="footerlogin">
      <p>Realizzato da Triolo Gabriele e Giurdanella Chiara</p>
    </div>
  </footer>
</body>
</html>

<script>
$(document).ready(function(){
  load_data();
  function load_data(query)
  {
    $.ajax({
      url:"cerca_collaboratore.php",
      method:"post",
      data:{query:query},
      success:function(data)
      {
        $('#result').html(data);
      }
    });
  }
  
  $('#search_text').keyup(function(){
    var search = $(this).val();
    if(search != '')
    {
      load_data(search);
    }
    else
    {
      load_data();      
    }
  });
});
</script>

	