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
	$option = $_POST['option'];
	$nbrskill = 0;
	if($option == "consultant"){
		$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');// échapper les caractères spéciaux
		$date = date('YmdHis') . $username; // en prévision d'un numéro d'identification (id) de la compétence, qui doit être unique (parmis les id des compétences d'un utilisateur X et ceux des autres utilisateurs)
		$id = hash('sha256', $date); // hashage pour éviter que le username soit identifiable, tout en conservant l'unicité de l'id
		if($_POST['option_email'] != "all"){
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

		if($other[$id]['skills'] == NULL){ // si aucune compétence séléctionné
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
		$nbr = 0;
		foreach($_POST['skills'] as $checkbox){// pour chaque compétence séléctionnée
			$nbr++;
            $id_skill = $_POST[$checkbox];
			foreach($users[$username]["skills"] as $num => $skill){// on cherche la compétence correspondante dans le tableau et on change son statut
				if($skill["id"] == $id_skill){
					$users[$username]["skills"][$num]["status"] = "archived";
				}
			}
        }
		if($nbr!=0){
			$file = fopen('../data.php', 'w'); // on actualise le fichier data
			fwrite($file, '<?php $users = ' . var_export($users, true) . '; $other = ' . var_export($other, true) . '; ?>');
			fclose($file);
		}else{
			$message = "Aucune compétence séléctionnée";
		}
		
	}else{
		$nbr = 0;
		foreach($_POST['skills'] as $checkbox){// on compte le nombre de compétence séléctionnée
			$nbr++;
        }
		if($nbr==0){
			$message = "Aucune compétence séléctionnée";
		}else{
			$tab = $users[$username]; // on écrit le contenu du fichier cv.html
			$body = '<style>
			table[class="bandeau"]{
				width: 100%;
				background-color: lightgray;
				color: white;
			}
			
			.deconnexion{
				position:relative;
				float: right;
				margin-right: 2%;
				cursor: pointer;
			}
			
			li[id="bandeau"]{
				padding: 10px;
				background-color: lightgray;
				display: inline;
			}
			div[class="navbar"]{
				text-align: center;
				margin-left: 2%;
			}
			input[type="checkbox"]{
				pointer-events: none;
			}
			
			a{ 
			 text-decoration:none; 
			}
			
			li[class="jeune"]{
				background-color: rgb(240, 93, 106);
			}
			
			
			#taille1{
				color: rgb(240, 93, 106);
				font-size: 500%;
				position:relative;
				float: right;
				padding-right: 50px;
			}
			#taille2{
				font-size: 250%;
				float: right;
				padding-bottom: 10px;
				margin-right: 50px;
			}
			
			.jeune{
				background-color: rgb(240, 93, 106);
				color: white;
			}
			.referent{
				color: green;
			}
			.consultant{
				color: rgb(71, 141, 255);
			}
			.partenaires{
				color: rgb(90, 93, 95);
			}
			.center{
				text-align: center;
			}
			.back{
				background-color: white;
			}
			.liste{
				margin-left: 50px;
				text-align: center;
				
			}
			.comment{
				overflow-wrap: break-word;
				max-width: 325px;
				border: 2px solid yellowgreen;
				padding: 1%;
			}
			
			#button{
				padding-top: 10px;
			}
			
			.marge_bottom{
				margin-bottom: 70px;
			}
			
			/* -------------------------------------------------------------------------------------------*/
			.general{
				border-spacing: 20px 150px;
			}
			
			#jeune{
				border: solid rgb(240, 93, 106);
				min-width: 40%;
				max-width: 50%;
				min-height: 20%;
				max-height: 30%;
			}
			#jeune_toConfirm{
				border: solid rgb(240, 93, 106);
				width: 90%;
				padding: 2%;
				padding-left: 8%;
				margin-bottom: 50%;
			}
			
			#referent{
				border: solid yellowgreen;
				padding: 1%;
				min-width: 40%;
				max-width: 50%;
				min-height: 20%;
				max-height: 30%;
			}
			
			#global_1{
				margin-left: 5%;
				display: flex;
				flex-direction: row;
				max-width: 90%;
			}
			#section_1{
				min-width: 45%;
				max-width: 55%;
			}
			#section_2{
				margin-left: 20%;
				max-width: 80%;             /*commande max-width*/
			}
			#global{
				display: flex;
				flex-direction: row;
				width: 100%;
			}
			
			#first{
				min-width: 45%;
				max-width: 55%;
			}
			#second{
				margin-left: 10%;
			}
			.titre{
				margin-left: 5%;
				color: deeppink;
			}
			.titre_ref{
				margin-left: 5%;
				color: green;
			}
			.titre_toConfirm{
				color: deeppink;
			}
			
			.do_ted{
				border-bottom: 2px dotted rgb(240, 93, 106);
			}
			.do_ted_green{
				border-bottom: 2px dotted yellowgreen;
			}
			#savoir{
				color: deeppink;
			}
			#je_suis{
				background-color: deeppink;
			}
			#colored{
				background-color: pink;
			}
			#green{
				color: green;
			}
			#back_green{
				background-color: green;
			}
			#back_table{
				background-color: rgba(196, 228, 133, 0.842);
			}
			
			/*------------------------------------------*/
			
			/* The container */
			.container {
				display: block;
				position: relative;
				padding-left: 35px;
				margin-bottom: 12px;
				cursor: pointer;
				font-size: 17px;
				-webkit-user-select: none;
				-moz-user-select: none;
				-ms-user-select: none;
				user-select: none;
				pointer-events: none;
			  }
			  
			  /* Hide the browser"s default checkbox */
			  .container input {
				position: absolute;
				opacity: 0;
				cursor: pointer;
				height: 0;
				width: 0;
			  }
			  
			  /* Create a custom checkbox */
			  .checkmark {
				position: absolute;
				top: 0;
				left: 0;
				height: 25px;
				width: 25px;
				background-color: #eee;
			  }
				/* Create a custom checkbox for referent */
				.checkmark_2 {
					position: absolute;
					top: 0;
					left: 0;
					height: 25px;
					width: 25px;
					background-color: #eee;
				  }
			  /* When the checkbox is checked, add a blue background */
			  .container input:checked ~ .checkmark_2 {
				background-color: rgb(12, 170, 12);
			  }
			  .container input:checked ~ .checkmark {
				background-color: deeppink;
			  }
			  
			  /* Create the checkmark/indicator (hidden when not checked) */
			  .checkmark:after {
				content: "";
				position: absolute;
				display: none;
			  }
			  .checkmark_2:after {
				content: "";
				position: absolute;
				display: none;
			  }
			  
			  /* Show the checkmark when checked */
			  .container input:checked ~ .checkmark:after {
				display: block;
			  }
			  .container input:checked ~ .checkmark_2:after {
				display: block;
			  }
			  
			  /* Style the checkmark/indicator */
			  .container .checkmark:after {
				left: 9px;
				top: 5px;
				width: 5px;
				height: 10px;
				border: solid white;
				border-width: 0 3px 3px 0;
				-webkit-transform: rotate(45deg);
				-ms-transform: rotate(45deg);
				transform: rotate(45deg);
			  }
			  .container .checkmark_2:after {
				left: 9px;
				top: 5px;
				width: 5px;
				height: 10px;
				border: solid white;
				border-width: 0 3px 3px 0;
				-webkit-transform: rotate(45deg);
				-ms-transform: rotate(45deg);
				transform: rotate(45deg);
			  }
			
			</style><body>
			<h2>CV - ' . $tab['firstname'] . ' '  . $tab['name'] . '</h2><br>
			<h3>Mes compétences :</h3><table class="general"><tr class="back">';
			foreach($_POST['skills'] as $key){ // pour chaques compétences, on ajoute une case avec toutes les données
				$id_skill = $_POST[$key];
				$skill = $tab["skills"][$key];
				if($skill['id'] == $id_skill && $skill['status'] == 'confirmed'){
					$ref = $skill['referent'];
					$date_obj = DateTime::createFromFormat('Y-m-d', $skill['beginning']); // Formater la date au format dd/mm/yyyy
					$date = $date_obj->format('d/m/Y');
					$competence = "<td id='jeune'><h1 class='titre'>JEUNE</h1><div id='global_1'><div id='section_1'><h4>" . $skill['environement'] . "</h4>
					description de l'engagement :<br>" . $skill["description"] . "<br><br>
					début de l'engagement :<br>" . $date . "<br><br>
					durée de l'engagement :<br>" . $skill["duration"] . " " . $skill["durationType"] . "<br><br>";
					$competence .= "<h5>Compétences selon moi</h5>";
					if(!empty($skill['savoir-faire'])){
						$competence .= "<h5>Savoir faire</h5>";
						foreach($skill["savoir-faire"] as $savoir_faire){
							$competence .= "<p class='do_ted'>" . $savoir_faire . "</p>";
						}
					}else{
						$competence .= "<h5>Compétences : savoir faire</h5><br>aucun savoir-faire mentionné<br>";
					}
					$competence .= "</div>";
					if(!empty($skill['socialSkills'])){
						$competence .= "<div id='section_2'><table id='truc'><tr><td id='savoir'>Mes savoir-être</td></tr>
						<tr><td id='je_suis'>Je suis</td></tr>
						<tbody id='colored'>";
						foreach($skill["socialSkills"] as $socialSkill){
							$competence .= '<tr><td>
							<label class="container">' . $socialSkill . '
								<input type="checkbox" checked>
								<span class="checkmark"></span>
							</label></td></tr>';
						}
						$competence .="</tbody></table>";
					}else{
						$competence .="<div id='section_2'><h4>Compétences : savoir-être</h4><br>aucun savoir-être mentionné";
					}
					$competence .="</div></div>";
					$competence .='</td>';
					/******************************************* PARTIE DU REFERENT ************************************************************/
					$competence .= "<td id='referent'><h1 class='titre_ref'>REFERENT</h1><div id='global_1'><div id='first'><u>";
					$competence .= $ref["firstname"] . " " . $ref["name"] . "<br><br>";
					$competence .= $ref["email"] . "</u><br><br>";
					$competence .= $ref["situation"] . "<br>";
					$competence .= "<h5>Compétences selon le référent</h5>";
					
					if(!empty($skill['savoir-faire_ref'])){
						$competence .= "<h5>Savoir faire</h5>";
						foreach($skill["savoir-faire"] as $savoir_faire){
							$competence .= "<p class='do_ted_green'>" . $savoir_faire . "</p>";
						}
					}else{
						$competence .= "<h5>Compétences : savoir faire</h5><br>aucun savoir-faire mentionné";
					}
					$competence .= "</div><div id='second'>";
					if($skill["comment"] != ""){
						$competence .="<br><h4>Commentaire du référent</h4><p class='comment'>" . $skill["comment"] . "</p><br>";
					}
					if(!empty($skill['socialSkills_ref'])){
						$competence .= "<table id='a'><tr><td id='green'>Ses savoir-être</td></tr>
						<tr><td id='back_green'>Il est</td></tr>
						<tbody id='back_table'>";
						foreach($skill["socialSkills"] as $socialSkill){
							$competence .= '<tr><td>
							<label class="container">' . $socialSkill . '
								<input type="checkbox" checked>
								<span class="checkmark_2"></span>
							</label></td></tr>';
						}
						$competence .= "</tbody></table>";
					}else{
						$competence .= "<div id='second'><h5>Compétences : savoir-être</h5><br>aucun savoir-être mentionné";
					}
					
					$competence .= '</div></div></td>';
					if($nbrConfirmedSkill%1==0){ // nombre de cases max dans une seule ligne
						$competence .= "</tr><tr class='back'>";
					}
					$body .= $competence; // on fusionne le tout pour obtenir le corp entier de la page
				}
				
			}	
			$body .= "</tr></table>";
			$body .= '</table><br><br><br>CV généré par via le site du <a href="http://localhost:8080/jeune6.4.html">projet Jeunes 6.4</a><br></body>';
			$file = fopen('cv.html', 'w'); // on écrit le cv dans cv.html
			fwrite($file, $body);
			fclose($file);


			if($_POST['option_file'] == "html"){// si le format demandé est html
				echo '<script>window.open("cv.html", "_blank");	</script>';
			}else if($_POST['option_file'] == "pdf"){
				$client = $_SERVER['HTTP_USER_AGENT']; // si le format demandé est pdf, on vérifie que le client est sous linux
				function isLinux($client){
					return stripos($client, 'linux') !== false;
				}
				if(!isLinux($client)){
					$message = "Cette option n'est disponible que sous Linux";
				}else{
					$res = shell_exec("bash script.sh"); // si le client est sous linux, on execute le fichier shell
					if($res == ""){
						echo '<script>window.open("cv.pdf", "_blank");	</script>';
					}else{
						$message = $res;// si le logiciel n'est pas installé, on retourne le ma=essage d'erreur
					}
				}
				
			}
		}
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
		echo '<table class="general"><tr class="back">';
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
			echo '<input type="radio" id="cv" name="option" value="cv" onclick="show_file()" required><label for="cv">Générer un CV</label><br>';
			echo '<div id="file"></div>';
			echo '<input type="radio" id="archive" name="option" value="archive" onclick="hide_both()" required><label for="archive">Archiver ces expériences</label><br>';
			echo '<input type="radio" id="consultant" name="option" value="consultant" onclick="show_email()" required><label for="consultant">Envoyer à un consultant</label>';
			echo '<div id="email"></div>';// plutôt que de cacher, on enlève completement ou on place l'input mail selon le choix de l'utilisateur, pour éviter des problèmes avec le "required"
			echo '<br><button type="submit" name="select">Valider</button><br>';
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