<?php include 'connessione.php' ?>
<?php session_start(); ?>

<?php

if(isset($_POST['login'])){
  
  $username = $_POST['username'];
  $password =$_POST['password'];
  
  if (!preg_match('/^[a-z0-9_-]{3,16}$/', $password)){
    die("Lo username non è valido"); 
  }
   
  if (!preg_match('/^[a-zA-Z0-9_-]{4,18}$/', $password)){
    die("La password non è valida"); 
  }

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
    $avviso = "Password o nickname errati";
    echo "<script type='text/javascript'>alert('$avviso');window.location.replace(\"index.php\");</script>";
    
  }
}

 ?>
