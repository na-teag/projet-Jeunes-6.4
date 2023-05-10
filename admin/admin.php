<?php
	session_start();

	if(!isset($_SESSION["role"]) || $_SESSION["role"] != "admin" || $_SESSION["username"] != "admin"){
		header("Location: ../login.php");
		exit;
	}

	if(isset($_POST['deconnexion'])){
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
	<link rel="stylesheet" href="admin.css">
	<meta charset="UTF-8">
</head>
<body>
	<table class="bandeau">
		<tr>
			<td rowspan="2"><a href="../home.php"><img src="../images/logo.svg"><img></a></td>
			<td><h1 id="taille1">Administrateur</h1></td>
		</tr>
		<tr>
			<td><p id="taille2"></p></td>
		</tr>
	</table>
	<div id="bouton">
		<form method="POST">
			<button class="deconnexion" type="submit" name="deconnexion">Me Déconnecter</button>
		</form>
	</div>
</body>
</html> 