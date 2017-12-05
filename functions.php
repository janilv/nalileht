<?php
	//peaks requirema andmebaaside faili instead, et seda avalikult ei näeks
	$serverHost = "localhost";
	$serverUsername ="if17";
	$serverPassword = "if17";
	
	$database = "if17_veebiprog12";
	
	session_start();
	
	//sisselogimise funktsioon
	function signIn($username, $password){
		$notice = "";
		//andmebaasi ühendus
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, username, password, email FROM users WHERE username = ?");
		$stmt->bind_param("s", $username);
		$stmt->bind_result($id, $usernameFromDb, $passwordFromDb, $email);
		$stmt->execute();
	
		//kontrollin vastavust
			if($stmt->fetch()) {
				$hash = hash("sha512", $password);
				if($hash == $passwordFromDb) {
					$notice = "Kõik õige! Logisid sisse.";
					
					//sessioonimuutujad
					$_SESSION["userId"] = $id;
					$_SESSION["userEmail"] = $emailFromDb;
					$_SESSION["userlogin"] = $username;
					
					
					//liigume pealehele
					header("Location: index.php");
					exit();
				} else {
					$notice = "Vale parool!";
				}
			} else {
				$notice = "Sellist kasutajat (" .$usernameFromDb .")ei leitud!";
			}
			$stmt->close();
			$mysqli->close();
			return $notice;
	} //sign in lõpp
	
	//kasutaja andmebaasi sisestamine
	function signUp($loginUsername, $loginPassword, $loginEmail){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
		$stmt->bind_param("sss", $loginUsername, $loginPassword, $loginEmail);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	} //sign up lõpp
	
	function addPhotoData($title,  $filename){
		//echo $GLOBALS["serverHost"];
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO memes (title, filename, userid) VALUES (?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("ssi", $title, $filename, $_SESSION["userId"]);
		//$stmt->execute();
		if ($stmt->execute()){
			$GLOBALS["notice"] .= "Foto andmete lisamine andmebaasi õnnestus! ";
		} else {
			$GLOBALS["notice"] .= "Foto andmete lisamine andmebaasi ebaõnnestus! ";
		}
		$stmt->close();
		$mysqli->close();
	}
	
	//logout
	if(isset($_GET['logout'])) {
	//session_unset();
	session_destroy();
	header("Location: index.php");
	}
?>