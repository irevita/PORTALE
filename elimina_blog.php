<?php
    session_start();
    include "connessione.php";

    $logged = false;
    if(isset($_SESSION["id"])){
        $logged = true;
    }

    if($logged){
        $id = $_POST["id_blog"];
        $query = mysqli_query($connessione,"DELETE FROM Blog WHERE CodiceBlog = '{$id}' AND Autore = {$_SESSION['id']}");
        
        if(!$query){
            die('Query fallita'.mysqli_error($connessione));
            echo "query fallita";
        }

        header("Location: area_riservata.php");

    }
?>