<?php
	session_start();
	require_once 'data.php';
	
	
	if(isset($_POST['name']) && isset($_POST['firstname']) && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['birth']) && isset($_POST['gender'])){
		$name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');// échapper les caractères spéciaux
		$firstname = htmlspecialchars($_POST['firstname'], ENT_QUOTES, 'UTF-8');
		$gender = $_POST['gender'];//pas besoin de vérifier les données natives
		$birth = htmlspecialchars($_POST["birth"], ENT_QUOTES, 'UTF-8');
		$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
		$username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
		$password = $_POST['password'];
	
		$birthDate = new DateTime($birth); // on vérifi que l'utilisateur à mois de 30 ans
		$currentDate = new DateTime();
		$age = $currentDate->diff($birthDate)->y;
		
		if(isset($users[$username]) || $username == 'admin'){ // verifier si l'identifiant est deja pris
			$message = "Cet identifiant est déjà utilisé, veuillez en choisir un autre.";
		}else if(!(16 <= $age && $age <= 30)){
			$message = "Ce site est exclusivement réservé aux utilisateurs entre 16 et 30 ans, merci d'utiliser un autre service que le projet Jeunes 6.4.";
		}else{
	
		
			$users[$username] = array( // ajouter nouvel utilisateur
				'name' => $name,
				'firstname' => $firstname,
				'gender' => $gender,
				'birth' => $birth,
				'email' => $email,
				'username' => $username,
				'password' => password_hash($password, PASSWORD_DEFAULT),
				'role' => 'jeune',
				'skills' => array(),
			);

		

			$file = fopen('data.php', 'w');
			fwrite($file, '<?php $users = ' . var_export($users, true) . '; $other = ' . var_export($other, true) . '; ?>');
			fclose($file);
			$_SESSION["username"] = $username;
			$_SESSION["role"] = "jeune";
			header("Location: jeune/skills.php");
			exit();
		}
	}
	
?>
<html>
<head>
<head>
	<title>Jeunes 6.4</title>
	<meta charset="UTF-8">
	<link rel="icon" type="image/x-icon" href="/images/favicon.ico">
	<link rel="stylesheet" href="login.css">
</head>
<body>
	<table class="bandeau">
			<tr>
				<td rowspan="2"><a href="home.php"><img src="../images/logo.svg"><img></a></td>
				<td><h1 id="taille1">JEUNE</h1></td>
			</tr>
			<tr>
				<td><p id="taille2">Pour faire de l'engagement une valeur</p></td>
			</tr>
	</table>
	<div class="navbar">
		<ul>
			<li><a class="jeune" href="jeune/skills.php">JEUNE </a></li>
			<li><a class="referent" href="referent_info.php" >RÉFÉRENT </a></li>
			<li><a class="consultant" href="consultant_info.php">CONSULTANT </a></li>
			<li><a class="partenaires" href="partenaires.php" >PARTENAIRES</a></li>
		</ul>
	</div>
	<br>
	<div id="message"><?php
		if(isset($message)){
			echo $message;
		}
	?></div>
	<br><br><br>
	<h1>Inscription</h1>
	<form method="POST">
		<label>Genre:</label>
		<input type="radio" id="homme" name="gender" value="man" maxlength="100" required>
  		<label for="homme">Homme</label>
  		<input type="radio" id="femme" name="gender" value="woman" maxlength="100" required>
  		<label for="femme">Femme</label>
  		<input type="radio" id="autre" name="gender" value="other" maxlength="100" required>
  		<label for="autre">Autre</label><br><br>
		<label>Nom:</label><br>
		<input type="text" name="name" maxlength="100" required><br><br>
		<label>Prénom:</label><br>
		<input type="text" name="firstname" maxlength="100" required><br><br>
		<label>Date de Naissance :</label><br>
		<input type="date" name="birth" required><br><br>
		<label>Email:</label><br>
		<input type="email" name="email" maxlength="100" required><br><br>
		<label>identifiant:</label><br>
		<input type="text" name="username" maxlength="100" required><br><br>
		<label>Mot de passe:</label><br>
		<input type="password" name="password" maxlength="100" required><br><br>
		<input type="submit" value="S'inscrire">
	</form>
</body>
</html>

	<br><br><br><br>
</body>
<?php include_once "footer.html"; ?>

</html>  