<?php
	session_start();

	if(!isset($_SESSION["role"]) || $_SESSION["role"] != "admin"){
		header("Location: ../login.php");
		exit;
	}

	require_once '../data.php';
	foreach($users as $user){
		$tab[] = $user['username'];
	}
	sort($tab);


	if(isset($_POST['deconnexion'])){		//partie pour déconnecter l'utilisateur
		$_SESSION = array();
		session_destroy();
		header("Location: ../home.php");
		exit;
	}

	if(isset($_GET['q'])){		// récupérer les données envoyées par le javascript
		$q = strtolower($_GET['q']);
		$len = strlen($q);
		$res = array();
		if($len === 0){
			// Si aucun caractère n'est saisi, retourner tous les choix possibles
			$res = $tab;
		}else{
			foreach($tab as $name){
				if(stristr($q, substr($name, 0, $len))){
					$res[] = $name;
				}
			}
		}
		echo json_encode($res);
		exit;
	}
?>

<html>
<head>
	<title>Jeunes 6.4</title>
	<link rel="icon" type="image/x-icon" href="../images/favicon.ico">
	<link rel="stylesheet" href="search.css">
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

	<br><br>


    <h3>Saisir le nom d’un utilisateur</h3>
    Utilisateurs : <input type="text" id="txt1" onkeyup="getSuggestions(this.value)">

    <p>Suggestions: <div id="div1">
    <?php 
        if(!isset($_GET['q'])){
			$nbr=0;
            foreach($tab as $user){ // avant que l'admin ne rentre qqch dans la barre de recherche, tous les utilisateurs sont affichés
				echo '<a class="' . $user . '" href="manage.php?username=' . urlencode($user) . '">' . $user . '</a><br>';
				$nbr++;
            }
        }
    ?>
    </div></p>

    <script src="search.js"></script>
	<?php
		for($i=14; $i>$nbr; $i--){ // afficher le footer en bas de la page
			echo "<br>";
		}
	?>
</body>
<?php include_once "../footer.html"; ?>
</html>