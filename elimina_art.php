<?php
    session_start();
    include "connessione.php";
    

    $logged = false;
    if(isset($_SESSION["id"])){
        $logged = true;
    }


    if($logged){
        $id = $_POST["id_art"];
        $query = mysqli_query($connessione,"DELETE FROM Articoli WHERE CodiceArt = '{$id}' AND AutoreArt = {$_SESSION['id']}");
        
        if(!$query){
            die('Query fallita'.mysqli_error($connessione));
            echo "query fallita";
        }


        header("Location: area_riservata.php");

    }
?>