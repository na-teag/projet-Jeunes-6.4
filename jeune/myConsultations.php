<!-- partie à mettre dans chaques pages pour interdire l'accès sans identification-->

<?php
session_start();

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "jeune"){
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

if(isset($_POST['delete'])){
	foreach($_POST['skills'] as $id_skill){
		foreach($other as $key => $value){
			if($key == $id_skill){
				unset($other[$key]);
			}
		}
	}
	$file = fopen('../data.php', 'w');
	fwrite($file, '<?php $users = ' . var_export($users, true) . '; $other = ' . var_export($other, true) . '; ?>');
	fclose($file);
    header("Location: skills.php");
    exit;
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
		echo '<br><table><tr>';
		foreach($other as $key => $tab){ # boucle pour les expériences confirmées
			if($other[$key]['user'] == $username){
				$nbrConsultation++;
				$nbr = 0;
				echo '<td class="marge"><input type="checkbox" name="skills[]" value="' . $key . '">';
				foreach($other[$key]["skills"] as $nbr2 => $key2){
					foreach($users[$username]["skills"] as $nbr3 => $skill){
						if($skill["id"] == $key2){
							if($skill["status"] == "confirmed"){
								echo '</td><td class="marge">' . $skill["environement"];
							}else if($skill["status"] == "archived"){
								echo '</td><td class="marge"><p class="color">' . $skill["environement"] . '</p>';
							}
						}
					}
					$nbr++;
					if($nbr >= 3){
						break;
					}
				}
				if($nbr < 3){
					while($nbr<3){
						echo '</td><td class="marge">';
						$nbr++;
					}
				}
				echo '</td>';
				if($nbrConsultation%1==0){ // nombre de nom d'environement max dans une seule ligne
					echo "</tr><tr>";
				}
			}
		}
		echo "</tr></table><br>Pour chaque partage d'expérience différent, seul les trois premières expériences sont visible.<br>Si des expériences apparaissent grisés, c'est qu'elle sont archivées, les consultants ne les voient donc pas.";
		if($nbrConsultation == 0){
			echo '<p><br><br>aucune expérience confirmée n\'a encore été partagée avec un consultant</p>';
		}else{
			echo '<br><br><input type="submit" name="delete" value="Effacer définitivement ces consultations"><br><br><br><br><br><br>';
		}
	?>
	</form>
	<script src="myConsultations.js"></script>
</body>
</html>