<?php 
    include "connessione.php";
    ?>

<!DOCTYPE html>
<html lang="it">

<head>
    <title>Il mio bloggo</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <?php include "header.php"?>
</head>

<h3>Scrivi il tuo articolo! </h3>

<div id="infoarticolo">
    <form action="bloggo.php" method="post">
     
        <input type="text" name="titoloart_txt" placeholder="Titolo...">
        <button name="addtitolo" type="submit" class="button">+</button>    
    </br>

        <!-- <input type="text" name="testoart_txt" placeholder="Testo..."> -->
        <button name="addtesto" type="submit" class="button">+</button>

    </form>

<!-- funzione sfoglia  -->
<form enctype="multipart/form-data" action="/upload/image" method="post">
                <input id="image-file" type="file" />
            </form>
            <?php


$avviso = "";

if(isset($_POST['addtitolo'])){
if(isset($_POST['addtesto'])){

  $titoloart = $_POST['titoloart_txt'];
  $testoart = $_POST['testoart_txt'];

  if(!empty($titoloart) &&!empty($testoart)){

    $query = "INSERT INTO Articoli(Titolo, TESTO)
    VALUES ('{$titoloart}','{$testoart}', '{$_SESSION['id']}')";
  
    $creaArticolo = mysqli_query($connessione, $query);

    if(!$creaArticolo){
      die('Query fallita'.mysqli_error($connessione));
      echo "query fallita";
    }


    $avviso = "Titolo inserito!";
    echo $avviso;

 
  }else{
    $avviso = "Inizia a scrivere ;)";
    echo $avviso;
  
  }
}
}
?>

<!-- 
PROVA AGGIUNGI COMMENTO 
 </span>
                <div class="full comment_form">
                            <h4>Post your comment</h4>
                            <form action="index.html" method="post">   
                            <input type="text" name="comment_txt" placeholder="Comment"></input>
                            <button name="add_comment" type="submit" class="button">Send</button>
                            </form> 
                        </div>
                </div>
                <?php } 
                
                $avviso = "";
                if(isset($_POST['add_comment'])){

                    $commento = $_POST['comment_txt'];

                    if (!empty($commento)){

                        $query = "INSERT INTO Commenti(Testo, ID_Utente) 
                        VALUES ('{$commento}'), '{$_SESSION ['id']}')";
                    
                    $creaCommento = mysqli_query($connessione, $query);

                    if(!$creaCommento){
                        die('Query fallita'.mysqli_error($connessione));
                        echo "query fallita";
                    }
                    $avviso = "Commento inserito!";
                    echo $avviso;
                    }else{
                        $avviso = "Prima di pubblicare un commento...scrivilo!";
                    echo $avviso;
                    }
                }
                
                ?>
                
                </div>

 -->