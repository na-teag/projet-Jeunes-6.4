<?php
session_start();

if($_SESSION['role'] == 'referent'){
    header("Location: /referent/confirm.php?id=" . $_SESSION['username']);
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
	
	<div class="navbar">
		<ul>
			<li><a class="jeune" href="jeune/skills.php">JEUNE </a></li>
			<li><a class="referent" href="referent_info.php" >RÉFÉRENT </a></li>
			<li><a class="consultant" href="consultant_info.php">CONSULTANT </a></li>
			<li><a class="partenaires" href="partenaires.php" >PARTENAIRES</a></li>
		</ul>
	</div>
	<div class="bloc">
		<div class="case1"><p>Etape 1:</p>
			<p>Le Jeune dans sa demande de validation d'expérience met l'adresse mail d'un référent, qui a été l'un de ses responsables, que ce soit pour un club de sport, une association 
			ou bien même une entreprise.</p>
		</div>
		<div class="case2"><p>Etape 2:</p>
			<p>Le Référent reçoit un mail avec un lien sur lequel il peut cliquer et qui l'emmènera sur une page pour confirmer les champs du Jeune qu'il considère justes et il pourra,
			s'il le souhaite, ajouter des savoir-être et/ou des savoir-faire, ainsi qu'un champ des aspects à valoriser si besoin.</p>
		</div>
		<div class="case3"><p>Etape 3:</p>
			<p>Ensuite il recevra une page de confirmation et de remerciements.</p>
		</div>
	</div>
</body>
</html>  