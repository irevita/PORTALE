<?php
include('check_session.php');
if (!isset($_SESSION['id_utente'])) {
  header("Location: login.php");
  exit();
}
$menu = 1;
?>
<!DOCTYPE html>
<html lang="it"> 
<head>
	<title>Apertura Post</title> 
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="style_index.css">
</head>
<body>
  <header>
    <?php include('header.php'); ?>
  </header>

  <?php 
	require('connect.php');

    if((isset($_GET['id'])) && (is_numeric($_GET['id'])) &&(isset($_GET['blog']))) { 
      $id_post = htmlspecialchars($_GET['id'], ENT_QUOTES);
      $blog = htmlspecialchars($_GET['blog'], ENT_QUOTES);
    }elseif((isset($_POST['id'])) && (is_numeric($_POST['id'])) &&(isset($_POST['blog']))) { 
      $id_post = htmlspecialchars($_POST['id'], ENT_QUOTES);
      $blog = htmlspecialchars($_POST['blog'], ENT_QUOTES);
    } else {
      echo '<p class="error">ID non valido.</p>'; 
      exit();
    }
  
  
	
    $query = "SELECT id_post, titolo, testo, immagine, data_creazione FROM Post WHERE id_post='" . $id_post . "' and autore = '".$_SESSION['id_utente']."' and blog ='" . $blog . "'";
    $query3 = "SELECT id_post, titolo, testo, immagine, data_creazione FROM Post WHERE id_post='" . $id_post . "' and autore != '".$_SESSION['id_utente']."' and blog ='" . $blog . "'";
    $result = mysqli_query($mysqli, $query);
    $result3 = mysqli_query($mysqli, $query3);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC);
    if ($id_post == $row["id_post"] || $id_post == $row3["id_post"]) { 
    	if (mysqli_num_rows($result)) {
    		  echo '
                 <div class="blogcontainer">
                    <img class="imgBlog" src='.$row["immagine"].' width="50%" height="200">
                    <h3 class="card-title">Titolo post: '.$row["titolo"].'</h3>
                    <h4 class="card-title">Testo post: '.$row["testo"].'</h4>
                    <h5 class="card-title">Data creazione post: '.$row["data_creazione"].'</h5>';
          echo'</div>';
    	}
    	if (mysqli_num_rows($result3)) {
    		
    		  echo '
                <div class="blogcontainer">
                  <img class="imgBlog" src='.$row3["immagine"].' width="50%" height="200">
                  <h3 class="card-title">Titolo post: '.$row3["titolo"].'</h3>
                  <h4 class="card-title">Testo: '.$row3["testo"].'</h4>
                  <h5 class="card-title">Data creazione: '.$row3["data_creazione"].'</h5>';
          echo'</div>';
    	}
    }else {
    	echo "<p style = 'color:red'>Non puoi accedere a Post non creati da te.</p>";
      	echo '<a href="lista_blog.php"> Torna ai tuoi Blog </a>';
      	exit();
    } 
?>
	
	
	<?php

    $query="SELECT recensione,username FROM Valutazione,Utente WHERE Valutazione.post=".$id_post." AND utente !=".$_SESSION['id_utente']." AND utente = Utente.id_utente";
    $result = mysqli_query($mysqli, $query);
    if(mysqli_num_rows($result)) {
      while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo'<p>Valutazione inserita da: <b>'.$row["username"].'</b> con un voto di: <b>'.$row["recensione"].'</b></p>';
      }
    } else {
      echo'<p>Questo post non è stato ancora valutato da nessun utente.</p>';
    }



		$query = 'SELECT * FROM Valutazione WHERE utente ='.$_SESSION['id_utente'].' AND post = '.$id_post.' ';
		$result = mysqli_query($mysqli, $query);
		if($result) {
			if(mysqli_num_rows($result) == 0 ) {
				echo '<form method="post" align="center" action="valutazione.php?id='.$id_post.'&blog='.$blog.'">
       				  <label><h3>Valuta questo Post:</h3></label></br>
        				<div>
      				      <span><input type="radio" name="rating" value="1"><label for="str5"></label></span>
     	    		      <span><input type="radio" name="rating" value="2"><label for="str4"></label></span>
   					        <span><input type="radio" name="rating" value="3"><label for="str3"></label></span>
  					        <span><input type="radio" name="rating" value="4"><label for="str2"></label></span>
   					        <span><input type="radio" name="rating" value="5"><label for="str1"></label></span>
                </div>
        				<div class="valuta" align="center">
        				  <br />
        				  <button type="submit">Valuta</button>
        				</div>
        			</form>';
			}else {
				$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
				echo '<br><div>
        		   	      <p>Hai valutato questo post con un voto di: <b>'.$row["recensione"].'</b></p>
        		      </div>';
        
        echo '<a href="elimina_valutazione.php?id='.$row["id_valutazione"].'&id_post='.$id_post.'&blog='.$blog.'"> Elimina Valutazione </a>';
			}
		}

	?>
	<br><br>
	<div class="row">
  <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      require('comment.php');
    }
  ?>
    <br><br>
		<form class="form-horizontal" action="" method="POST">
			<input type="hidden" name="id_post" value=<?php echo $id_post;?>>
			<input type="hidden" name="blog" value=<?php echo $blog;?>>
			<textarea class="form-control" rows="7" cols="50" name="comment" placeholder="Commenta qui..."></textarea><br>
			<input type="submit" name="postcomment" value="Commenta">
		</form>
	</div>

	<div class="row">
		<?php
			$query = "SELECT testo, id_commento, post, data_commento, username, utente FROM Commento, Utente WHERE Commento.utente = Utente.id_utente AND post = '$id_post' ORDER BY data_commento LIMIT 10";
			$result = mysqli_query($mysqli, $query);
			if (mysqli_num_rows($result) > 0) {
        echo'<h2> Tutti i commenti </h2>';
				while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				 	$comment = $row["testo"];
				 	$id_commento = $row["id_commento"];
				 	$id_post = $row["post"];
				 	$data_commento = $row["data_commento"];
          $username = $row["username"];
				 	?>
				 	<p><?php echo $comment;?></p>
				 	<p>Data: <?php echo $data_commento;?></p>
          <p>Scritto da: <?php echo $username;?></p>
				 	<?php if($row["utente"]==$_SESSION['id_utente']) echo'<a href = "elimina_commento.php?id=' .$id_commento.'&id_post='.$id_post.'&blog='.$blog.'"> Elimina Commento </a> '?>
				 	<?php
				}
			} else {
        echo'<h2> Nessun commento. </h2>';
      }
			?>
	</div>
    <?php
      mysqli_free_result($result);
      mysqli_free_result($result3);
      mysqli_close($mysqli);
    ?>
  <footer>
    <div id="footerlogin">
      <p>Realizzato da Triolo Gabriele e Giurdanella Chiara</p>
    </div>
  </footer>
</body>
</html>






