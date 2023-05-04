<!-- partie à mettre dans chaques pages pour interdire l'accès sans identification-->

<?php
session_start();

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "jeune"){
	header("Location: ../login.php");
	exit;
}

if(isset($_POST['deconnexion'])){
	$_SESSION = array();
	session_destroy();
	header("Location: ../home.php");
	exit;
}
?>

<!-- partie classique de la page-->



<html>
<head>
	<title>Jeunes 6.4</title>
	<link rel="icon" type="image/x-icon" href="../images/favicon.ico">
	<link rel="stylesheet" href="newExperience.css">
	<meta charset="UTF-8">
</head>
<body>
	<script>
		function checkLimite(checkbox){
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
		</table>
		<table>
			<tr><td>durée :</td><td><input type="number" name=durationDay min="0"> jours</td><td> *remplissez un des trois champs</td></tr>
			<tr><td></td><td><input type="number" name=durationWeek min="0"> semaines</td></tr>
			<tr><td></td><td><input type="number" name=durationMonth min="0"> mois</td></tr>
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
		<table> <!-- partie pour écriture dynamique de savoir faire -->
			<tr><td></td></tr>
		</table>
		<table><!-- partie pour informations référent -->
			<tr><td></td></tr>
			<tr><td colspan="3"><button type="submit">Enregistrer</button><td></tr>
		</table>
	</form>
		
	
	
</body>
</html>