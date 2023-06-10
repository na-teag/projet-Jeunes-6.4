<!-- partie à mettre dans chaques pages pour interdire l'accès sans identification-->

<?php
session_start();

if(!isset($_SESSION["role"]) || ($_SESSION["role"] != "jeune" && $_SESSION["role"] != "admin")){
	header("Location: ../login.php");
	exit;
}

require '../data.php';
$username = $_SESSION["username"];

if(isset($_POST['deconnexion'])){// partie pour déconnecter l'utilisateur
	$_SESSION = array();
	session_destroy();
	header("Location: ../home.php");
	exit;
}



if(isset($_POST['select']) && isset($_POST['skills'])){ // changer le statuts de la compétence de "archived" à "confirmed"
	foreach($_POST['skills'] as $checkbox){
		$id_skill = $_POST[$checkbox];
		foreach($users[$username]["skills"] as $num => $skill){
			if($skill["id"] == $id_skill){
				$users[$username]["skills"][$num]["status"] = "confirmed";
			}
		}
	}
	$file = fopen('../data.php', 'w');// mettre à jour le fichier data
	fwrite($file, '<?php $users = ' . var_export($users, true) . '; $other = ' . var_export($other, true) . '; ?>');
	fclose($file);
}

if(isset($_POST['delete']) && isset($_POST['skills'])){ // supprimer définitivement la compétence
	foreach($_POST['skills'] as $checkbox){
		$id_skill = $_POST[$checkbox];
		foreach($users[$username]["skills"] as $num => $skill){
			if($skill["id"] == $id_skill){
				unset($users[$username]["skills"][$num]);
			}
		}
	}
	$file = fopen('../data.php', 'w');// mettre à jour le fichier data
	fwrite($file, '<?php $users = ' . var_export($users, true) . '; $other = ' . var_export($other, true) . '; ?>');
	fclose($file);
}

?>

<!-- partie classique de la page-->



<html>
<head>
	<title>Jeunes 6.4</title>
	<link rel="icon" type="image/x-icon" href="../images/favicon.ico">
	<link rel="stylesheet" href="select.css">
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

	<br><br>
	<button type="button" onclick="goToSelect()">gérer les expériences confirmées</button>
	<br>
	<h3>Mes expériences archivées</h3>
	<br>
	<button type="button" onclick="check()">tout cocher</button>
	<button type="button" onclick="uncheck()">tout décocher</button>
	<form method="POST">
	<?php // cette partie est tirée de skills.php
		$username = $_SESSION["username"];
		$nbrConfirmedSkill = 0;
		echo '<br><table class="general"><tr class="back">';
		foreach($users[$username]["skills"] as $key => $skill){ # boucle pour les expériences confirmées
			if($skill['status'] == "archived"){
				$nbrConfirmedSkill++;
				echo '<td class="marge"><input type="checkbox" name="skills[]" value="' . $key . '"><input type="hidden" name="' . $key . '" value="' . $skill["id"] . '"></td><td class="marge"><h4>' . $skill["environement"] . "</h4>description: " . $skill["description"];
				echo '</td><td class="marge">';
				echo "<h4>Référent</h4>";
				echo $skill["referent"]["firstname"] . " " . $skill["referent"]["name"] . "<br>";
				echo $skill["referent"]["email"] . "<br>";
				echo $skill["referent"]["situation"] . "<br>";
				echo "</td>";
				if($nbrConfirmedSkill%1==0){ // nombre de cases max dans une seule ligne
					echo "</tr><tr class='back'>";
				}
			}
		}
		echo "</tr></table>";
		if($nbrConfirmedSkill == 0){
			echo '<p><br><br>aucune expérience archivée<br><br><br><br><br><br><br><br><br></p>';
		}else{
			echo '<br><br>
			<input id="custom" type="submit" name="select" value="Désarchiver ces expériences">
			<br><br><br><br>
			<input id="custom2" type="submit" name="delete" value="effacer définitivement ces expériences">
			<br><br><br><br>';
		}
	?>
	</form>
	<script src="select.js"></script>
</body>
<?php include_once "../footer.html"; ?>
</html>