<?php
session_start();

if(isset($_POST['deconnexion'])){
    $_SESSION = array();
    session_destroy();
    header("Location: ../home.php");
    exit;
}
?>

<head>
	<title>Jeunes 6.4</title>
	<link rel="icon" type="image/x-icon" href="images/favicon.ico">
	<link rel="stylesheet" href="partenaires.css">
	<meta charset="UTF-8">
</head>


<body>
	<table class="bandeau">
		<tr>
			<td rowspan="2"><a href="home.php"><img src="images/logo.svg"><img></a></td>
			<td><h1 id="taille1">PARTENAIRES</h1></td>
		</tr>
		<tr>
			<td><p id="taille2"></p></td>
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
			<li class="navbar"><a class="jeune" href="jeune/skills.php">JEUNE </a></li>
			<li class="navbar"><a class="referent" href="referent_info.php" >RÉFÉRENT </a></li>
			<li class="navbar"><a class="consultant" href="consultant_info.php">CONSULTANT </a></li>
			<li class="navbar"><a class="partenaires" href="partenaires.php" >PARTENAIRES </a></li>
		</ul>
	</div>
	<div class="image">
		<p>JEUNES 6.4 est un dispositif issu de la <a href="http://test.le64.fr/uploads/tx_arccg64/charte2013.pdf"> charte de l’engagement</a> pour la<br>jeunesse signée en 2013 par des partenaires institutionnels...</p>
		<ul>
			<li><a href="https://www.jeunes.gouv.fr/"><img src="images/fronce_partnair.svg"></img></a></li>
			<li><a href="https://www.nouvelle-aquitaine.fr/" ><img src="images/Region-aquitaine.svg"></img></a></li>
			<li><a href="https://www.pyrenees-atlantiques.gouv.fr/"><img src="images/pyrenees_atlantiques.jpg"></img></a></li>
			<li><a href="https://www.ameli.fr/" ><img src="images/assurance-maladie-logo.png"></img></a></li>
		</ul>
		<br>
		<ul>
			<li><img src="images/assise_jeunesse.png"></img></li>
			<li class="nomarginright"><a href="https://www.caf.fr/" ><img src="images/alloc_Bearn.jpeg"></img></a></li>
			<li class="nomarginleft"><a href="https://www.caf.fr/"><img src="images/alloc_Basque.jpeg"></img></a></li>
			<li><a href="https://www.msa.fr/lfp" ><img src="images/logo-msa.gif"></img></a></li>
			<li><a href="https://www.univ-pau.fr/fr/index.html" ><img src="images/Universite_Pau_Pays_de_l'Adour.png"></img></a></li>
		</ul>
		<br><br>
		<p>...qui ont décidé de mettre en commun leurs actions pour les jeunes<br>des Pyrénées-Atlantiques.</p>
	</div>
</body>
</html> 