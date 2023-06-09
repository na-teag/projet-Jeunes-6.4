<?php

    session_start();
	$_SESSION = array();
	session_destroy();
	session_start();

    $id = $_GET['id'];
    require_once '../data.php';
	//on regarde si le consultant a le droit d'accès à l'expérience
    if(!array_key_exists($id, $other)){
	    $forbidden = 1;
    }else{
	    $forbidden = 0;
		$_SESSION["role"] = "consultant";
    	$_SESSION["username"] = $id;
    	$username = $other[$_SESSION["username"]]['user'];
	}
    
    
    
    if(isset($_POST['deconnexion'])){ // partie pour déconnecter l'utilisateur
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
	<link rel="stylesheet" href="consultation.css">
	<meta charset="UTF-8">
</head>
<body>
	<!-- bandeau contenant le logo et le status de l'utilisateur -->
	<table class="bandeau">
		<tr>
			<td rowspan="2"><a class="rien" href="../home.php"><img src="../images/logo.svg"><img></a></td>
			<td><h1 id="taille1">CONSULTANT</h1></td>
		</tr>
		<tr>
			<td><p id="taille2">Je donne de la valeur à ton engagement</p></td>
		</tr>
	</table>
	<!-- bouton qui permet la déconnexion à la session -->
	<div id="bouton">
			<form method="POST">
				<button class="deconnexion" type="submit" name="deconnexion">Me Déconnecter</button>
			</form>
	</div>
	<!-- barre de naviqation permettant de naviguer entre les pages principales -->
	<div class="navbar">
		<ul>
			<li id="bandeau"><a class="jeune" href="../home.php">JEUNE </a></li>
			<li id="bandeau"><a class="referent" href="../referent_info.php" >RÉFÉRENT </a></li>
			<li id="bandeau"><a class="consultant" href="../consultant_info.php">CONSULTANT </a></li>
			<li id="bandeau"><a class="partenaires" href="../partenaires.php" >PARTENAIRES</a></li>
		</ul>
	</div>
	<br>
	<br>
    <br>
	<!--Information sur le jeune-->
	<div id="info_jeune">
    	<h4>informations sur le jeune</h4>
    	nom : <?php echo $users[$username]['name']; ?><br>
    	prénom : <?php echo $users[$username]['firstname']; ?><br>
    	date de naissance : <?php echo $users[$username]['birth']; ?><br>
    	email : <?php echo $users[$username]['email']; ?>
	</div>
    <br>
    <br>
    <br>
    <h4>Expériences du jeune</h4>
    <br>
    <br>
	<!-- bloc contenant les informations du jeune et du référent sur la ou les expériences du jeune -->
    <div id="liste">
		<?php
			$nbrConfirmedSkill = 0;
			echo '<br><table><tr class="back">';

			if($other[$id]["skills"] != "all"){
						//si le jeune à choisi de ne montrer que certaines compétences
				foreach($other[$id]["skills"] as $number => $id_skill){
					if($users[$username]["skills"][$number]['id'] == $id_skill){ # vérifier que les expériences enregistrées sont toujours disponibles
            	        $skill = $users[$username]["skills"][$number];
						if($users[$username]["skills"][$number]['status'] == "confirmed"){
							$nbrConfirmedSkill++;
							//récupération des informations concernant l'expérience du jeune
							echo '<td class="marge"><h2>' . $skill["environement"] . "</h2><ul><li>description: " . $skill["description"] . "</li><li>début: " . $skill["beginning"] . "</li><li> durée: " . $skill["duration"] . " " . $skill["durationType"] . "</li></ul>";
							echo "<h3>Compétences selon moi</h3>";
							if(!empty($skill['socialSkills'])){
								echo "<h5>Savoir-être</h5><ol>";
								foreach($skill["socialSkills"] as $socialSkill){
									echo "<li>" . $socialSkill . "</li>";
								}
								echo "</ol>";
							}else{
								echo "<h5>Compétences : savoir-être</h5><br>aucun savoir-être mentionné";
							}
							if(!empty($skill['savoir-faire'])){
								echo "<h5>Savoir faire</h5><ol>";
								foreach($skill["savoir-faire"] as $savoir_faire){
									echo "<li>" . $savoir_faire . "</li>";
								}
								echo "</ol>";
							}else{
								echo "<h5>Compétences : savoir faire</h5><br>aucun savoir-faire mentionné";
							}
							//récupération des informations concernant l'avis du référent par rapport au jeune
							echo '</td><td class="marge">';
							echo "<h4>Référent</h4>";
							echo $skill["referent"]["firstname"] . " " . $skill["referent"]["name"] . "<br><br>";
							echo $skill["referent"]["email"] . "<br><br>";
							echo $skill["referent"]["situation"] . "<br>";
							echo "<h3>Compétences selon le référent</h3>"; // selon le referent
							if(!empty($skill['socialSkills_ref'])){
								echo "<h5>Savoir-être</h5><ol>";
								foreach($skill["socialSkills_ref"] as $socialSkill){
									echo "<li>" . $socialSkill . "</li>";
								}
								echo "</ol>";
							}else{
								echo "<h5>Compétences : savoir-être</h5><br>aucun savoir-être mentionné";
							}
							if(!empty($skill['savoir-faire_ref'])){
								echo "<h5>Savoir faire</h5><ol>";
								foreach($skill["savoir-faire_ref"] as $savoir_faire){
									echo "<li>" . $savoir_faire . "</li>";
								}
								echo "</ol>";
							}else{
								echo "<h5>Compétences : savoir faire</h5><br>aucun savoir-faire mentionné";
							}
							echo '</td><td class="marge">';
							if($skill["comment"] != ""){
								echo "<br><h5>Commentaire du référent</h5><br><p class='comment'>" . $skill["comment"] . "</p><br>";
							}
							echo "</td>";
							if($nbrConfirmedSkill%1==0){ // nombre de cases max dans une seule ligne
								echo "</tr><tr class='back'>";
							}
						}else if($users[$username]["skills"][$number]['status'] == "confirmed"){
							$nbrArchivedSkill++;
						}

					}
				}


			}else{

						// si le jeune a choisi de montrer toutes les compétences y compris celle futures
				foreach($users[$username]["skills"] as $skill){
					if($skill['status'] == "confirmed"){
						$nbrConfirmedSkill++;
						echo '<td class="marge"><h2>' . $skill["environement"] . "</h2><ul><li>description: " . $skill["description"] . "</li><li>début: " . $skill["beginning"] . "</li><li> durée: " . $skill["duration"] . " " . $skill["durationType"] . "</li></ul>";
						echo "<h3>Compétences selon moi</h3>";
						if(!empty($skill['socialSkills'])){
							echo "<h5>Savoir-être</h5><ol>";
							foreach($skill["socialSkills"] as $socialSkill){
								echo "<li>" . $socialSkill . "</li>";
							}
							echo "</ol>";
						}else{
							echo "<h5>Compétences : savoir-être</h5><br>aucun savoir-être mentionné";
						}
						if(!empty($skill['savoir-faire'])){
							echo "<h5>Savoir faire</h5><ol>";
							foreach($skill["savoir-faire"] as $savoir_faire){
								echo "<li>" . $savoir_faire . "</li>";
							}
							echo "</ol>";
						}else{
							echo "<h5>Compétences : savoir faire</h5><br>aucun savoir-faire mentionné";
						}
						echo '</td><td class="marge">';
						echo "<h4>Référent</h4>";
						echo $skill["referent"]["firstname"] . " " . $skill["referent"]["name"] . "<br><br>";
						echo $skill["referent"]["email"] . "<br><br>";
						echo $skill["referent"]["situation"] . "<br>";
						echo "<h3>Compétences selon le référent</h3>";
						if(!empty($skill['socialSkills'])){
							echo "<h5>Savoir-être</h5><ol>";
							foreach($skill["socialSkills"] as $socialSkill){
								echo "<li>" . $socialSkill . "</li>";
							}
							echo "</ol>";
						}else{
							echo "<h5>Compétences : savoir-être</h5><br>aucun savoir-être mentionné";
						}
						if(!empty($skill['savoir-faire'])){
							echo "<h5>Savoir faire</h5><ol>";
							foreach($skill["savoir-faire"] as $savoir_faire){
								echo "<li>" . $savoir_faire . "</li>";
							}
							echo "</ol>";
						}else{
							echo "<h5>Compétences : savoir faire</h5><br>aucun savoir-faire mentionné";
						}
						echo '</td><td class="marge">';
						if($skill["comment"] != ""){
							echo "<br><h5>Commentaire du référent</h5><br><p class='comment'>" . $skill["comment"] . "</p><br>";
						}
						echo "</td>";
						if($nbrConfirmedSkill%1==0){ // nombre de cases max dans une seule ligne
							echo "</tr><tr class='back'>";
						}
					}
				}
			}



			//le jeune a supprimer ou archiver la totalité des expériences que le consultant pouvait voir
			if($nbrConfirmedSkill == 0){
				if($nbrArchivedSkill != 0){
                	echo '<br><p>il semblerait que ces expériences ne soient plus consultables</p><br><br>';
				}else{
					echo '<br><br><br><br><br><br><br><br><p>il semblerait qu\'il n\'y ai aucune expérience consultable enregistrée sur ce profil</p>';
				}
            }
            echo "</tr></table>";
		?>
	</div>
	
	<?php 
	//s'il n'a pas accès le consultant ne dois pas voir le contenu
		if($forbidden == 1){
			echo '<script>
				document.getElementById("liste").innerHTML = "";
				document.getElementById("info_jeune").innerHTML = "";
			</script>';
			echo 'aucune expérience ne semble correspondre, <a href="../consultant_info.php">retrouvez ici les informations relative aux consultants</a>';
		}
	?>

</body>
<!--inlus le footer-->
<?php include_once "../footer.html"; ?>
</html>