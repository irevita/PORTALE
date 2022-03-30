<?php include 'connessione.php' ?>
<?php session_start(); ?>
<?php
    $msg = ["status" => 404, "msg" => "Tutto okay!"];

    if(isset($_GET["id"]) && !empty($_GET["id"])){
        $query_blog = mysqli_query($connessione, "SELECT * FROM Blog WHERE Blog.Autore = {$_SESSION['id']} AND Blog.CodiceBlog = {$_GET['blog']}");
        $num = mysqli_num_rows($query_blog); 
        if($num){
            $query_ut = mysqli_query($connessione, "SELECT ID_Utente FROM Utenti WHERE Utenti.Nick = '{$_GET['id']}'");
            $row = mysqli_fetch_array($query_ut);
            $query_check = mysqli_query($connessione, "SELECT * FROM Coautore WHERE ID_Utente = {$row['ID_Utente']} AND CodiceBlog = {$_GET['blog']}");
            if($row['ID_Utente'] != $_SESSION['id'] && !mysqli_num_rows($query_check)){
                $query_utente = mysqli_query($connessione, "INSERT INTO Coautore(ID_Utente, CodiceBlog) VALUES ({$row['ID_Utente']}, {$_GET['blog']})");
                $msg["status"] = 200;
            } else {
                $msg["msg"] = "Utente non valido!";
            }
        } else {
            $msg["msg"] = "Non puoi modificare questo blog!";
        }
    } else {
        $msg["msg"] = "Coautore non valido!";
    }

    echo json_encode($msg);
    die;
?>