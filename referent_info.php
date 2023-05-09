<?php
session_start();

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
	<link rel="icon" type="image/x-icon" href="/images/favicon.ico">
	<link rel="stylesheet" href="referent_info.css">
	<meta charset="UTF-8">
</head>


<body>
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
		<?php
			if(isset($_SESSION["role"])){
				echo '<form method="POST"><button class="deconnexion" type="submit" name="deconnexion">Me Déconnecter</button></form>';
			}
		?>
	</div>
	
	<div class="bandeau">
		<ul>
			<li><a class="jeune" href="jeune/skills.php">JEUNE </a></li>
			<li><a class="referent" href="referent_info.php" >RÉFÉRENT </a></li>
			<li><a class="consultant" href="consultant_info.php">CONSULTANT </a></li>
			<li><a class="partenaires" href="partenaires.php" >PARTENAIRES</a></li>
		</ul>
	</div>
</body>
</html> 