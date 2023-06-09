<?php
session_start();
//seul les comptes admin et jeune peuvent accéder à cet page
if(!isset($_SESSION["role"]) || ($_SESSION["role"] != "jeune" && $_SESSION["role"] != "admin")){
	header("Location: ../login.php");
	exit;
}

require_once '../data.php';
$username = $_SESSION["username"];
$tab = $users[$username];


if(isset($_POST['name']) && isset($_POST['firstname']) && isset($_POST['email']) && isset($_POST['birth']) && isset($_POST['gender'])){
	$name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');// échapper les caractères spéciaux
	$firstname = htmlspecialchars($_POST['firstname'], ENT_QUOTES, 'UTF-8');
	$gender = $_POST['gender'];//pas besoin de vérifier les données natives
	$birth = htmlspecialchars($_POST["birth"], ENT_QUOTES, 'UTF-8');
	$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');  

	$password = $tab['password'];
	$skills = $tab['skills'];
	
	$users[$username] = array( // met à jour les données
		'name' => $name,
		'firstname' => $firstname,
		'gender' => $gender,
		'birth' => $birth,
		'email' => $email,
		'username' => $username,
		'password' => $password,
		'role' => 'jeune',
		'skills' => $skills,
	);

	$file = fopen('../data.php', 'w'); // mettre a jour le fichier data
	fwrite($file, '<?php $users = ' . var_export($users, true) . '; $other = ' . var_export($other, true) . '; ?>');
	fclose($file);
	header("Location: skills.php");
	exit();
}



if(isset($_POST['oldPassword']) && isset($_POST['newPassword'])){// partie pour changer le mot de passe de l'utilisateur
	$tab = $users[$username];
	if(password_verify($_POST['oldPassword'], $tab["password"]) || $_SESSION["role"] == "admin"){
		$name = $tab['name'];
		$firstname = $tab['firstname'];
		$gender = $tab['gender'];
		$email = $tab['email'];
		$birth = $tab['birth'];
		$username = $tab['username'];
		$skills = $tab['skills'];

		$users[$username] = array( // met à jour les données
			'name' => $name,
			'firstname' => $firstname,
			'gender' => $gender,
			'birth' => $birth,
			'email' => $email,
			'username' => $username,
			'password' => password_hash($_POST['newPassword'], PASSWORD_DEFAULT),
			'role' => 'jeune',
			'skills' => $skills,
		);

		$file = fopen('../data.php', 'w');
		fwrite($file, '<?php $users = ' . var_export($users, true) . '; $other = ' . var_export($other, true) . '; ?>');
		fclose($file);
		header("Location: skills.php");
	}else{
		$message = "mot de passe incorrect";
	}
}


if(isset($_POST['delete'])){// partie pour supprimer le compte de l'utilisateur
	unset($users[$_SESSION["username"]]);

	foreach($other as $key => $value){// partie pour supprimer les clé d'identifiant pour referent et consultant associé au compte du jeune
		if($value['user'] == $username){
			unset($other[$key]);
		}
	}

	$file = fopen('../data.php', 'w'); // mettre a jour le fichier data
	fwrite($file, '<?php $users = ' . var_export($users, true) . '; $other = ' . var_export($other, true) . '; ?>');
	fclose($file);
	$_SESSION = array();
	session_destroy();
	header("Location: ../jeune6.4.html");
	exit;
}


if(isset($_POST['deconnexion'])){// partie pour déconnecter l'utilisateur
	$_SESSION = array();
	session_destroy();
	header("Location: ../home.php");
	exit;
}
?>



<html>
<head>
	<title>Jeunes 6.4</title>
	<link rel="icon" type="image/x-icon" href="../images/favicon.ico">
	<link rel="stylesheet" href="myaccount.css">
	<meta charset="UTF-8">
</head>
<body>
	<!-- tableau permettant de contenir le logo et le statut de l'utilisateur -->
	<table class="bandeau">
		<tr>
			<td rowspan="2"><a href="../home.php"><img src="../images/logo.svg"><img></a></td>
			<td><h1 id="taille1">JEUNE</h1></td>
		</tr>
		<tr>
			<td><p id="taille2">Je donne de la valeur à mon engagement</p></td>
		</tr>
	</table>
	<!--bouton qui s'affiche seulement si le consultant est connecté à sa session -->
		<div id="bouton">
				<form method="POST">
					<button class="deconnexion" type="submit" name="deconnexion">Me Déconnecter</button>
				</form>
		</div>
	<!-- barre de navigation permettant de naviguer entre les pages -->
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

		<fieldset id="main">
		<legend class='titre'>Mon Profil</legend>
		<form method="POST">
			<label>Genre:</label>
			<label for="homme" class='container'>Homme
				<input type="radio" id="homme" name="gender" value="man" maxlength="100" <?php if($tab['gender'] == 'man'){ echo 'checked';} ?> required>
				<span class="checkmark"></span>
  			</label>
			<label for="femme" class='container'>Femme
				<input type="radio" id="femme" name="gender" value="woman" maxlength="100" <?php if($tab['gender'] == 'woman'){ echo 'checked';} ?> required>
				<span class="checkmark"></span>
  			</label>
			<label for="autre" class='container'>Autre
  				<input type="radio" id="autre" name="gender" value="other" maxlength="100" <?php if($tab['gender'] == 'other'){ echo 'checked';} ?> required>
				<span class="checkmark"></span>
			</label>
  			<br><br>
			<label>Nom:</label><br>
			<input type="text" name="name" maxlength="100" value="<?php echo $tab['name'];?>" required><br><br>
			<label>Prénom:</label><br>
			<input type="text" name="firstname" maxlength="100" value="<?php echo $tab['firstname'];?>" required><br><br>
			<label>Date de Naissance :</label><br>
			<input type="date" name="birth" value="<?php echo $tab['birth'];?>" required><br><br>
			<label>Email:</label><br>
			<input type="email" name="email" maxlength="100" value="<?php echo $tab['email'];?>" required><br><br>
			<input class="confirm" type="submit" value="Valider mes données">
		</form>

		<input class="delete" type="button" name="delete" value="effacer mon compte" onclick="delete_account()">
		
		<br><br>
		<input class="change" type="button" name="change" value="changer de mot de passe" onclick="password()">
		<br><br><br>
    	<div id="message"><b><i><marquee width="400" scrollamount="400" scrolldelay="600" loop="7">
			<?php
			if(isset($message)){
				echo $message;
			}
			?></marquee></i></b>
		</div>

	</fieldset><br><br><br>
	<script src="myaccount.js"></script>

</body>
<!--inclus le footer-->
<?php include_once "../footer.html"; ?>
</html>