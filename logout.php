<?php session_start() ?>
<?php 

    $_SESSION['utente'] = null;
    $_SESSION['id'] = null;

    header ("Location: index.php");

  ?>
