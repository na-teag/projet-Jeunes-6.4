function password(){ // afficher le formulaire pour le changement de mot de passe
	document.getElementById('main').innerHTML = '<form method="POST"><br><br><label>Ancien mot de passe:</label><br><input type="password" name="oldPassword" maxlength="100" required><br><br><label>Nouveau mot de passe:</label><br><input type="password" name="newPassword" maxlength="100" required><br><br><input class="change_yes" type="submit" value="Valider"></form><br><br><input class="cancel" type="button" value="annuler" onclick="redirect()">';
}

function delete_account(){ // afficher la page de confirmation pour la suppression du compte
	document.getElementById('main').innerHTML = '<form method="POST"><br><br><label>Êtres vous certain de vouloir effacer votre compte et toutes les données associées ?</label><br><br><label>Cette action est irréversible</label><br><br><input class="delete_yes" type="submit" name="delete" value="Effacer mon compte"></form><br><br><br><input class="cancel" type="button" value="annuler" onclick="redirect()">';
}

function redirect() { // redirection sur myaccount.php
	window.location.href = "myaccount.php";
}