<!-- partie à mettre dans chaques pages pour interdire l'accès sans identification-->

<?php
session_start();

if(!isset($_SESSION["role"]) || ($_SESSION["role"] != "jeune" && $_SESSION["role"] != "admin")){
    header("Location: ../login.php");
    exit;
}


$username = $_SESSION["username"];
require '../data.php';

if(isset($_POST['deconnexion'])){// partie pour déconnecter l'utilisateur
    $_SESSION = array();
    session_destroy();
    header("Location: ../home.php");
    exit;
}

if(isset($_POST['delete'])){ // supprimer l'accès de consultation des expériences
	$nbr=0;
	foreach($_POST['skills'] as $id_skill){
		$nbr++;
		foreach($other as $key => $value){
			if($key == $id_skill){
				unset($other[$key]);
			}
		}
	}
	if($nbr != 0){
		$file = fopen('../data.php', 'w'); // mettre a jour le fichier data
		fwrite($file, '<?php $users = ' . var_export($users, true) . '; $other = ' . var_export($other, true) . '; ?>');
		fclose($file);
    	header("Location: skills.php");
    	exit;
	}else{
		$message = "aucune compétence séléctionnée";
	}
}
?>

<!-- partie classique de la page-->



<html>
<head>
	<title>Jeunes 6.4</title>
	<link rel="icon" type="image/x-icon" href="../images/favicon.ico">
	<link rel="stylesheet" href="myConsultations.css">
	<meta charset="UTF-8">
</head>
<body>
	<table class="bandeau">
		<tr>
			<td rowspan="2"><a href="../home.php"><img src="../images/logo.svg"><img></a></td>
			<td><h1 id="taille1">JEUNE</h1></td>
		</tr>
		<tr>
			<td><p id="taille2">Je donne de la valeur à mon engagement</p></td>
		</tr>
	</table>
	<div id="bouton">
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
	<br>
	<br>
	<br>
	<h3>Mes expériences visibles par des consultants</h3>
	<br>
	<button type="button" onclick="check()">tout cocher</button>
	<button type="button" onclick="uncheck()">tout décocher</button>
	<form method="POST">
	<?php // cette partie est tirée de select.php
		$nbrConsultation = 0;
		echo '<br><table><tr class="back">';
		foreach($other as $key => $tab){ // afficher tous les partage de compétences au nom du jeune
			if($other[$key]['user'] == $username && $other[$key]['status'] == 'consultant'){
				$nbrConsultation++;
				$nbr = 0;
				echo '<td class="marge"><input type="checkbox" name="skills[]" value="' . $key . '"></td><td class="marge">' . $other[$key]["email"];
				if($other[$key]["skills"] != 'all'){
					foreach($other[$key]["skills"] as $nbr2 => $key2){
						foreach($users[$username]["skills"] as $nbr3 => $skill){
							if($skill["id"] == $key2){
								if($skill["status"] == "confirmed"){
									echo '</td><td class="marge">' . $skill["environement"];
								}else if($skill["status"] == "archived"){ // la case apparaissant grisée si la compétences est archivée
									echo '</td><td class="marge"><p class="color">' . $skill["environement"] . '</p>';
								}
							}
						}
						$nbr++;
						if($nbr >= 4){ // on affiche que 4 experiences max
							break;
						}
					}
				}else{
					echo "</td><td colspan='4' class='marge'>toutes les compétences sont partagées.";
					$nbr=4;
				}
				if($nbr < 4){
					while($nbr<4){ // si il y a moins de 4 experiences, on ajoute des case vides pour que les tableaux aient la même taille
						echo '</td><td class="marge">';
						$nbr++;
					}
				}
				echo '</td>';
				if($nbrConsultation%1==0){ // nombre de case max dans une seule ligne
					echo "</tr><tr class='back'>";
				}
			}
		}
		
		if($nbrConsultation != 0){
			echo "</tr></table><br>Pour chaque partage d'expérience différent, seul les quatre premières expériences sont visibles.<br>Si des expériences apparaissent grisées, c'est qu'elles sont archivées, les consultants ne les voient donc pas.";
		}
		if($nbrConsultation == 0){
			echo '<p><br><br><br><br><br>aucune expérience confirmée n\'a encore été partagée avec un consultant<br><br><br><br><br><br></p>';
		}else{
			echo '<br><br><button class="red" type="submit" name="delete" ">Interdire l\'accès à ces expériences<br>à leur consultant</button><span id="message">';
			if(isset($message)){
				echo $message;
			}
			echo '</span><br><br><br><br><br><br>';
		}
	?>
	</form>
	<script src="myConsultations.js"></script>
</body>
<?php include_once "../footer.html"; ?>
</html>