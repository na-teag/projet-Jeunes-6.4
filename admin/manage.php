<?php
	session_start();

	if(!isset($_SESSION["role"]) || $_SESSION["role"] != "admin"){
		header("Location: ../login.php");
		exit;
	}

	require_once '../data.php';

	if(isset($_GET['username'])){
        if(isset($users[$_GET['username']])){ // si l'utilisateur demandé existe, accéder à sa page en prenant son username, mais en gardant le role d'admin
            $_SESSION["username"]=$_GET['username'];
            header("Location: ../jeune/skills.php");
		    exit;
        }else{
            echo 'aucun utilisateur "' . $_GET['username'] . '" trouvé dans la base de données.<br><br>'; // sinon, afficher un message d'erreur
            echo '<a href="search.php">Retour</a>';
        }	
	}

?>