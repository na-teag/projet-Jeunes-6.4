<html>
<head>
	<title>Jeunes 6.4</title>
	<link rel="icon" type="image/x-icon" href="../images/favicon.ico">
	<link rel="stylesheet" href="thankYou.css">
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
			<li><a class="jeune" href="../jeune/skills.php">JEUNE </a></li>
			<li><a class="referent" href="../referent_info.php" >RÉFÉRENT </a></li>
			<li><a class="consultant" href="../consultant_info.php">CONSULTANT </a></li>
			<li><a class="partenaires" href="../partenaires.php" >PARTENAIRES</a></li>
		</ul>
	</div>
    <br><br><br><br><br><br><br><br>
    <div class="merci">MERCI !</div>
	<br><br><br><br><br><br><br>
</body>
<?php include_once "../footer.html"; ?>
</html> 

<script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script> <!-- importer le script gérant les confettis-->
<script>const jsConfetti = new JSConfetti();
	document.addEventListener('DOMContentLoaded', () => {
		setTimeout(() => {
			jsConfetti.addConfetti();// lancer les confettis 1s après le chargement
		}, 1000);
		setTimeout(() => {
        window.location.href = '../jeune6.4.html';// ensuite, rediriger sur la page d'entrée du projet
    }, 9000);
	});
</script>