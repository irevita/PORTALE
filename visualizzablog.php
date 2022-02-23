<?php session_start(); 
 include 'connessione.php'
$is_logged = isset($_SESSION["id"]);
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
        <span onclick="toggleMenu()">
            <h1 class="pointer" id="pbmenu">&#9776;</h1>
            <a href="area_riservata.php" id="pb">
                <h1 class="pointer">Portale Blog</h1>
            </a>
            <!-- <h1 class="pointer"> PORTALE</h1> -->
        </span>
        <?php  //if($is_logged){
            
                //echo '<a href="logout.php">';
                //echo '<button type="button" class="btn btn-danger">Logout</button> </a>';

                //} else {
                
                //echo '';

                //}?> 
      
</header>


    <?php 
        $query_mostraUtente = mysqli_query($connessione, "SELECT Nick, Nome, Cognome, Nazione, DatadiNascita, Email FROM Utenti, Blog WHERE Blog.Autore=Utenti.ID_Utente && Blog.CodiceBlog={$_GET['blog']} ");
        while($row = mysqli_fetch_array($query_mostraUtente)) { ?> 

            <div class="contenitori">

                <h3><?php echo $row["Nick"];?></h3>
                <h3><?php echo $row["Nome"];?></h3>
                <h3><?php echo $row["Cognome"];?></h3>
                <h3><?php echo $row["Nazione"];?></h3>
                <h3><?php echo $row["DatadiNascita"];?></h3>
                <h3><?php echo $row["Email"];?></h3>
                
            </div>
    <?php } ?> 

</body>

<?php include "footer.php" ?>