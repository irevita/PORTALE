<?php 
if (!isset($_SESSION['id_utente'])) {
  header("Location: login.php");
  exit();
}
$menu = 1;

require_once ("connect.php");

if(empty($_POST['comment'])) {
  $errors[] = 'Inserire un commento.';
}else {

  if(isset($_POST['postcomment'])) {

  $comment = Trim(stripslashes($_POST['comment']));
  $id_utente = $_SESSION["id_utente"];
  $id_post = $_POST["id_post"];
  $blog = $_POST["blog"];


  $query = "INSERT INTO Commento (id_commento, utente, post, testo, data_commento)";
  $query .= "VALUES(' ', ?, ?, ?, NOW() )";
  $q = mysqli_stmt_init($mysqli);
  mysqli_stmt_prepare($q, $query);
  mysqli_stmt_bind_param($q, 'iis', $id_utente, $id_post, $comment);
  mysqli_stmt_execute($q);
    if (mysqli_stmt_affected_rows($q) == 1) {
      header("Location: apertura_post.php?id=".$id_post."&blog=".$blog."");
    }
  mysqli_stmt_close($q);
  }
}

if (!empty($errors)) {
  $errorstring = "Errore! <br /> Ecco i seguenti errori(e):<br>";
  foreach ($errors as $msg) {
    $errorstring .= " - $msg<br>\n";
  }
  $errorstring .= "Riprova!<br>";
  echo "<p style = 'color:red'>$errorstring</p>";
}
?>
      






