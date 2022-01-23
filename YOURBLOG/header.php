<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset ="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	
	
		<h1 style="color:white">
			<?php
			if(!empty($_GET['nome'])) {
				$nome = filter_var( $_GET['nome'], FILTER_SANITIZE_STRING);
				echo $nome;
			}
			else { echo "Your Blog"; }
			?>
		</h1>
	
	<nav>
		<ul> 
		<?php
		switch ($menu) {
			case 1: //vista utenti registrati
		?>
			<li> <a href='index.php?nome=Home Page'> Home page</a></li>
			<li> <a href='lista_blog.php?nome=I Tuoi Blog'> I Tuoi Blog </a></li>
			<li> <a href='blog.php?nome=Crea Blog'> Crea Blog </a></li>
			<li id="profilo"> <a href='profilo.php?nome=Profilo Utente'>Il Mio Profilo</a>
				<ul id="dropdown">
					<li class="elenco"> <a href ='cambia_password.php?nome=Cambia Password'>Cambia Password</a></li>
					<li class="elenco"> <a href ='modifica_utente.php?nome=Modifica Utente'>Modifica Utente</a></li>
					<li class="elenco"> <a href ='elimina_utente.php?nome=Elimina Utente'>Elimina Utente</a></li>
					<li class="elenco"> <a href ='logout.php?nome=Logout'>Logout </a></li>
				</ul>
			</li>
		<?php
		break;
			case 2: //vista utenti non ancora registrati
		?>
			<li> <a href='index.php?nome=Home Page'>Home Page</a></li>
			<li> <a href= 'login.php?nome=Login'>Accedi</a></li>
			<li> <a href= 'registrazione.php?nome=Registrazione'>Registrati</a></li>
			
		<?php
		break;
		}
		?>
		</ul>
	</nav>
	
</body>
</html>