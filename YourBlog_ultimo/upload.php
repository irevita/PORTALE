<?php
include('check_session.php');
if(isset($_FILES["file"])) {
if(isset($_FILES["file"]["type"])) {
		$allowedtypes = array ("image/jpeg","image/pjpeg","image/png","image/gif");
		$savefolder = "images";
	if(($_FILES["file"]["size"] < 2000000) && in_array ($_FILES['file']['type'], $allowedtypes)) {
		if($_FILES["file"]["error"] > 0){
			echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
		}
		else{
			if(file_exists("images/" . $_FILES["file"]["name"])) {
				echo $_FILES["file"]["name"] . " <span id='invalid'><b>Immagine esistente.</b></span> ";
		} else{
			$thefile = $savefolder . "/" . $_FILES['file']['name'];
			move_uploaded_file($_FILES['file']['tmp_name'], $thefile);
			echo "<span id='success'>Immagine caricata con successo...!!</span><br/>";
			echo "<br/><b>Nome File:</b> " . $_FILES["file"]["name"] . "<br>";
			echo "<b>Tipo:</b> " . $_FILES["file"]["type"] . "<br>";
			//print_r($_FILES);
			$_SESSION["nomefile"] = $thefile;
			//$image = $_FILES["file"]["name"];
		}
		}

	}
	else{
		echo "<span id='invalid'>Tipo o dimensione immagine non validi.<span>";
	}

}
} 
?>
