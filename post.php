<?php session_start(); 

var_dump($_POST);

    if(isset($_POST["post_txt"])){

         $newpost = $_POST["post_txt"];

        $querypost = "INSERT INTO Articoli(TESTO) VALUES ('{$newpost}')";

         $creaPost = mysqli_query($connessione, $query);
     }

    // header ("Location: area_riservata.php");
?>
     