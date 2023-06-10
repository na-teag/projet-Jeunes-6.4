<!-- partie à mettre dans chaques pages pour interdire l'accès sans identification-->

<?php
session_start();

if(!isset($_SESSION["role"]) || ($_SESSION["role"] != "jeune" && $_SESSION["role"] != "admin")){
	header("Location: ../login.php");
	exit;
	echo "test";
}


$username = $_SESSION["username"];
require '../data.php';



if(isset($_POST['deconnexion'])){// partie pour déconnecter l'utilisateur
	$_SESSION = array();
	session_destroy();
	header("Location: ../home.php");
	exit;
}
if(isset($_POST['newExperience'])){ // les différentes redirections selon les boutons cliqués
	header("Location: newExperience.php");
	exit;
}
if(isset($_POST['select'])){
	header("Location: select.php");
	exit;
}
if(isset($_POST['account'])){
	header("Location: myaccount.php");
	exit;
}
if(isset($_POST['consult'])){
	header("Location: myConsultations.php");
	exit;
}
	
?>

<!-- partie classique de la page-->



<html>
<head>
	<title>Jeunes 6.4</title>
	<link rel="icon" type="image/x-icon" href="../images/favicon.ico">
	<link rel="stylesheet" href="skills.css">
	<meta charset="UTF-8">
</head>

<body>
	
<main>
	<table class="bandeau">
		<tr>
			<td rowspan="2"><a href="../home.php"><img src="../images/logo.svg"><img></a></td>
			<td><h1 id="taille1">JEUNE</h1></td>
		</tr>
		<tr>
			<td><p id="taille2">Je donne de la valeur à mon engagement</p></td>
		</tr>
	</table>
	<div class="bloc1">
		<div id="button">
				<form method="POST">
					<button class="deconnexion" type="submit" name="deconnexion">Me Déconnecter</button>
				</form>
		</div>

		<div class="navbar">
			<ul>
				<li id="bandeau"><a class="jeune" href="skills.php">JEUNE </a></li>
				<li id="bandeau"><a class="referent" href="../referent_info.php" >RÉFÉRENT </a></li>
				<li id="bandeau"><a class="consultant" href="../consultant_info.php">CONSULTANT </a></li>
				<li id="bandeau"><a class="partenaires" href="../partenaires.php" >PARTENAIRES</a></li>
			</ul>
		</div>
	</div>

    <div id="nav-open" class="nav-button">
        <ion-icon name="menu-outline"></ion-icon> <!-- icone pour ouvrir la barre de menu latérale-->
    </div>

    <div id="nav-latteral">
		<a href="../home.php"><img class="marge_bottom" src="../images/logo.svg"><img></a> <!-- contenu de la barre latérale-->
        <div id="nav-close" class="nav-button">
            <ion-icon name="close"></ion-icon>
        </div>
        <div class="nav-links">
            <div class="nav-link">
				<form method="POST">
					<button class="newExperience" type="submit" name="newExperience">Ajouter une expérience</button>
				</form>
            </div>
            <div class="nav-link">
                <form method="POST">
					<button class="select" type="submit" name="select">gérer mes expériences</button>
				</form>
            </div>
            <div class="nav-link">
                <form method="POST">
					<button class="select" type="submit" name="consult">gérer les consultation de mes expériences</button>
				</form>
            </div>
			<div class="nav-link">
				<form method="POST">
					<button class="account" type="submit" name="account">mon profil</button>
				</form>
            </div>
        </div>
    </div>


    <div id="nav-wrapper"></div>
    
	
    
	<div class="liste">
		<?php
			if(!empty($users[$username]['skills'])){
				$nbrConfirmedSkill = 0;
				echo '<br><h2>Mes expériences confirmées</h2><table class="general"><tr class="back">';
				foreach($users[$username]["skills"] as $skill){ # boucle pour afficher les expériences confirmées
					if($skill['status'] == "confirmed"){
						$nbrConfirmedSkill++;
						echo '<td id="jeune"><h1 class="titre">JEUNE</h1><div id="global_1"><div id="section_1"><h2>' . $skill["environement"] . "</h2><ul><li>description: " . $skill["description"] . "</li><li>début: " . $skill["beginning"] . "</li><li> durée: " . $skill["duration"] . " " . $skill["durationType"] . "</li></ul>";
						echo "<h3>Compétences selon moi</h3>"; // compétences selon le jeune
						if(!empty($skill['savoir-faire'])){
							echo "<h4>Savoir faire</h4>";
							foreach($skill["savoir-faire"] as $savoir_faire){
								echo '
								<p class="do_ted">' . $savoir_faire . '</p>';
							}
						}else{
							echo "<h4>Compétences : savoir faire</h4><br>aucun savoir-faire mentionné";
						}
						echo "</div>";
						if(!empty($skill['socialSkills'])){
							echo "<div id='section_2'><table><tr><td id='savoir'>Mes savoir-être</td></tr>
							<tr><td id='je_suis'>Je suis</td></tr>
							<tbody id='colored'>";
							foreach($skill["socialSkills"] as $socialSkill){
								echo '<tr><td>
								<label class="container">' . $socialSkill . '
									<input type="checkbox" checked>
									<span class="checkmark"></span>
								</label></td></tr>';
							}
							echo "</tbody></table>";
						}else{
							echo "<div id='section_2'><h4>Compétences : savoir-être</h4><br>aucun savoir-être mentionné";
						}
						echo "</div></div>";
						echo '</td>';
						/* ------------------------- compétences selon le référent -------------------------------------------*/
						echo '<td id="referent"><h1 class="titre_ref">REFERENT</h1><div id="global_1"><div id="first"><u>';
						echo $skill["referent"]["firstname"] . " " . $skill["referent"]["name"] . "<br><br>";
						echo $skill["referent"]["email"] . "</u><br><br>";
						echo $skill["referent"]["situation"] . "<br>";
						echo "<h3>Compétences selon le référent</h3>";

						if(!empty($skill['savoir-faire_ref'])){
							echo "<h4>Savoir faire</h4>";
							foreach($skill["savoir-faire_ref"] as $savoir_faire){
								echo '
								<p class="do_ted_green">' . $savoir_faire . '</p>';
							}
						}else{
							echo "<h4>Compétences : savoir faire</h4><br>aucun savoir-faire mentionné";
						}
						echo "</div>";
						echo "<div id='second'>";
						if($skill["comment"] != ""){
							echo "<br><h4>Commentaire du référent</h4><p class='comment'>" . $skill["comment"] . "</p><br>";
						}
						if(!empty($skill['socialSkills_ref'])){
							echo "<table><tr><td id='green'>Ses savoir-être</td></tr>
							<tr><td id='back_green'>Il est</td></tr>
							<tbody id='back_table'>";
							foreach($skill["socialSkills_ref"] as $socialSkill){
								echo '<tr><td>
								<label class="container">' . $socialSkill . '
									<input type="checkbox" checked>
									<span class="checkmark_2"></span>
								</label></td></tr>';
							}
							echo "</tbody></table>";
						}else{
							echo "<div id='second'><h5>Compétences : savoir-être</h5><br>aucun savoir-être mentionné";
						}
						echo "</div></div>";
						echo "</td>";
						if($nbrConfirmedSkill%1==0){ // nombre de cases max dans une seule ligne
							echo "</tr><tr class='back'>";
						}
					}
				}
						echo "</tr></table>";
				if($nbrConfirmedSkill == 0){
					echo '<p><br><br>aucune expérience confimée par un référent</p>';
				}


				$nbrToConfirmSkill = 0;
				echo '<h2>Mes expériences non confirmées</h2><table class="general"><tr class="back">';
				foreach($users[$username]["skills"] as $skill){ # boucle pour les expériences non confirmées
					if($skill['status'] == "toConfirm"){
						$nbrToConfirmSkill++;  // compétences selon le jeune uniquement (car pas encore confirmé)
						echo '<td id="jeune_toConfirm"><h1 class="titre_toConfirm">JEUNE</h1><div id="global"><div id="section_1"><h2>' . $skill["environement"] . "</h2><ul><li>description: " . $skill["description"] . "</li><li>début: " . $skill["beginning"] . "</li><li>durée: " . $skill["duration"] . " " . $skill["durationType"] . "</li></ul>";
						echo "<h4>Compétences selon moi</h4>";
						if(!empty($skill['savoir-faire'])){
							echo "<h5>Savoir faire</h5>";
							foreach($skill["savoir-faire"] as $savoir_faire){
								echo '
								<p class="do_ted">' . $savoir_faire . '</p>';
							}
						}else{
							echo "<div id='section_1'><h5>Compétences : savoir faire</h5><br>aucun savoir-faire mentionné";
						}
						echo "</div>";
						if(!empty($skill['socialSkills'])){
							echo "<div id='section_2'><table><tr><td id='savoir'>Mes savoir-être</td></tr>
							<tr><td id='je_suis'>Je suis</td></tr>
							<tbody id='colored'>";
							foreach($skill["socialSkills"] as $socialSkill){
								echo '<tr><td>
								<label class="container">' . $socialSkill . '
									<input type="checkbox" checked>
									<span class="checkmark"></span>
								</label></td></tr>';
							}
							echo "</tbody></table>";
						}else{
							echo "<div id='section_2'><h5>Compétences : savoir-être</h5><br>aucun savoir-être mentionné";
						}
						echo "<h4>Référent</h4>";
						echo $skill["referent"]["firstname"] . " " . $skill["referent"]["name"] . "<br>";
						echo $skill["referent"]["email"] . "<br>";
						echo $skill["referent"]["situation"];
						echo '</td><td class="marge"></td>';
						echo "</div></div>";
						echo '</td>';
						if($nbrToConfirmSkill%1==0){ // nombre de cases max dans une seule ligne
							echo "</tr><tr class='back'>";
						}
					}
				}
						echo "</tr></table>";
				if($nbrToConfirmSkill == 0){
					echo '<p><br><br>aucune expérience non confirmée</p>';
				}

			}else{
				echo '<br><br><br><br><br><br><br><br><p>aucune expérience enregistrée</p>';
			}
			$oldusers = $users;
		?>
	</div>
	<br><br>
	<?php include_once "../footer.html"; ?>
    </main>
    <script src="skills.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>