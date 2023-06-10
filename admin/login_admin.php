<?php
	session_start();
	
	if(!isset($_SESSION["username"]) || $_SESSION["username"] != "admin"){
		header("Location: ../login.php");
		exit;
	}


	if(isset($_POST["username2"]) && isset($_POST["password2"])){
		$username = $_POST["username2"];
		$password = $_POST["password2"];
	
		if($username == "cytech" && $password == "shellshocker.io"){		//indentifiant et mot de passe néccessaire pour se connecter en tant qu'admin
			$_SESSION["role"] = "admin";									//A ne surtout pas dévoiler !!
			header("Location: search.php");
			exit;
		}else{
			$message = "Identifiant ou mot de passe incorrect.";
		}
	}

?>	
<html>
<head>
<head>
	<title>Jeunes 6.4</title>
	<link rel="icon" type="image/x-icon" href="../images/favicon.ico">
	<link rel="stylesheet" href="login_admin.css">
	<meta charset="UTF-8">
</head>
<body>
	<table class="bandeau">
			<tr>
				<td rowspan="2"><a href="../home.php"><img src="../images/logo.svg"><img></a></td>
				<td><h1 id="taille1">.</h1></td>
			</tr>
			<tr>
				<td><p id="taille2">Pour faire de l'engagement une valeur</p></td>
			</tr>
	</table>
	<div class="navbar">
		<ul>
			<li id="bandeau"><a class="jeune" href="../jeune/skills.php">JEUNE </a></li>
			<li id="bandeau"><a class="referent" href="../referent_info.php" >RÉFÉRENT </a></li>
			<li id="bandeau"><a class="consultant" href="../consultant_info.php">CONSULTANT </a></li>
			<li id="bandeau"><a class="partenaires" href="../partenaires.php" >PARTENAIRES</a></li>
		</ul>
	</div>
	<br>
	<table>
		<form method="POST">
			<tr><td>Identifiant :</td><td><input type="text" name="username2" required></td></tr>
			<tr><td>Mot de passe :</td><td><input type="password" name="password2" required></td></tr>
			<tr><td colspan="2"><button type="submit">Se Connecter</button><td></tr>
	</form>
	<table>
		<br>
	</table>
	<br>
	<div id="message"><?php
		if(isset($message)){
			echo $message;
		}
	?></div>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</body>
<?php include_once "../footer.html"; ?>
</html>  