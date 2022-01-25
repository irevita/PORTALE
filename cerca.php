
<form method="post" action="cerca.php">
    <input type="text" name="cerca_utente" placeholder="Cerca utente" /><br  />
    <input type="submit" value="CERCA"  />
</form>

<h2 class="intestazione">Risultati della tua ricerca</h2>

<?php include 'connessione.php'; 

    $testo = $_POST["cerca_utente"];
    $sql_cerca = mysqli_query($connessione, "SELECT Nick FROM Utenti WHERE Nick LIKE '%" . $testo . "%'");
    $trovati = mysqli_num_rows($sql_cerca);
    if($trovati > 0){

        echo "<p class='desc' style='margin-left:25px;'>Trovate $trovati voci per il termine <b>".stripslashes($testo)."</b></p>\n";

        //inizio il loop
        while($row = mysqli_fetch_array($sql_cerca)) {

            echo '<p>' . $row['Nick'] . '</p>';

        } //fine LOOP valori trovati

    } //fine risultati if

    else{ //se non ci sono risultati

    // notifica in caso di mancanza di risultati
        echo "Al momento non sono stati pubblicati post/articoli che contengano i termini cercati.";

    }//fine else 

?>

