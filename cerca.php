
<form method="post" action="cerca.php">
    <input type="text" name="cerca_utente" placeholder="Cerca utente" />
    <input type="submit" name="click_utente" value="CERCA"  /><br  />
    <input type="text" name="cerca_blog" placeholder="Cerca blog" />
    <input type="submit" name="click_blog" value="CERCA"  /><br  />
    <input type="text" name="cerca_articolo" placeholder="Cerca articolo" />
    <input type="submit" name="click_articolo" value="CERCA"  /><br  />
</form>


<?php 

    include 'connessione.php'; 

    $search_utente = $_POST["cerca_utente"];
    $search_blog = $_POST["cerca_blog"];
    $search_articolo = $_POST["cerca_articolo"];

    $sql_cerca_utente = mysqli_query($connessione, "SELECT Nick FROM Utenti WHERE Nick LIKE '%" . $search_utente . "%'");
    $sql_cerca_blog = mysqli_query($connessione, "SELECT NomeBlog FROM Blog WHERE NomeBlog LIKE '%" . $search_blog . "%'");
    $sql_cerca_articolo = mysqli_query($connessione, "SELECT Titolo FROM Articoli WHERE Titolo LIKE '%" . $search_articolo . "%'");


    if(isset($_POST['click_utente'])){
        if(mysqli_num_rows($sql_cerca_utente) > 0){

            echo "<h2>Risultati della tua ricerca</h2>";
            echo "<p class='desc' style='margin-left:25px;'>Trovate". mysqli_num_rows($sql_cerca_utente)." voci per il termine <b>".stripslashes($search_utente)."</b></p>\n";

            while($row = mysqli_fetch_array($sql_cerca_utente)) {

                echo '<p>' . $row['Nick'] . '</p>';

            }
        }

        else{
            echo "Al momento non sono stati trovati utenti con questo nome.";
        }
    }

    if(isset($_POST['click_blog'])){
        if(mysqli_num_rows($sql_cerca_blog) > 0){
            echo "<h2>Risultati della tua ricerca</h2>";
            echo "<p class='desc' style='margin-left:25px;'>Trovate". mysqli_num_rows($sql_cerca_blog)." voci per il termine <b>".stripslashes($search_utente)."</b></p>\n";

            while($row = mysqli_fetch_array($sql_cerca_blog)) {

                echo '<p>' . $row['NomeBlog'] . '</p>';

            }
        }

        else{
            echo "Al momento non sono stati trovati blog con questo nome.";
        }
    }

    if(isset($_POST['click_articolo'])){
        if(mysqli_num_rows($sql_cerca_articolo) > 0){
            echo "<h2>Risultati della tua ricerca</h2>";
            echo "<p class='desc' style='margin-left:25px;'>Trovate". mysqli_num_rows($sql_cerca_articolo)." voci per il termine <b>".stripslashes($search_utente)."</b></p>\n";

            while($row = mysqli_fetch_array($sql_cerca_articolo)) {

                echo '<p>' . $row['Titolo'] . '</p>';

            }
        }

        else{
            echo "Al momento non sono stati trovati articoli con questo nome.";
        }
    }    
?>

