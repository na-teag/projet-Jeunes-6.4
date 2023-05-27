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
            $num = $number;
        }
    }
    $tab = $users[$username]['skills'][$num];
    

    if(isset($_POST['description']) && isset($_POST['name']) && isset($_POST['firstname']) && isset($_POST['email']) && isset($_POST['situation']) && isset($_POST['beginning']) && isset($_POST['duration'])){
        
        if(!(is_numeric($_POST['duration']) && intval($_POST['duration']) > 0)){
            $message = "veuillez entrez une valeur numérique positive";
        }else{
            
            
            $beginning = htmlspecialchars($_POST['beginning'], ENT_QUOTES, 'UTF-8');
            $duration = htmlspecialchars($_POST['duration'], ENT_QUOTES, 'UTF-8');
            $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
            $environement = htmlspecialchars($_POST['environement'], ENT_QUOTES, 'UTF-8');
            $durationType = $_POST['durationType'];//pas besoin de vérifier les données natives
            $socialSkills = $_POST['socialSkills'];
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
			$body = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Votre expérience à été validé !</title></head><body><script>window.open("thankYou.html", "_blank");</script>
			Bonjour ' . $users[$username]['firstname'] . " " . $users[$username]['name'] . ".
			<br>Votre référent, " . $firstname . " " . $name . ",vient de valider votre expérience.
			<br>Aller donc voir si des éléments ont été modifiés ou ajoutés.
			<br><i><a href='http://localhost:8080/login.php'>Me connecter</a></i>
			<br>
			<br>Bien cordialement,
			<br><b>SERVICE JEUNE 6.4</b></body></html>";
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
<body> <!-- cette section est tiré de la page newExperience.php (en partie, le php diffère) -->
	<table class="bandeau">
		<tr>
			<td rowspan="2"><a href="../home.php"><img src="../images/logo.svg"><img></a></td>
			<td><h1 id="taille1">RÉFÉRENT</h1></td>
		</tr>
		<tr>
			<td><p id="taille2">Je confirme la valeur de ton engagement</p></td>
		</tr>
	</table>
	<div id="bouton">
			<form method="POST">
				<button class="deconnexion" type="submit" name="deconnexion">Me Déconnecter</button>
			</form>
	</div>
	
	<div class="bandeau">
		<ul id="bandeau">
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
    merci de valider les données, et de les corriger si nécéssaire. Vous pouvez également en ajouter.
	<br>
	<br>
	<br>
	<br>
	<br>
	<form method="POST">
		<table>
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
		<table>
			<tr>
				<td>savoir-être :</td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Fiable</td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Déterminé</td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Autonome</td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Ouvert d'esprit</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Réfléchi</td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Honnête</td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Passionné</td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Ponctuel</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">A l'écoute</td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Respectueux</td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Organisé</td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Collaboratif</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Patient</td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Proactif</td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Responsable</td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Diplomate</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Optimiste</td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Curieux</td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Communicatif</td>
				<td><input type="checkbox" name="socialSkills[]" onclick="checkLimite(this)">Empathique</td>
			</tr>
		</table>


		<br><p class="marge">Mes savoir-faire</p>
		<table id="myTable">
            <?php
            if(isset($tab['savoir-faire'])){
                foreach($tab['savoir-faire'] as $savoir_faire){
                    echo '<tr><td><input type="text" name="myTable[]" class="long" maxlength="100"></td></tr>';
                }
            }?>
		</table>
		<input type="button" onclick="addRow()" value="Ajouter un savoir-faire"> 
		<input type="button" onclick="deleteRow()" value="Effacer un savoir-faire">
		
		
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
				<td><input type="text" name="email" value="<?php echo $tab['referent']['email'];?>" maxlength="50" required></td>
			</tr>
			<tr>
				<td>Poste/situation :</td>
				<td><input type="text" name="situation" value="<?php echo $tab['referent']['situation'];?>" maxlength="50" required></td>
			</tr>
		</table>
		<br><textarea id="texte" name="comment" cols="100" rows="6" placeholder="Vous pouvez également ajouter un commentaire ici" onchange="checkArea()"></textarea><span id="compteur">0</span>/500 caractères
		<br>
		<button type="submit">Enregistrer</button>
	</form>
	<br><br><br><br><br>	
	
	
	<script src="confirm.js"></script>
	
</body>
</html> 