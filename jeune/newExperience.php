<!-- partie à mettre dans chaques pages pour interdire l'accès sans identification-->

<?php
session_start();

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "jeune"){
	header("Location: ../login.php");
	exit;
}


if(isset($_POST['deconnexion'])){ // partie pour déconnecter l'utilisateur
	$_SESSION = array();
	session_destroy();
	header("Location: ../home.php");
	exit;
}


 // vérification et enregistrement des données
if(isset($_POST['description'])){
	require_once '../data.php';
	$beginning = $_POST['beginning'];
	$duration = $_POST['duration'];
	$description = $_POST['description'];
	$environement = $_POST['environement'];
	$durationType = $_POST['durationType'];
	$socialSkills = $_POST['socialSkills'];
	$savoir_faire = $_POST['myTable'];
	$savoir_faire = array_filter($savoir_faire); // supprimer les cases vides
	$name = $_POST['name'];
	$firstname = $_POST['firstname'];
	$email = $_POST['email'];
	$situation = $_POST['situation'];
	// ajouter les données au tableau
	$tab = array (
        'referent' => 
        array (
          'name' => $name,
          'firstname' => $firstname,
          'email' => $email,
          'situation' => $situation,
        ),
        'beginning' => $beginning,
        'duration' => $duration,
		'durationType' => $durationType,
        'environement' => $environement,
        'description' => $description,
        'socialSkills' => $socialSkills,
        'savoir-faire' => $savoir_faire,
        'status' => 'toConfirm',
    );
	$user = $users[$_SESSION["username"]];
	array_push($user['skills'], $tab);
	$users[$_SESSION["username"]] = $user;
	//echo '<script>alert("' . print_r($users) . '")</script>';
	$file = fopen('../data.php', 'w');
	fwrite($file, '<?php $users = ' . var_export($users, true) . '; ?>');
	fclose($file);
	header("Location: skills.php");
	exit();
}
?>




<html>
<head>
	<title>Jeunes 6.4</title>
	<link rel="icon" type="image/x-icon" href="../images/favicon.ico">
	<link rel="stylesheet" href="newExperience.css">
	<meta charset="UTF-8">
</head>
<body>
	<script>
		function checkLimite(checkbox){ // vérifier que seulement 4 cases max ont été cochées
		  var checkboxes = document.getElementsByName("socialSkills[]");
		  var nbrChecked = 0;
		  for(var i=0; i<checkboxes.length; i++){
			if(checkboxes[i].checked){
				nbrChecked++;
			}
		  }
		  if(nbrChecked > 4){
			checkbox.checked = false;
			alert("Vous ne pouvez pas cocher plus de 4 cases.");
		  }
		}
	</script>
	<table class="bandeau">
		<tr>
			<td rowspan="2"><a href="../home.php"><img src="../images/logo.svg"><img></a></td>
			<td><h1 id="taille1">JEUNE</h1></td>
		</tr>
		<tr>
			<td><p id="taille2">Je donne de la valeur à mon engagement</p></td>
		</tr>
	</table>
	<div id="bouton">
			<form method="POST">
				<button class="deconnexion" type="submit" name="deconnexion">Me Déconnecter</button>
			</form>
	</div>
	
	<div class="bandeau">
		<ul id="bandeau">
			<li id="bandeau"><a class="jeune" href="skills.php">JEUNE </a></li>
			<li id="bandeau"><a class="referent" href="../referent_info.php" >RÉFÉRENT </a></li>
			<li id="bandeau"><a class="consultant" href="../consultant_info.php">CONSULTANT </a></li>
			<li id="bandeau"><a class="partenaires" href="../partenaires.php" >PARTENAIRES</a></li>
		</ul>
	</div>
	<br>
	<br>
	<br>
	<form method="POST">
		<table>
			<tr><td>description :</td><td><input type="text" name="description" required> ex: agent d'accueil, assistant à domicile pour personne agée</td></tr>
			<tr><td>cadre :</td><td><input type="text" name="environement" required> ex: nom de l'entrprise, - </td></tr>
			<tr><td>début :</td><td><input type="date" name="beginning" required></td></tr>
			<tr><td>durée :</td><td><input type="number" name="duration" required> <select name="durationType">
				<option>jours</option>
				<option>semaines</option>
				<option>mois</option>
				<option>années</option>
			</td></tr>
		</table>
		<table>
			<tr>
				<td>savoir-être :</td>
				<td><input type="checkbox" name="socialSkills[]" value="Fiable" onclick="checkLimite(this)">Fiable</td>
				<td><input type="checkbox" name="socialSkills[]" value="Déterminé" onclick="checkLimite(this)">Déterminé</td>
				<td><input type="checkbox" name="socialSkills[]" value="Autonome" onclick="checkLimite(this)">Autonome</td>
				<td><input type="checkbox" name="socialSkills[]" value="Ouvert d'esprit" onclick="checkLimite(this)">Ouvert d'esprit</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="checkbox" name="socialSkills[]" value="Réfléchi" onclick="checkLimite(this)">Réfléchi</td>
				<td><input type="checkbox" name="socialSkills[]" value="Honnête" onclick="checkLimite(this)">Honnête</td>
				<td><input type="checkbox" name="socialSkills[]" value="Passionné" onclick="checkLimite(this)">Passionné</td>
				<td><input type="checkbox" name="socialSkills[]" value="Ponctuel" onclick="checkLimite(this)">Ponctuel</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="checkbox" name="socialSkills[]" value="A l'écoute" onclick="checkLimite(this)">A l'écoute</td>
				<td><input type="checkbox" name="socialSkills[]" value="Respectueux" onclick="checkLimite(this)">Respectueux</td>
				<td><input type="checkbox" name="socialSkills[]" value="Organisé" onclick="checkLimite(this)">Organisé</td>
				<td><input type="checkbox" name="socialSkills[]" value="Collaboratif" onclick="checkLimite(this)">Collaboratif</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="checkbox" name="socialSkills[]" value="Patient" onclick="checkLimite(this)">Patient</td>
				<td><input type="checkbox" name="socialSkills[]" value="Proactif" onclick="checkLimite(this)">Proactif</td>
				<td><input type="checkbox" name="socialSkills[]" value="Responsable" onclick="checkLimite(this)">Responsable</td>
				<td><input type="checkbox" name="socialSkills[]" value="Diplomate" onclick="checkLimite(this)">Diplomate</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="checkbox" name="socialSkills[]" value="Optimiste" onclick="checkLimite(this)">Optimiste</td>
				<td><input type="checkbox" name="socialSkills[]" value="Curieux" onclick="checkLimite(this)">Curieux</td>
				<td><input type="checkbox" name="socialSkills[]" value="Communicatif" onclick="checkLimite(this)">Communicatif</td>
				<td><input type="checkbox" name="socialSkills[]" value="Empathique" onclick="checkLimite(this)">Empathique</td>
			</tr>
		</table>


		<br><p class="marge">Mes savoir-faire</p>
		<table id="myTable">
		</table>
		<input type="button" onclick="addRow()" value="Ajouter un savoir-faire"> 
		<input type="button" onclick="deleteRow()" value="Effacer un savoir-faire">
		
		
		<br><br><br><br>Information sur le référent
		<table>
			<tr>
				<td>Nom :</td>
				<td><input type="text" name="name"></td>
			</tr>
			<tr>
				<td>Prénom :</td>
				<td><input type="text" name="firstname"></td>
			</tr>
			<tr>
				<td>Email :</td>
				<td><input type="text" name="email"></td>
			</tr>
			<tr>
				<td>Poste/situation :</td>
				<td><input type="text" name="situation"></td>
			</tr>
		</table>
		<br>
		<p class="note">note : lorsque vous arriverez sur la page d'accueil,<br>il vous faudra actualiser la page pour charger les données saisies.</p>
		<button type="submit">Enregistrer</button>
	</form>
	<br><br><br><br><br>	
	
	
	<script src="newExperience.js"></script>
	
</body>
</html> 