<?php
	session_start();
	if(isset($_SESSION["role"])){
		$message_deco = "Vous avez été déconnecté de votre <br>session " . $_SESSION["role"];
	}
	$_SESSION = array();
	session_destroy();
	session_start();

	require_once 'data.php';

	if(isset($_POST["username"]) && isset($_POST["password"])){
		$username = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');// échapper les caractères spéciaux
		$password = $_POST["password"];
	
		$user_found = 0;
		foreach($users as $user){
			if($user["username"] == $username && password_verify($password, $user["password"])){
				$user_found = 1;
			}
		}
	
		if($user_found == 1){
			$_SESSION["username"] = $username;
			$_SESSION["role"] = "jeune";
			header("Location: jeune/skills.php");
			exit;
		}else{
			if($username == $password && $password == 'admin'){
				$_SESSION["username"] = "admin";
				header("Location: admin/login_admin.php");
				exit;
			}else{
				$message = "Identifiant ou mot de passe incorrect.";
			}
		}
	}
?>	
<html>
<head>
<head>
	<title>Jeunes 6.4</title>
	<link rel="icon" type="image/x-icon" href="/images/favicon.ico">
	<link rel="stylesheet" href="login.css">
	<meta charset="UTF-8">
</head>
<body>
	<!-- tableau permettant de contenir le logo et le statut de l'utilisateur -->
	<table class="bandeau">
			<tr>
				<td rowspan="2"><a href="home.php"><img src="images/logo.svg"><img></a></td>
				<td><h1 id="taille1">JEUNE</h1></td>
			</tr>
			<tr>
				<td><p id="taille2">Pour faire de l'engagement une valeur</p></td>
			</tr>
	</table>
	<!-- tableau permettant de contenir le logo et le statut de l'utilisateur -->
	<div class="navbar">
		<ul>
			<li><a class="jeune" href="jeune/skills.php">JEUNE </a></li>
			<li><a class="referent" href="referent_info.php" >RÉFÉRENT </a></li>
			<li><a class="consultant" href="consultant_info.php">CONSULTANT </a></li>
			<li><a class="partenaires" href="partenaires.php" >PARTENAIRES</a></li>
		</ul>
	</div>
	<br>
	<div id="message"><b><i><marquee width="400" scrollamount="400" scrolldelay="600" loop="10">
		<?php
		if(isset($message)){
			echo $message;
		}
		if(isset($message_deco)){
			echo $message_deco;
		}
		?></marquee></i></b>
	</div>
	<table class="tableau">
		<form method="POST">
			<tr><td>Identifiant :</td><td><input type="text" name="username" required></td>
			<td width="50%"></td></tr>
			<tr><td>Mot de passe :</td><td><input type="password" name="password" required></td></tr>
			<tr><td colspan="2"><button type="submit">Se Connecter</button><td></tr>
		</form>		
	</table>
	<a href="signUp.php">Créer un compte<a>
	<div class="bloc">
		<div class="case">
			Le projet <b>Jeunes 6.4</b> a pour vocation<br> de valoriser l'engagement des jeunes de <b>16 à 30 ans</b>,<br> issu de la région <b>Pyrénées-Atlantiques</b>.<br>
			Ce dispositif vous permet de valoriser votre <br><b>savoir-faire</b> et votre <b>savoir-être</b><br> auprès de <b>recruteur potentiel</b>, 
			en<br> faisant valider vos expériences par des <b>référents</b>
		</div>
	</div>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	
</body>
<?php include_once "footer.html"; ?>
</html>  