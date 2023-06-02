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



if(isset($_POST['select']) && isset($_POST['option'])){
	echo "test:" . $_POST['option'];
	$option = $_POST['option'];
	$nbrskill = 0;
	if($option == "consultant"){
		$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');// échapper les caractères spéciaux
		$date = date('YmdHis') . $username; // en prévision d'un numéro d'identification (id) de la compétence, qui doit être unique (parmis les id des compétences d'un utilisateur X et ceux des autres utilisateurs)
		$id = hash('sha256', $date); // hashage pour éviter que le username soit identifiable, tout en conservant l'unicité de l'id
		echo "_" . $_POST['option_vue'] . "_";
		if($_POST['option_vue'] != "all"){
        	foreach($_POST['skills'] as $checkbox){
        	    $id_skill = $_POST[$checkbox]; // récupérer l'id de la compétence pour pouvoir vérifier qu'elle existe toujours lors de la consultation
				$skills[$checkbox] = $id_skill;
        	}

			$other[$id] = array(
				'user' => $username,
				'status' => 'consultant',
				'email' => $email,
				'skills' => $skills,
			);
		}else{
			$other[$id] = array(
				'user' => $username,
				'status' => 'consultant',
				'email' => $email,
				'skills' => 'all',
			);
		}

		if($other[$id]['skills'] == NULL){
			$message = "A moins de choisir de séléctionnez toutes les compétences, y compris celles validées ulterieurement, vous devez séléctionner au moins une compétence.";
		}else{
			$file = fopen('../data.php', 'w');
			fwrite($file, '<?php $users = ' . var_export($users, true) . '; $other = ' . var_export($other, true) . '; ?>');
			fclose($file);

			$file = fopen('mail.html', 'w'); //on écrit le contenu du mail
			$body = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Consultation d\'expérience</title></head>
		<style>
		table[class="bandeau"]{
			width: 100%;
			background-color: lightgray;
			color: white;
		}
		
		.bloc{
			width: 80%;
			padding: 1% 1%;
			margin-top: 8%;
		}

		.lien{
			color: black;
		}
		</style>
		<body><script>window.open("skills.php", "_blank");</script>
		<table class="bandeau">
			<tr>
			<td><img src="../images/logo.svg"><img></a></td>
			</tr>
		</table>
		<div class="bloc">
		<div>
		Bonjour,
		<br><br>
		<br>' . $users[$username]['firstname'] . " " . $users[$username]['name'] . " vous partage par l'intermédiaire du site <b><a class='lien' href='http://localhost:8080/home.php'>JEUNES 6.4</a></b> une liste de ses compétences, certifiées par des référents externes.
		</div>
		<div>
		<br>Le service Jeune 6.4 est un projet destiné à aider les jeunes à valoriser leurs expériences, et de faciliter leur accès aux entreprises et autres structures où ils pourront trouver du travail.</div><div><br> Merci de prendre quelques instant pour examiner les compétences de  " . $users[$username]['firstname'] . " " . $users[$username]['name'] . ".
		<br><i><a href='http://localhost:8080/consultant/consultation.php?id=" . $id . "'>consulter les experiences</a></i></div>
		<br>
		<div>
		<br>N'hésitez pas à contacter " . $users[$username]['firstname'] . " " . $users[$username]['name'] . " si vous avez des propositions à lui faire.
		<br><br>Bien cordialement,
		<br><b>SERVICE JEUNE 6.4</b>
		</div></div></body></html>";
			fwrite($file, $body);// on écrit le mail dans la page avant d'aller dessus, de là bas on ouvrira un nouvel onglet pour faire revenir le jeune à skills.php
			fclose($file);
			header("Location: mail.html");
			exit;
		}
	}else if($option == "archive"){
		foreach($_POST['skills'] as $checkbox){
            $id_skill = $_POST[$checkbox];
			foreach($users[$username]["skills"] as $num => $skill){
				if($skill["id"] == $id_skill){
					$users[$username]["skills"][$num]["status"] = "archived";
				}
			}
        }
		$file = fopen('../data.php', 'w');
		fwrite($file, '<?php $users = ' . var_export($users, true) . '; $other = ' . var_export($other, true) . '; ?>');
		fclose($file);
	}else{
		$tab = $users[$username];
		$body = '<style>
		table{
			table-layout: fixed;
			width: 100%;
			border-collapse: collapse;
		}
		th, td{
			width: 25%;
			padding-top: 150px;
		}
		tr{
			*/border-style: solid;/*
		}
		</style><body>
		<h2>CV - ' . $tab['firstname'] . ' '  . $tab['name'] . '</h2><br>
		<br>
		<h3>Mes compétences :</h3><table>';
		foreach($tab['skills'] as $skill){
			if($skill['status'] == 'confirmed'){
				$ref = $skill['referent'];
				$date_obj = DateTime::createFromFormat('Y-m-d', $skill['beginning']); // Formater la date au format dd/mm/yyyy
				$date = $date_obj->format('d/m/Y');

				$competence = '<tr><td><h4>' . $skill['environement'] . "</h4>
				description de l'engagement : " . $skill["description"] . "<br>
				début de l'engagement : " . $date . "<br>
				durée de l'engagement : " . $skill["duration"] . " " . $skill["durationType"] . "<br>";
				$competence .= "<h5>Compétences selon moi</h5>";
				if(!empty($skill['socialSkills'])){
					$competence .= "<h5>Savoir-être</h5><ol>";
					foreach($skill["socialSkills"] as $socialSkill){
						$competence .= "<li>" . $socialSkill . "</li>";
					}
					$competence .= "</ol>";
				}else{
					$competence .= "<h5>Compétences : savoir-être</h5><br>aucun savoir-être mentionné";
				}
				if(!empty($skill['savoir-faire'])){
					$competence .= "<h5>Savoir faire</h5><ol>";
					foreach($skill["savoir-faire"] as $savoir_faire){
						$competence .= "<li>" . $savoir_faire . "</li>";
					}
					$competence .= "</ol>";
				}else{
					$competence .= "<h5>Compétences : savoir faire</h5><br>aucun savoir-faire mentionné";
				}
				$competence .= "</td><td><h5>Référent</h5>";
				$competence .= $ref["firstname"] . " " . $ref["name"] . "<br><br>";
				$competence .= $ref["email"] . "<br><br>";
				$competence .= $ref["situation"] . "<br>";
				$competence .= "<h5>Compétences selon le référent</h5>";
				if(!empty($skill['socialSkills'])){
					$competence .= "<h5>Savoir-être</h5><ol>";
					foreach($skill["socialSkills"] as $socialSkill){
						$competence .= "<li>" . $socialSkill . "</li>";
					}
					$competence .= "</ol>";
				}else{
					$competence .= "<h5>Compétences : savoir-être</h5><br>aucun savoir-être mentionné";
				}
				if(!empty($skill['savoir-faire'])){
					$competence .= "<h5>Savoir faire</h5><ol>";
					foreach($skill["savoir-faire"] as $savoir_faire){
						$competence .= "<li>" . $savoir_faire . "</li>";
					}
					$competence .= "</ol>";
				}else{
					$competence .= "<h5>Compétences : savoir faire</h5><br>aucun savoir-faire mentionné";
				}
				$competence .= '</td>';
				if($skill["comment"] != ""){
					$competence .= "<td><br><h5>Commentaire du référent</h5><br><p class='comment'>" . $skill["comment"] . "</p><br></td>";
				}
				
				$competence .=  "</tr>";
				$body .= $competence;
			}
		}	
		$body .= '</table><br><br><br>CV généré par via le <a href="http://localhost:8080/jeune6.4.html">le site du projet Jeunes 6.4</a><br></body>';
		$file = fopen('cv.html', 'w');
		fwrite($file, $body);// on écrit le mail dans la page avant d'aller dessus, de là bas on ouvrira un nouvel onglet pour faire revenir le jeune à skills.php
		fclose($file);
		header("Location: cv.html");
		exit;
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
		echo '<br><table><tr class="back">';
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
					echo "</tr><tr class='back'>";
				}
			}
		}
		echo "</tr></table>";
		if($nbrConfirmedSkill == 0){
			echo '<p><br><br>aucune expérience confimée par un référent<br><br><br><br><br><br><br><br><br><br></p>';
		}else{
			echo '<br><br><input type="radio" id="cv" name="option" value="cv" onclick="hide()" required><label for="cv">Générer un CV</label><br>';
			echo '<input type="radio" id="archive" name="option" value="archive" onclick="hide()" required><label for="archive">Archiver ces expériences</label><br>';
			echo '<input type="radio" id="consultant" name="option" value="consultant" onclick="show()" required><label for="consultant">Envoyer à un consultant</label>';
			echo '<div id="email"></div>';// plutôt que de cacher, on enlève completement ou on place l'input mail selon le choix de l'utilisateur, pour éviter des problème avec le "required"
			echo '<br><input type="submit" name="select" value="Valider"><br>';
			if(isset($message)){
				echo "<p class='red'>" . $message . "</p>";
			}
			echo '<br><br><br><br><br><br>';
		}
	?>
	</form>
	<script src="select.js"></script>
</body>
<?php include_once "../footer.html"; ?>
</html>