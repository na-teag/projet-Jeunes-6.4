<!-- partie à mettre dans chaques pages pour interdire l'accès sans identification-->

<?php
session_start();

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "jeune"){
    header("Location: ../login.php");
    exit;
}

if(isset($_POST['deconnexion'])){// partie pour déconnecter l'utilisateur
    $_SESSION = array();
    session_destroy();
    header("Location: ../home.php");
    exit;
}
if(isset($_POST['newExperience'])){
    header("Location: newExperience.php");
    exit;
}
if(isset($_POST['select'])){
    header("Location: select.php");
    exit;
}
if(isset($_POST['account'])){
    header("Location: myaccount.php");
    exit;
}
if(isset($_POST['consult'])){
    header("Location: myConsultations.php");
    exit;
}
?>

<!-- partie classique de la page-->



<html>
<head>
	<title>Jeunes 6.4</title>
	<link rel="icon" type="image/x-icon" href="../images/favicon.ico">
	<link rel="stylesheet" href="skills.css">
	<meta charset="UTF-8">
</head>
<body>
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
	
	<div class="navbar">
		<ul>
			<li id="bandeau"><a class="jeune" href="skills.php">JEUNE </a></li>
			<li id="bandeau"><a class="referent" href="../referent_info.php" >RÉFÉRENT </a></li>
			<li id="bandeau"><a class="consultant" href="../consultant_info.php">CONSULTANT </a></li>
			<li id="bandeau"><a class="partenaires" href="../partenaires.php" >PARTENAIRES</a></li>
		</ul>
	</div>
	<br>
	<br>
	<div id="bouton">
			<form method="POST">
				<button class="newExperience" type="submit" name="newExperience">Ajouter une expérience</button>
			</form>
			<form method="POST">
				<button class="select" type="submit" name="select">gérer mes expériences</button>
			</form>
			<form method="POST">
				<button class="select" type="submit" name="consult">gérer les consultation de mes expériences</button>
			</form>
			<form method="POST">
				<button class="account" type="submit" name="account">mon profil</button>
			</form>
	</div>
	
	
	<div class="liste">
		<?php
			$username = $_SESSION["username"];
			require '../data.php';
			if(!empty($users[$username]['skills'])){
				$nbrConfirmedSkill = 0;
				echo '<br><h2>Mes expériences confirmées</h2><table><tr>';
				foreach($users[$username]["skills"] as $skill){ # boucle pour les expériences confirmées
					if($skill['status'] == "confirmed"){
						$nbrConfirmedSkill++;
						echo '<td class="marge"><h3>' . $skill["environement"] . "</h3><ul><li>description: " . $skill["description"] . "</li><li>début: " . $skill["beginning"] . "</li><li> durée: " . $skill["duration"] . " " . $skill["durationType"] . "</li></ul>";
						if(!empty($skill['socialSkills'])){
							echo "<h5>Compétences : savoir-être</h5><ol>";
							foreach($skill["socialSkills"] as $socialSkill){
								echo "<li>" . $socialSkill . "</li>";
							}
							echo "</ol>";
						}else{
							echo "<h5>Compétences : savoir-être</h5><br>aucun savoir-être mentionné";
						}
						if(!empty($skill['savoir-faire'])){
							echo "<h5>Compétences : savoir faire</h5><ol>";
							foreach($skill["savoir-faire"] as $savoir_faire){
								echo "<li>" . $savoir_faire . "</li>";
							}
							echo "</ol>";
						}else{
							echo "<h5>Compétences : savoir faire</h5><br>aucun savoir-faire mentionné";
						}
						echo '</td><td class="marge">';
						echo "<h4>Référent</h4>";
						echo $skill["referent"]["firstname"] . " " . $skill["referent"]["name"] . "<br>";
						echo $skill["referent"]["email"] . "<br>";
						echo $skill["referent"]["situation"] . "<br>";
						if($skill["comment"] != ""){
							echo "<br><h5>Commentaire du référent</h5><br>" . $skill["comment"] . "<br>";
						}
						echo "</td>";
						if($nbrConfirmedSkill%1==0){ // nombre de cases max dans une seule ligne
							echo "</tr><tr>";
						}
					}
				}
						echo "</tr></table>";
				if($nbrConfirmedSkill == 0){
					echo '<p><br><br>aucune expérience confimée par un référent</p>';
				}


				$nbrToConfirmSkill = 0;
				echo '<br><h2>Mes expériences non confirmées</h2><table><tr>';
				foreach($users[$username]["skills"] as $skill){ # boucle pour les expériences non confirmées
					if($skill['status'] == "toConfirm"){
						$nbrToConfirmSkill++;
						echo '<td class="marge"><h3>' . $skill["environement"] . "</h3><ul><li>description: " . $skill["description"] . "</li><li>début: " . $skill["beginning"] . "</li><li>durée: " . $skill["duration"] . " " .  $skill["durationType"] . "</li></ul>";
						if(!empty($skill['socialSkills'])){
							echo "<h5>Compétences : savoir-être</h5><ol>";
							foreach($skill["socialSkills"] as $socialSkill){
								echo "<li>" . $socialSkill . "</li>";
							}
							echo "</ol>";
						}else{
							echo "<h5>Compétences : savoir-être</h5><br>aucun savoir-être mentionné";
						}
						if(!empty($skill['savoir-faire'])){
							echo "<h5>Compétences : savoir faire</h5><ol>";
							foreach($skill["savoir-faire"] as $savoir_faire){
								echo "<li>" . $savoir_faire . "</li>";
							}
							echo "</ol>";
						}else{
							echo "<h5>Compétences : savoir faire</h5><br>aucun savoir-faire mentionné";
						}
						echo '</td><td class="marge">';
						echo "<h4>Référent</h4>";
						echo $skill["referent"]["firstname"] . " " . $skill["referent"]["name"] . "<br>";
						echo $skill["referent"]["email"] . "<br>";
						echo $skill["referent"]["situation"];
						echo "</td>";
						if($nbrToConfirmSkill%1==0){ // nombre de cases max dans une seule ligne
							echo "</tr><tr>";
						}
					}
				}
						echo "</tr></table>";
				if($nbrToConfirmSkill == 0){
					echo '<p><br><br>aucune expérience non confirmée</p>';
				}

			}else{
				echo '<br><br><br><br><br><br><br><br><p>aucune expérience enregistrée</p>';
			}
			$oldusers = $users;
		?>
	</div>
	
	
</body>
</html> 