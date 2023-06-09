	<!-- partie à mettre dans chaques pages pour interdire l'accès sans identification-->

	<?php
session_start();

if(!isset($_SESSION["role"]) || ($_SESSION["role"] != "jeune" && $_SESSION["role"] != "admin")){
	header("Location: ../login.php");
	exit;
}


if(isset($_POST['deconnexion'])){ // partie pour déconnecter l'utilisateur
	$_SESSION = array();
	session_destroy();
	header("Location: ../home.php");
	exit;
}

$username = $_SESSION["username"];

 // vérification et enregistrement des données
if(isset($_POST['description']) && isset($_POST['name']) && isset($_POST['firstname']) && isset($_POST['email']) && isset($_POST['situation']) && isset($_POST['beginning']) && isset($_POST['duration'])){
	
	if(!(is_numeric($_POST['duration']) && intval($_POST['duration']) > 0)){
        $message = "veuillez entrez une valeur numérique positive";
    }else{
	
	
	require_once '../data.php';
	$beginning = htmlspecialchars($_POST['beginning'], ENT_QUOTES, 'UTF-8');// échapper les caractères spéciaux pour éviter les injections de code
	$duration = htmlspecialchars($_POST['duration'], ENT_QUOTES, 'UTF-8');
	$description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
	$environement = htmlspecialchars($_POST['environement'], ENT_QUOTES, 'UTF-8');
	$durationType = $_POST['durationType'];//pas besoin de vérifier les données natives
	$socialSkills = $_POST['socialSkills'];
	$savoir_faire = array_filter($_POST['myTable']); // supprimer les cases vides
	foreach($savoir_faire as $key => $value){
		$savoir_faire[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	}
	$name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
	$firstname = htmlspecialchars($_POST['firstname'], ENT_QUOTES, 'UTF-8');
	$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
	$situation = htmlspecialchars($_POST['situation'], ENT_QUOTES, 'UTF-8');
	$date = date('YmdHis') . $username; // en prévision d'un numéro d'identification (id) de la compétence, qui doit être unique (parmis les id des compétences d'un utilisateur X et ceux des autres utilisateurs)
	$id = hash('sha256', $date); // hashage pour éviter que le username soit identifiable, tout en conservant l'unicité de l'id
	
	$tab = array (// ajouter les données au tableau
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
		'id' => $id,
    );
	$user = $users[$username];
	array_push($user['skills'], $tab);// ajouter le nouveau tableau dans le tableau de l'utilisateur
	$users[$username] = $user;// remplacer le tableau de l'utilisateur par sa nouvelle version dans le tabeau général

	$other[$id] = array( // créer une entrer dans le tableau other pour permettre au référent de confirmer la compétence par la suite
		'user' => $username,
		'status' => 'referent',
	);
	$file = fopen('email.html', 'w'); //on écrit le contenu du mail
	$body = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Demande de validation d\'expérience</title></head>
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
	Bonjour ' . $firstname . " " . $name . ".
	<br><br>
	<br>" . $users[$username]['firstname'] . " " . $users[$username]['name'] . " requiert votre approbation après son travail auprès de vous.
	</div>
	<div>
	<br>Le service <a class='lien' href='http://localhost:8080/home.php'>Jeune 6.4</a> est un projet destiné à aider les jeunes à valoriser leurs expériences. <br> Afin de permettre à " . $users[$username]['firstname'] . " " . $users[$username]['name'] . " de valider son expérience, merci de cliquer sur le lien ci-dessous et de valider ou infirmer les données entrées.
	<br><i><a href='http://localhost:8080/referent/confirm.php?id=" . $id . "'>confirmer une experience</a></i>
	</div>
	<div>
	<br>
	<br>Bien cordialement,
	<br><b>SERVICE JEUNE 6.4</b>
	<div/></div></body></html>";
	fwrite($file, $body);// on écrit le mail dans la page avant d'aller dessus, de là bas on ouvrira un nouvel onglet pour faire revenir le jeune à skills.php
	fclose($file);	


	$file = fopen('../data.php', 'w');//on met à jour le fichier data
	fwrite($file, '<?php $users = ' . var_export($users, true) . '; $other = ' . var_export($other, true) . '; ?>');
	fclose($file);
	header("Location: email.html");
	exit();
}}
?>



<html>
<head>
	<title>Jeunes 6.4</title>
	<link rel="icon" type="image/x-icon" href="../images/favicon.ico">
	<link rel="stylesheet" href="newExperience.css">
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
	<form method="POST"> <!-- formulaire pour récupérer les données-->
		<table class="description">
			<tr><td>Description :</td><td><input type="text" name="description" class="long" maxlength="100" required> ex: agent d'accueil, aide à domicile</td></tr>
			<tr><td>Nom de la structure : </td><td><input type="text" name="environement" class="long" maxlength="50" required> ex: nom de l'entreprise, de l'association</td></tr>
			<tr><td>Début :</td><td><input type="date" name="beginning" required></td></tr>
			<tr><td>Durée :</td><td><input type="number" name="duration" required> <select name="durationType"><?php if(isset($message)){echo $message;}?>
				<option>jours</option>
				<option>semaines</option>
				<option>mois</option>
				<option>années</option>
			</select></td></tr>
		</table>
		<br>
		<div id="global">	
			<div id="first"><p class="underline">Mes savoir-faire :</p>
				<table id="myTable" class="faire"><!-- tableau pour les savoir faire-->
					<tr><td><input type="text" name="myTable[]" class="long" maxlength="100"></td></tr>
				</table>
				<input type="button" onclick="addRow()" value="Ajouter un savoir-faire"> <!-- fonctions pour ajouter ou supprimer une ligne-->
				<input type="button" onclick="deleteRow()" value="Effacer un savoir-faire">
				<br><br>
	
				<table>	
					<tr><td class="underline">Information sur le référent</tr></td>
					<tr>
						<td>Nom :</td>
						<td><input type="text" name="name" maxlength="50" required></td>
					</tr>
					<tr>
						<td>Prénom :</td>
						<td><input type="text" name="firstname" maxlength="50" required></td>
					</tr>
					<tr>
						<td>Email :</td>
						<td><input type="email" name="email" maxlength="50" required></td>
					</tr>
					<tr>
						<td>Poste/situation :</td>
						<td><input type="text" name="situation" maxlength="50" required></td>
					</tr>
				</table>
			</div>
			<div id="second">
			<table>
				<tr><td id="etre">Mes savoirs-être :</td></tr> <!-- tableau pour ranger les différents savoirs-être-->
				<tr><td id="je_suis" colspan="2">Je suis*</td></tr>
				<tbody id="savoir_etre">
				
					<tr>
						<td>
							<label class="container"> Fiable
								<input type="checkbox" name="socialSkills[]" value="Fiable" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> Déterminé
								<input type="checkbox" name="socialSkills[]" value="Déterminé" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label class="container"> Autonome
								<input type="checkbox" name="socialSkills[]" value="Autonome" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> Ouvert d'esprit
								<input type="checkbox" name="socialSkills[]" value="Ouvert d'esprit" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label class="container"> Réfléchie
								<input type="checkbox" name="socialSkills[]" value="Réfléchie" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> Honnête
								<input type="checkbox" name="socialSkills[]" value="Honnête" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label class="container"> Passionné
								<input type="checkbox" name="socialSkills[]" value="Passionné" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> Ponctuel
								<input type="checkbox" name="socialSkills[]" value="Ponctuel" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label class="container"> A l'écoute
								<input type="checkbox" name="socialSkills[]" value="A l'écoute" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> Respectueux
								<input type="checkbox" name="socialSkills[]" value="Respectueux" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label class="container"> Organisé
								<input type="checkbox" name="socialSkills[]" value="Organisé" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> Collaboratif
								<input type="checkbox" name="socialSkills[]" value="Collaboratif" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label class="container"> Patient
								<input type="checkbox" name="socialSkills[]" value="Patient" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> Proactif
								<input type="checkbox" name="socialSkills[]" value="Proactif" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label class="container"> Responsable
								<input type="checkbox" name="socialSkills[]" value="Responsable" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> Diplomate
								<input type="checkbox" name="socialSkills[]" value="Diplomate" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label class="container"> Optimiste
								<input type="checkbox" name="socialSkills[]" value="Optimiste" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> Curieux
								<input type="checkbox" name="socialSkills[]" value="Curieux" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label class="container"> Communicatif
								<input type="checkbox" name="socialSkills[]" value="Communicatif" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> Empathique
								<input type="checkbox" name="socialSkills[]" value="Empathique" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
					</tr>
				</tbody>
					<tr><td id="asterisque">Faire 4 choix maximum</td></tr>
				</table>
			</div>
		</div>
		<button type="submit" class="confirm">Enregistrer</button>
	</form>
	<br><br><br><br><br>	
	
	
	<script src="newExperience.js"></script>
	
</body>
<?php include_once "../footer.html"; ?>
</html> 