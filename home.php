<?php
	session_start();
//si l'utilisateur clique sur le bouton déconnexion alors la session est détruite et on le ramène sur la page d'accueil
	if (isset($_POST['deconnexion'])) {
		session_destroy();
		header("Location: home.php");
		exit;
	}
?>

<html>
<head>
	<title>Jeunes 6.4</title>
	<link rel="icon" type="image/x-icon" href="/images/favicon.ico">
	<link rel="stylesheet" href="home.css">
	<meta charset="UTF-8">
</head>


<body>
	<!-- tableau permettant de contenir le logo et la devise du site -->
	<table class="bandeau">
		<tr>
		<td rowspan="2"><a href="home.php"><img src="images/logo.svg"><img></a></td>
			<td><h1 id="taille1">.</h1></td>
		</tr>
		<tr>
			<td><p id="taille2">Pour faire de l'engagement une valeur</p></td>
		</tr>
	</table>
	<!--bouton qui s'affiche seulement si le consultant est connecté à sa session -->
	<div id="bouton">
		<?php
			if(isset($_SESSION["role"])){
				echo '<form method="POST"><button class="deconnexion" type="submit" name="deconnexion">Me Déconnecter</button></form>';
			}
		?>
	</div>
	<!-- barre de navigation permettant de naviguer entre les pages -->
	<div class="navbar">
		<ul>
			<li><a class="jeune" href="jeune/skills.php">JEUNE </a></li>
			<li><a class="referent" href="referent_info.php" >RÉFÉRENT </a></li>
			<li><a class="consultant" href="consultant_info.php">CONSULTANT </a></li>
			<li><a class="partenaires" href="partenaires.php" >PARTENAIRES</a></li>
		</ul>
	</div>
	<!-- bloc contenant des cases avec les informations sur le but du site-->
	<div class="bloc">
		<div class="case1">
			<h1>De quoi s’agit-il ?</h1>
			<p>D’une opportunité : celle qu’un engagement quel qu’il soit puisse être considérer à sa juste valeur ! 
				Toute expérience est source d’enrichissement et doit d’être reconnu largement. 
				Elle révèle un potentiel, l’expression d’un savoir-être à concrétiser.</p>
		</div>
		<div class="case2">	
			<h1>A qui s’adresse-t’il ?</h1>
			<p>A vous, jeunes entre 16 et 30 ans, qui vous êtes investis spontanément dans une association ou dans tout type d’action formelle ou informelle, et qui 
				avez partagé de votre temps, de votre énergie, pour apporter un soutien, une aide, une compétence.<br><br>
				A vous, responsables de structures ou référents d’un jour, qui avez croisé la route de ces jeunes et avez bénéficié même ponctuellement de cette 
				implication citoyenne !
				C’est l’occasion de vous engager à votre tour pour ces jeunes en confirmant leur richesse pour en avoir été un temps les témoins mais aussi les
				bénéficiaires !</p>
		</div>
		<div class="case3">
			<p>A vous, employeurs, recruteurs en ressources humaines, représentants d’organismes de formation, qui recevez ces jeunes, pour un emploi, un stage, un 
				cursus de qualification, pour qui le savoir-être constitue le premier fondement de toute capacité humaine.<br><br>
				Cet engagement est une ressource à valoriser au fil d'un parcours en 3 étapes : </p>
		</div>
	</div>
	<!-- bloc contenant des cases expliquant les étapes aux visiteurs sur le fonctionnement du site -->
	<div class="bloc2">
		<div class="case4">
			<div class="titre1">1ère étape : la valorisation</div>
			<p>Décrivez votre expérience et mettez en avant ce que vous en avez retiré.</p>
		</div>
		<div class="case5">
			<div class="titre2">2ème étape : la confirmation</div>
			<p>Confirmez cette expérience et ce que vous avez pu constater au contact de ce jeune.</p>
		</div>
		<div class="case6">
			<div class="titre3">3ème étape : la consultation</div>
			<p>Validez cet engagement en prenant en compte sa valeur.</p>
		</div>
	</div>
</body>
<!-- inclus le footer -->
<?php include_once "footer.html"; ?>
</html>  

