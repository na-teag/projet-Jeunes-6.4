<!-- partie à mettre dans chaques pages pour interdire l'accès sans identification-->

<?php
session_start();

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "jeune"){
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



if(isset($_POST['select']) && isset($_POST['skills']) && isset($_POST['option'])){
	$option = $_POST['option'];
	$nbrskill = 0;
	if($option == "consultant"){
		$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');// échapper les caractères spéciaux
		$date = date('YmdHis') . $username; // en prévision d'un numéro d'identification (id) de la compétence, qui doit être unique (parmis les id des compétences d'un utilisateur X et ceux des autres utilisateurs)
		$id = password_hash($date, PASSWORD_DEFAULT); // hashage pour éviter que le username soit identifiable, tout en conservant l'unicité de l'id
		
        foreach($_POST['skills'] as $checkbox){
            $id_skill = $_POST[$checkbox]; // récupérer l'id de la compétence pour pouvoir vérifier qu'elle existe toujours lors de la consultation
			$skills[$checkbox] = $id_skill;
        }

		$other[$id] = array(
			'user' => $username,
			'skills' => $skills,
		);

		$file = fopen('../data.php', 'w');
		fwrite($file, '<?php $users = ' . var_export($users, true) . '; $other = ' . var_export($other, true) . '; ?>');
		fclose($file);

		$file = fopen('mail.html', 'w'); //on écrit le contenu du mail
		$body = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Consultation d\'expérience</title></head><body><script>window.open("skills.php", "_blank");</script>
		Bonjour,
		<br>' . $users[$username]['firstname'] . " " . $users[$username]['name'] . " vous partage par l'intermédiaire du site <b>JEUNES 6.4</b> une liste de ses compétences, certifiées par des référents externes.
		<br>Le service Jeune 6.4 est un projet destiné à aider les jeunes à valoriser leurs expériences, et de faciliter leur accès aux entreprises et autres structures où ils pourront trouver du travail. Merci de prendre quelques instant pour examiner les compétences de  " . $users[$username]['firstname'] . " " . $users[$username]['name'] . ".
		<br><i><a href='http://localhost:8080/consultant/consultation.php?id=" . $id . "'>consulter les experiences</a></i>
		<br>
		<br>N'hésitez pas à contacter " . $users[$username]['firstname'] . " " . $users[$username]['name'] . " si vous avez des propositions à lui faire.
		<br><br>Bien cordialement,
		<br><b>SERVICE JEUNE 6.4</b></body></html>";
		fwrite($file, $body);// on écrit le mail dans la page avant d'aller dessus, de là bas on ouvrira un nouvel onglet pour faire revenir le jeune à skills.php
		fclose($file);
		header("Location: mail.html");
		exit;
	}else if($option == "archive"){
		foreach($_POST['skills'] as $checkbox){
            $id_skill = $_POST[$checkbox];
			foreach($users[$username]["skills"] as $num => $skill){
				//echo  $users[$username]["skills"][$num]["id"];
				if($skill["id"] == $id_skill){
					$users[$username]["skills"][$num]["status"] = "archived";
				}
			}
        }
		$file = fopen('../data.php', 'w');
		fwrite($file, '<?php $users = ' . var_export($users, true) . '; $other = ' . var_export($other, true) . '; ?>');
		fclose($file);
	}else{

	}

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
	<button type="button" onclick="goToArchive()">gérer les expériences archivées</button>
	<br>
	<h3>Mes expériences confirmées</h3>
	<br>
	<button type="button" onclick="check()">tout cocher</button>
	<button type="button" onclick="uncheck()">tout décocher</button>
	<form method="POST">
	<?php // cette partie est tirée de skills.php
		$username = $_SESSION["username"];
		$nbrConfirmedSkill = 0;
		echo '<br><table><tr>';
		foreach($users[$username]["skills"] as $key => $skill){ # boucle pour les expériences confirmées
			if($skill['status'] == "confirmed"){
				$nbrConfirmedSkill++;
				echo '<td class="marge"><input type="checkbox" name="skills[]" value="' . $key . '"><input type="hidden" name="' . $key . '" value="' . $skill["id"] . '"></td><td class="marge"><h4>' . $skill["environement"] . "</h4>description: " . $skill["description"];
				echo '</td><td class="marge">';
				echo "<h4>Référent</h4>";
				echo $skill["referent"]["firstname"] . " " . $skill["referent"]["name"] . "<br>";
				echo $skill["referent"]["email"] . "<br>";
				echo $skill["referent"]["situation"] . "<br>";
				echo "</td>";
				if($nbrConfirmedSkill%1==0){ // nombre de cases max dans une seule ligne
					echo "</tr><tr>";
				}
			}
		}
		echo "</tr></table>";
		if($nbrConfirmedSkill == 0){
			echo '<p><br><br>aucune expérience confimée par un référent</p>';
		}else{
			echo '<br><br><input type="radio" id="cv" name="option" value="cv" onclick="hide()" required><label for="cv">Générer un CV</label><br>';
			echo '<input type="radio" id="archive" name="option" value="archive" onclick="hide()" required><label for="archive">Archiver ces expériences</label><br>';
			echo '<input type="radio" id="consultant" name="option" value="consultant" onclick="show()" required><label for="consultant">Envoyer à un consultant</label>';
			echo '<div id="email"></div>';// plutôt que de cacher, on enlève ou on place l'input mail selon le choix de l'utilisateur, pour éviter des problème avec le "required"
			echo '<br><br><input type="submit" name="select" value="Valider"><br><br><br><br><br><br>';
		}
	?>
	</form>
	<script src="select.js"></script>
</body>
</html>