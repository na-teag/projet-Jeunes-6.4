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
                'socialSkills' => $socialSkills,
                'savoir-faire' => $savoir_faire,
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
            $file = fopen('../data.php', 'w');
            fwrite($file, '<?php $users = ' . var_export($users, true) . '; $other = ' . var_export($other, true) . '; ?>');
            fclose($file);

	        $_SESSION = array();
	        session_destroy();
	        session_start();
            header("Location: thankYou.html");
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
			<tr><td>description :</td><td><input type="text" name="description" class="long" value="<?php echo $tab['description'];?>" required> ex: agent d'accueil, assistant à domicile pour personne agée</td></tr>
			<tr><td>cadre :</td><td><input type="text" name="environement" class="long" value="<?php echo $tab['environement'];?>" required> ex: nom de l'entrprise, - </td></tr>
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
				<td><input type="checkbox" name="socialSkills[]" value="Fiable" onclick="checkLimite(this)" <?php if(in_array('Fiable', $tab['socialSkills'])){ echo 'checked';} ?>>Fiable</td>
				<td><input type="checkbox" name="socialSkills[]" value="Déterminé" onclick="checkLimite(this)" <?php if(in_array('Déterminé', $tab['socialSkills'])){ echo 'checked';} ?>>Déterminé</td>
				<td><input type="checkbox" name="socialSkills[]" value="Autonome" onclick="checkLimite(this)" <?php if(in_array('Autonome', $tab['socialSkills'])){ echo 'checked';} ?>>Autonome</td>
				<td><input type="checkbox" name="socialSkills[]" value="Ouvert d'esprit" onclick="checkLimite(this)" <?php if(in_array("Ouvert d'esprit", $tab['socialSkills'])){ echo 'checked';} ?>>Ouvert d'esprit</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="checkbox" name="socialSkills[]" value="Réfléchi" onclick="checkLimite(this)" <?php if(in_array('Réfléchi', $tab['socialSkills'])){ echo 'checked';} ?>>Réfléchi</td>
				<td><input type="checkbox" name="socialSkills[]" value="Honnête" onclick="checkLimite(this)" <?php if(in_array('Honnête', $tab['socialSkills'])){ echo 'checked';} ?>>Honnête</td>
				<td><input type="checkbox" name="socialSkills[]" value="Passionné" onclick="checkLimite(this)" <?php if(in_array('Passionné', $tab['socialSkills'])){ echo 'checked';} ?>>Passionné</td>
				<td><input type="checkbox" name="socialSkills[]" value="Ponctuel" onclick="checkLimite(this)" <?php if(in_array('Ponctuel', $tab['socialSkills'])){ echo 'checked';} ?>>Ponctuel</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="checkbox" name="socialSkills[]" value="A l'écoute" onclick="checkLimite(this)" <?php if(in_array("A l'écoute", $tab['socialSkills'])){ echo 'checked';} ?>>A l'écoute</td>
				<td><input type="checkbox" name="socialSkills[]" value="Respectueux" onclick="checkLimite(this)" <?php if(in_array("Respectueux", $tab['socialSkills'])){ echo 'checked';} ?>>Respectueux</td>
				<td><input type="checkbox" name="socialSkills[]" value="Organisé" onclick="checkLimite(this)" <?php if(in_array("Organisé", $tab['socialSkills'])){ echo 'checked';} ?>>Organisé</td>
				<td><input type="checkbox" name="socialSkills[]" value="Collaboratif" onclick="checkLimite(this)" <?php if(in_array("Collaboratif", $tab['socialSkills'])){ echo 'checked';} ?>>Collaboratif</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="checkbox" name="socialSkills[]" value="Patient" onclick="checkLimite(this)" <?php if(in_array("Patient", $tab['socialSkills'])){ echo 'checked';} ?>>Patient</td>
				<td><input type="checkbox" name="socialSkills[]" value="Proactif" onclick="checkLimite(this)" <?php if(in_array("Proactif", $tab['socialSkills'])){ echo 'checked';} ?>>Proactif</td>
				<td><input type="checkbox" name="socialSkills[]" value="Responsable" onclick="checkLimite(this)" <?php if(in_array("Responsable", $tab['socialSkills'])){ echo 'checked';} ?>>Responsable</td>
				<td><input type="checkbox" name="socialSkills[]" value="Diplomate" onclick="checkLimite(this)" <?php if(in_array("Diplomate", $tab['socialSkills'])){ echo 'checked';} ?>>Diplomate</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="checkbox" name="socialSkills[]" value="Optimiste" onclick="checkLimite(this)" <?php if(in_array("Optimiste", $tab['socialSkills'])){ echo 'checked';} ?>>Optimiste</td>
				<td><input type="checkbox" name="socialSkills[]" value="Curieux" onclick="checkLimite(this)" <?php if(in_array("Curieux", $tab['socialSkills'])){ echo 'checked';} ?>>Curieux</td>
				<td><input type="checkbox" name="socialSkills[]" value="Communicatif" onclick="checkLimite(this)" <?php if(in_array("Communicatif", $tab['socialSkills'])){ echo 'checked';} ?>>Communicatif</td>
				<td><input type="checkbox" name="socialSkills[]" value="Empathique" onclick="checkLimite(this)" <?php if(in_array("Empathique", $tab['socialSkills'])){ echo 'checked';} ?>>Empathique</td>
			</tr>
		</table>


		<br><p class="marge">Mes savoir-faire</p>
		<table id="myTable">
            <?php
            if(isset($tab['savoir-faire'])){
                foreach($tab['savoir-faire'] as $savoir_faire){
                    echo '<tr><td><input type="text" name="myTable[]" class="long" value="' . $savoir_faire . '"></td></tr>';
                }
            }?>
		</table>
		<input type="button" onclick="addRow()" value="Ajouter un savoir-faire"> 
		<input type="button" onclick="deleteRow()" value="Effacer un savoir-faire">
		
		
		<br><br><br><br>Information sur le référent
		<table>
			<tr>
				<td>Nom :</td>
				<td><input type="text" name="name" value="<?php echo $tab['referent']['name'];?>" required></td>
			</tr>
			<tr>
				<td>Prénom :</td>
				<td><input type="text" name="firstname" value="<?php echo $tab['referent']['firstname'];?>" required></td>
			</tr>
			<tr>
				<td>Email :</td>
				<td><input type="text" name="email" value="<?php echo $tab['referent']['email'];?>" required></td>
			</tr>
			<tr>
				<td>Poste/situation :</td>
				<td><input type="text" name="situation" value="<?php echo $tab['referent']['situation'];?>" required></td>
			</tr>
		</table>
		<br>
		<button type="submit">Enregistrer</button>
	</form>
	<br><br><br><br><br>	
	
	
	<script src="../jeune/newExperience.js"></script>
	
</body>
</html> 