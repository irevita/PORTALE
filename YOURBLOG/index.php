<?php
include('check_session.php');
	if (isset($_SESSION['id_utente'])) { $menu = 1; }
	else { $menu = 2; }
?>
<!DOCTYPE html>
<html lang = "it">
<head>
	<title> Home Page </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="style_index.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>
<body>
	<div class ="container">
	<header>
		<?php include('header.php'); ?>
	</header>
	  <div class="form">
		<h2 class="text-center">Utenti registrati al Blog</h2>
			<?php
				require('connect.php');
				$query = "SELECT username, data_registrazione FROM Utente ORDER BY data_registrazione ASC ";
				$result = mysqli_query($mysqli, $query);
				if(mysqli_num_rows($result) > 0){
					echo '<table class="table table-striped">
							<tr>
								<th scope="col">Username</th>
								<th scope="col">Data registrazione</th>
							</tr>'; 
					while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						echo '<tr><td>' . $row['username'] . '</td><td>' . $row['data_registrazione'] . '</td></tr>';
					}
				echo '</table>';
				mysqli_free_result ($result);
				} else {
					echo '<p class="error">Nessun utente registrato al blog.</p>';
					// echo '<p>' . mysqli_error($mysqli) . '<br><br>Query: ' . $q . '</p>';
				}
				mysqli_close($mysqli);
			?>
      <br><br>
       <div>
        <span class="input-group-addon">Cerca Blog tramite l'utente:</span> <br><br>
        <input type="text" name="search_text2" id="search_text2" placeholder="Scrivi qui" class="form-control" />
      </div>
      <br />
      <div id="result2"></div>

    <br><br>
      <div>
        <span class="input-group-addon">Cerca Blog tramite la categoria:</span> <br><br>
        <input type="text" name="search_text1" id="search_text1" placeholder="Scrivi qui" class="form-control" />
      </div>
      <br />
      <div id="result1"></div>

		<br><br>
  		<div>
    		<span class="input-group-addon">Cerca Blog tramite il titolo:</span> <br><br>
    		<input type="text" name="search_text" id="search_text" placeholder="Scrivi qui" class="form-control" />
  		</div>
  		<br />
  		<div id="result"></div>
    <?php
      
      require('connect.php');
        
      //Mostro tutti i blog ordinati in modo discendente in base alla data di inserimento
      $sql = "SELECT id_blog, autore, username,Categoria.nomeC, titolo, Blog.categoria, descrizione, data_blog, Blog.immagine
      FROM Blog, Utente, Categoria
      WHERE Blog.autore=Utente.id_utente and Blog.categoria = Categoria.id_categoria ORDER BY data_blog DESC LIMIT 10 ";
      $results = mysqli_query($mysqli, $sql);
      
      //Controllo se sono presenti blog 
      if(mysqli_num_rows($results)>0){
        echo'<h3 style="text-align:center;">Visualizza i blog del sito</h3>';
      while($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
        
        
        
        echo '
        <div class="blogcontainer">
          <img class="imgBlog" src='.$row["immagine"].' width="50%" height="200">
          <h4 class="card-title">Titolo: '.$row["titolo"].'</h4>
          <p class="card-subtitle">Autore: '.$row["username"].'</a></p>
          <p class="card-subtitle mb-2 text-muted">Categoria: '.$row["nomeC"].'</p>
          <p class="card-subtitle mb-2 text-muted">Pubblicato il: '.$row["data_blog"].'</p>
          <a href="apertura_blog.php?id=' . $row["id_blog"].'" id = "leggi">Vai al Blog </a>';
        echo'</div>';

          }
    } else{
             echo "Nessun blog presente";
    }
    mysqli_close($mysqli);
    
    ?>
	</div>
	</div>
  <footer>
    <div id="footerlogin">
      <p>Realizzato da Triolo Gabriele e Giurdanella Chiara</p>
    </div>
  </footer>
</body>
</html>


<script>
$(document).ready(function(){
  load_data();
  function load_data(query)
  {
    $.ajax({
      url:"search_blog.php",
      method:"post",
      data:{query:query},
      success:function(data)
      {
        $('#result').html(data);
      }
    });
  }
  
  $('#search_text').keyup(function(){
    var search = $(this).val();
    if(search != '')
    {
      load_data(search);
    }
    else
    {
      load_data();      
    }
  });
});
</script>

<script>
$(document).ready(function(){
  load_data();
  function load_data(query)
  {
    $.ajax({
      url:"search_categoria.php",
      method:"post",
      data:{query:query},
      success:function(data)
      {
        $('#result1').html(data);
      }
    });
  }
  
  $('#search_text1').keyup(function(){
    var search = $(this).val();
    if(search != '')
    {
      load_data(search);
    }
    else
    {
      load_data();      
    }
  });
});
</script>

<script>
$(document).ready(function(){
  load_data();
  function load_data(query)
  {
    $.ajax({
      url:"search_utente.php",
      method:"post",
      data:{query:query},
      success:function(data)
      {
        $('#result2').html(data);
      }
    });
  }
  
  $('#search_text2').keyup(function(){
    var search = $(this).val();
    if(search != '')
    {
      load_data(search);
    }
    else
    {
      load_data();      
    }
  });
});
</script>













