<?php

	session_start();
	$_SESSION = array();
	session_destroy();
	session_start();

	$id = $_GET['id'];
	require_once '../data.php';
	if(!array_key_exists($id, $other)){
		header("Location: ../referent_info.php");
		exit;
	}
	//Seul le référent peut accéder à cet page
	$_SESSION["role"] = "referent";
	$_SESSION["username"] = $id;
	$username = $other[$_SESSION["username"]]['user'];
	
	
	if(isset($_POST['deconnexion'])){ // partie pour déconnecter l'utilisateur
		$_SESSION = array();
		session_destroy();
		header("Location: ../home.php");
		exit;
	}

	$num=0;
	foreach($users[$username]['skills'] as $number => $skill){
		if($skill['id'] == $id){
			$num = $number; // l'id du consultant est iddentique à l'id du skill du jeune qu'il doit confirmer -> on cherche en quel position dans le tableau est le skill à modifier et on le stocke dans $num
		}
	}
	$tab = $users[$username]['skills'][$num];
	

	if(isset($_POST['description']) && isset($_POST['name']) && isset($_POST['firstname']) && isset($_POST['email']) && isset($_POST['situation']) && isset($_POST['beginning']) && isset($_POST['duration'])){
		//vérifie que la durée de l'expérience est numérique et positive
		if(!(is_numeric($_POST['duration']) && intval($_POST['duration']) > 0)){
			$message = "veuillez entrez une valeur numérique positive";
		}else{
			//on récupère les informations de l'expérience du jeune
			$beginning = htmlspecialchars($_POST['beginning'], ENT_QUOTES, 'UTF-8');
			$duration = htmlspecialchars($_POST['duration'], ENT_QUOTES, 'UTF-8');
			$description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
			$environement = htmlspecialchars($_POST['environement'], ENT_QUOTES, 'UTF-8');
			$durationType = $_POST['durationType'];//pas besoin de vérifier les données natives
			$socialSkills = $_POST['socialSkills'];
			print_r($socialSkills);
			$savoir_faire = array_filter($_POST['myTable']); // supprimer les cases vides
			foreach ($savoir_faire as $key => $value) {
				$savoir_faire[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
			}
			$name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
			$firstname = htmlspecialchars($_POST['firstname'], ENT_QUOTES, 'UTF-8');
			$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
			$situation = htmlspecialchars($_POST['situation'], ENT_QUOTES, 'UTF-8');
			$comment = htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8');
			// ajouter les données au tableau
			$tabl = array (
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
				'socialSkills' => $tab['socialSkills'],
				'savoir-faire' => $tab['savoir-faire'],
				'socialSkills_ref' => $socialSkills,
				'savoir-faire_ref' => $savoir_faire,
				'comment' => $comment,
				'status' => 'confirmed',
				'id' => $id,
			);
			//on accède au tableau principale des données, et on réécrit le tableau ci-dessus à la place du tableau du skill avant la confirmation, pour le mettre à jour
			$user = $users[$username];
			foreach($user['skills'] as $num => $skill){
				if($skill['id'] == $tab['id']){
					$user['skills'][$num] = $tabl;
					break;
				}
			}
			$users[$username] = $user;
			unset($other[$id]);
			$file = fopen('../data.php', 'w'); // on met à jour le fichier data.php
			fwrite($file, '<?php $users = ' . var_export($users, true) . '; $other = ' . var_export($other, true) . '; ?>');
			fclose($file);

			$_SESSION = array();
			session_destroy();
			session_start();


			$file = fopen('email.html', 'w'); //on écrit le contenu du mail
			$body = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Votre expérience à été validé !</title></head>
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
			</style>
			<body><script>window.open("thankYou.php", "_blank");</script>
			<table class="bandeau">
				<tr>
					<td><img src="../images/logo.svg"><img></a></td>
				</tr>
			</table>
			<div class="bloc">
			<div>
			Bonjour, ' . $users[$username]['firstname'] . " " . $users[$username]['name'] . ".
			<br>Votre référent, " . $firstname . " " . $name . ",vient de valider votre expérience.
			</div>
			<div>
			<br>Aller donc voir si des éléments ont été modifiés ou ajoutés.
			<br><i><a href='http://localhost:8080/login.php'>Me connecter</a></i>
			</div>
			<div>
			<br>
			<br>Bien cordialement,
			<br><b>SERVICE JEUNE 6.4</b>
			</div></div></body></html>";
			fwrite($file, $body);// on écrit le mail dans la page avant d'aller dessus, de là bas on ouvrira un nouvel onglet pour diriger le referent sur thankYou.html
			fclose($file);	


			$file = fopen('../data.php', 'w');
			fwrite($file, '<?php $users = ' . var_export($users, true) . '; $other = ' . var_export($other, true) . '; ?>');
			fclose($file);
			header("Location: email.html");
			exit();
		}
	}
?>


<html>
<head>
	<title>Jeunes 6.4</title>
	<link rel="icon" type="image/x-icon" href="../images/favicon.ico">
	<link rel="stylesheet" href="confirm.css">
	<meta charset="UTF-8">
</head>
<body> 
<!-- bandeau contenant le logo et le status de l'utilisateur -->
	<table class="bandeau">
		<tr>
			<td rowspan="2"><a href="../home.php"><img src="../images/logo.svg"><img></a></td>
			<td><h1 id="taille1">RÉFÉRENT</h1></td>
		</tr>
		<tr>
			<td><p id="taille2">Je confirme la valeur de ton engagement</p></td>
		</tr>
	</table>
	<!-- bouton qui permet la déconnexion à la session -->
	<div id="bouton">
			<form method="POST">
				<button class="deconnexion" type="submit" name="deconnexion">Me Déconnecter</button>
			</form>
	</div>
	<!-- barre de naviqation permettant de naviguer entre les pages principales -->
	<div class="navbar">
		<ul>
			<li id="bandeau"><a class="jeune" href="../home.php">JEUNE </a></li>
			<li id="bandeau"><a class="referent" href="../referent_info.php" >RÉFÉRENT </a></li>
			<li id="bandeau"><a class="consultant" href="../consultant_info.php">CONSULTANT </a></li>
			<li id="bandeau"><a class="partenaires" href="../partenaires.php" >PARTENAIRES</a></li>
		</ul>
	</div>
	<br>
	<br>
	<br>
	<br>
	Merci de valider les données, et de les corriger si nécéssaire. Vous pouvez également en ajouter.
	<br>
	<br>
	<br>
	<br>
	<br>
	<form method="POST">
		<!-- -->
			<table class='description'>
					<tr><td>description :</td><td><input type="text" name="description" class="long" value="<?php echo $tab['description'];?>" maxlength="100" required> ex: agent d'accueil, assistant à domicile pour personne agée</td></tr>
					<tr><td>structure :</td><td><input type="text" name="environement" class="long" value="<?php echo $tab['environement'];?>" maxlength="50" required> ex: nom de l'entrprise, - </td></tr>
					<tr><td>début :</td><td><input type="date" name="beginning" value="<?php echo $tab['beginning'];?>" required></td></tr>
					<tr><td>durée :</td><td><input type="number" name="duration" value="<?php echo $tab['duration'];?>" required> <select name="durationType"><?php if(isset($message)){echo $message;}?>
						<option <?php if($tab['durationType'] == 'jours'){ echo 'selected'; } ?>>jours</option>
						<option <?php if($tab['durationType'] == 'semaines'){ echo 'selected'; } ?>>semaines</option>
						<option <?php if($tab['durationType'] == 'mois'){ echo 'selected'; } ?>>mois</option>
						<option <?php if($tab['durationType'] == 'années'){ echo 'selected'; } ?>>années</option>
					</td></tr>
				</table>
				<div id='global'>
				<!-- le référent peut mettre des savoir-faire au jeune -->
				<div id="first"><p class="underline">Ses savoir-faire :</p>
				<p class="underline">Savoir-faire</p>
				<table id="myTable">
					<tr><td><input type="text" name="myTable[]" class="long" maxlength="100"></td></tr>
				</table>
				<input type="button" onclick="addRow()" value="Ajouter un savoir-faire"> 
				<input type="button" onclick="deleteRow()" value="Effacer un savoir-faire">
				<!-- reprend les infos sur le référent que le jeune avait mis et le référent peut les modifier s'il le souhaite-->
				<br><br><br><br>Information sur le référent
				<table>
					<tr>
						<td>Nom :</td>
						<td><input type="text" name="name" value="<?php echo $tab['referent']['name'];?>" maxlength="50" required></td>
					</tr>
					<tr>
						<td>Prénom :</td>
						<td><input type="text" name="firstname" value="<?php echo $tab['referent']['firstname'];?>" maxlength="50" required></td>
					</tr>
					<tr>
						<td>Email :</td>
						<td><input type="email" name="email" value="<?php echo $tab['referent']['email'];?>" maxlength="50" required></td>
					</tr>
					<tr>
						<td>Poste/situation :</td>
						<td><input type="text" name="situation" value="<?php echo $tab['referent']['situation'];?>" maxlength="50" required></td>
					</tr>
				</table>
			</div>
			<!-- le référent peut confirmer les savoir-être du jeune -->
			<div id="second">
				<table>
				<tr><td id="etre">Ses savoir-être :</td></tr>
				<tr><td colspan=2 id="je_confirme">Je confirme son(sa)*</td></tr>
				<tbody id="savoir_etre">
					<tr>
						<td>
							<label class="container"> Fiabilité
								<input type="checkbox" name="socialSkills[]" value="Fiable" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> Détermination
								<input type="checkbox" name="socialSkills[]" value="Déterminé" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label class="container"> Autonomie
								<input type="checkbox" name="socialSkills[]" value="Autonome" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> Ouverture d'esprit
								<input type="checkbox" name="socialSkills[]" value="Ouvert d'esprit" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label class="container"> Réfléxion
								<input type="checkbox" name="socialSkills[]" value="Réfléchie" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> Honnêteté
								<input type="checkbox" name="socialSkills[]" value="Honnête" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label class="container"> Passion
								<input type="checkbox" name="socialSkills[]" value="Passionné" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> Ponctualité
								<input type="checkbox" name="socialSkills[]" value="Ponctuel" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label class="container"> Ecoute
								<input type="checkbox" name="socialSkills[]" value="A l'écoute" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> Respect
								<input type="checkbox" name="socialSkills[]" value="Respectueux" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label class="container"> Organisation
								<input type="checkbox" name="socialSkills[]" value="Organisé" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> Collaboration
								<input type="checkbox" name="socialSkills[]" value="Collaboratif" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label class="container"> Patience
								<input type="checkbox" name="socialSkills[]" value="Patient" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> proactivité
								<input type="checkbox" name="socialSkills[]" value="Proactif" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label class="container"> Responsabilité
								<input type="checkbox" name="socialSkills[]" value="Responsable" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> Diplomatie
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
							<label class="container"> Curiosité
								<input type="checkbox" name="socialSkills[]" value="Curieux" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label class="container"> Communication
								<input type="checkbox" name="socialSkills[]" value="Communicatif" onclick="checkLimite(this)">
								<span class="checkmark"></span>
							</label>
						</td>
						<td>
							<label class="container"> Empathie
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
				<div class='commentaire'>
				<textarea cols='84' rows='6' name='comment' class='y' maxlength="500" placeholder='Vous pouvez laisser un commentaire ici' onkeyup="limitText(this.value)"></textarea>
				<span class="x">0/500</span>
				<br><br>
				</div>
		
				<button type="submit" class='confirm'>Enregistrer</button>
			
	</form>
	<br><br><br><br><br>	
	
	
	<script src="confirm.js"></script>
	
</body>
<!-- inclus le footer -->
<?php include_once "../footer.html"; ?>
</html> 