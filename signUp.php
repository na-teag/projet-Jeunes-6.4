<?php
	session_start();
	require_once 'data.php';
	
	
	if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['birth']) && isset($_POST['gender'])){
		$name = $_POST['nom'];
		$firstname = $_POST['prenom'];
		$gender = $_POST['gender'];
		$birth = $_POST["birth"];
		$email = $_POST['email'];
		$username = $_POST['username'];
		$password = $_POST['password'];
	
		
		if(isset($users[$username]) || $username == 'admin'){ // Vérifier si l'identifiant est déjà pris
			$message = "Cet identifiant est déjà utilisé, veuillez en choisir un autre.";
		}else{
	
		
			$users[$username] = array(   // Ajouter nouvel utilisateur
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

		

			$file = fopen('data.php', 'w');// Écrire les données
			fwrite($file, '<?php $users = ' . var_export($users, true) . '; ?>');
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
				<td><h1 id="taille1">.</h1></td>
			</tr>
			<tr>
				<td><p id="taille2">Pour faire de l'engagement une valeur</p></td>
			</tr>
	</table>
	<div class="bandeau">
		<ul>
			<li><a class="jeune" href="jeune/skills.php">JEUNE </a></li>
			<li><a class="referent" href="referent_info.php" >RÉFÉRENT </a></li>
			<li><a class="consultant" href="consultant_info.php">CONSULTANT </a></li>
			<li><a class="partenaires" href="partenaires.php" >PARTENAIRES</a></li>
		</ul>
	</div>
	<br>
	<h1>Inscription</h1>
	<form method="POST">
		<label>Genre:</label>
		<input type="radio" id="homme" name="gender" value="man" required>
  		<label for="homme">Homme</label>
  		<input type="radio" id="femme" name="gender" value="woman" required>
  		<label for="femme">Femme</label>
  		<input type="radio" id="autre" name="gender" value="other" required>
  		<label for="autre">Autre</label><br><br>
		<label>Nom:</label><br>
		<input type="text" name="nom" required><br><br>
		<label>Prénom:</label><br>
		<input type="text" name="prenom" required><br><br>
		<label>Date de Naissance :</label><br>
		<input type="date" name="birth" required><br><br>
		<label>Email:</label><br>
		<input type="email" name="email" required><br><br>
		<label>identifiant:</label><br>
		<input type="text" name="username" required><br><br>
		<label>Mot de passe:</label><br>
		<input type="password" name="password" required><br><br>
		<input type="submit" value="S'inscrire">
	</form>
</body>
</html>

	<div id="message"><?php
		if(isset($message)){
			echo $message;
		}
	?></div>
</body>

<!-- 
  function checkEmail(email){
  var structure = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]+$/;
  return structure.test(email);
  }
 -->

</html> 