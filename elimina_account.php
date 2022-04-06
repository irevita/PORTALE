<?php include 'connessione.php' ?>
<?php session_start(); ?>
<?php $query_eliminautente = mysqli_query($connessione, "DELETE FROM Utenti WHERE ID_Utente=".$_SESSION['id']); ?>

<?php // bottone elimina php
    if (isset($_POST['delete'])) {
        if ($query_eliminautente) {    
            //eliminazione riuscita 
            header('Location: index.php?action=eliminautente');
        }else {
            // eliminazione non riuscita 
            // $error= mysqli_error($connessione);
            header('Location: area_riservata.php?action=erroreliminaut');
        }
    }
    if (isset($_POST['indietro'])) {
        header('Location: area_riservata.php');
    }
 ?>