<?php include 'connessione.php' ?>
<?php session_start(); ?>

<?php

if(isset($_POST['login'])){
// echo "tutto ok";
// }else {
//   echo "non va";
  $username = $_POST['username'];
  $password =$_POST['password'];

  $username = mysqli_real_escape_string($connessione, $username);
  $password = mysqli_real_escape_string($connessione, $password);

  $query = "SELECT ID_Utente, Nick, PasswordID FROM Utenti WHERE Nick = '{$username}'";

  $trovaUtente = mysqli_query($connessione, $query);

  if(!$trovaUtente){
      die("Richiesta fallita".mysqli_error($connessione));
  }

  while ($row = mysqli_fetch_array($trovaUtente)) {

    $idUtente = $row["ID_Utente"];
    $userUtente = $row["Nick"];
    $passUtente = $row["PasswordID"];

  }

  if ($username === $userUtente && $password === $passUtente ){

    $_SESSION['utente'] = $userUtente;
    $_SESSION['id']=$idUtente;
    header("Location: area_riservata.php");
  }else{
    header("Location: index.php");
  }
}

 ?>
