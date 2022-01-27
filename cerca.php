<?php 
    include 'connessione.php'; 

    if(isset($_POST['click_utente'])){
        $search_utente = $_POST["cerca_utente"];
        $sql_cerca_utente = mysqli_query($connessione, "SELECT Nick FROM Utenti WHERE Nick LIKE '%" . $search_utente . "%'");
        if(mysqli_num_rows($sql_cerca_utente) > 0){

            echo "<br/><h4>Risultati della tua ricerca</h4>";
            echo "<p style='margin-left:25px;'>Trovate ". mysqli_num_rows($sql_cerca_utente)." voci per il termine <b>".stripslashes($search_utente)."</b></p>\n";

            while($row = mysqli_fetch_array($sql_cerca_utente)) {

                echo '<a href="index.php?profilo='.$row["Nick"].'">'.$row["Nick"]."</a>";
            }
            
        }else{
            echo "<br/>Al momento non sono stati trovati utenti con questo nome.";
        }
    }

    if(isset($_POST['click_blog'])){
        $search_blog = $_POST["cerca_blog"];
        $sql_cerca_blog = mysqli_query($connessione, "SELECT NomeBlog, Descrizione, Sfondo FROM Blog WHERE NomeBlog LIKE '%" . $search_blog . "%'");
        if(mysqli_num_rows($sql_cerca_blog) > 0){
            echo "<br/><h4>Risultati della tua ricerca</h4>";
            echo "<p style='margin-left:25px;'>Trovate ". mysqli_num_rows($sql_cerca_blog)." voci per il termine <b>".stripslashes($search_blog)."</b></p>\n";

            while($row = mysqli_fetch_array($sql_cerca_blog)) {

                echo '<div class="contenitori">';
                echo "<img src=".$row['Sfondo'].">";
                echo '<h3>'.$row["NomeBlog"].'</h3>';
                echo '<p>'.$row["Descrizione"].'</p></div>';      
            }

        }else{
            echo "<br/>Al momento non sono stati trovati blog con questo nome.";
        }
    }

    if(isset($_POST['click_articolo'])){
        $search_articolo = $_POST["cerca_articolo"];
        $sql_cerca_articolo = mysqli_query($connessione, "SELECT Articoli.Titolo, Articoli.TESTO, Articoli.Data, Blog.NomeBlog, Multimedia.Nome FROM Articoli, Blog, Multimedia WHERE Articoli.CodiceArt = Multimedia.CodiceArt && Articoli.Blog = Blog.CodiceBlog && Articoli.Titolo LIKE '%" . $search_articolo . "%'");
        if(mysqli_num_rows($sql_cerca_articolo) > 0){
            echo "<br/><h4>Risultati della tua ricerca</h4>";
            echo "<p style='margin-left:25px;'>Trovate ". mysqli_num_rows($sql_cerca_articolo)." voci per il termine <b>".stripslashes($search_articolo)."</b></p>\n";

            while($row = mysqli_fetch_array($sql_cerca_articolo)) {

                echo '<div class="contenitori"><article>';
                echo "<img src=".$row['Nome'].">";
                echo '<h3>'.$row["Titolo"].'</h3>';
                echo '<p>'.$row["Data"].'</p>';   
                echo '<p>'.$row["TESTO"].'</p></article>';
                echo '<div class="info_blog">';
                echo '<h4>Blog: &nbsp</h4><a href="'.$row["NomeBlog"].'.php">'.$row["NomeBlog"].'</a></div></div>';
                
            }

        }else{
            echo "<br/>Al momento non sono stati trovati articoli con questo nome.";
        }
    }    
?>

