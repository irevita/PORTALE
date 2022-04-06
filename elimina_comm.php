<?php
    session_start();
    include "connessione.php";

    $logged = false;
    if(isset($_SESSION["id"])){
        $logged = true;
    }

    if($logged){
        $id = $_POST["cod_com"];
        $query = mysqli_query($connessione,"DELETE FROM Commenta WHERE CodCom = '{$id}' AND ID_Utente = {$_SESSION['id']}");

        if(!$query){
            die('Query fallita'.mysqli_error($connessione));
            echo "query fallita";
        }

        header("Location: visualizzablog.php?blog=".$_POST["articolo"]);

    }
?>