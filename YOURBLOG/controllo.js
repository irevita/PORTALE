function  controllo() {
	if (document.getElementById('password1').value == document.getElementById('password2').value) {
		document.getElementById('message').style.color = 'green';
		document.getElementById('message').innerHTML = 'Password corrispondente';
			return true;
	} else {
		document.getElementById('message').style.color = 'red';
		document.getElementById('message').innerHTML = 'Password non corrispondente';
			return false;
	}
}