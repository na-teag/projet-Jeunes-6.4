function password(){
	document.getElementById('main').innerHTML = '<form method="POST"><br><br><label>Ancien mot de passe:</label><br><input type="password" name="oldPassword" maxlength="100" required><br><br><label>Nouveau mot de passe:</label><br><input type="password" name="newPassword" maxlength="100" required><br><br><input type="submit" value="Valider"></form>';
}

function delete_account(){
	document.getElementById('main').innerHTML = '<form method="POST"><br><br><label>Êtres vous certain de vouloir effacer votre compte et toutes les données associées ?</label><br><label>Cette action est irréversible</label><br><br><input type="submit" name="delete" value="Effacer mon compte"></form><br><br><input type="button" value="annuler" onclick="redirect()">';
}

function redirect() {
	window.location.href = "myaccount.php";
}