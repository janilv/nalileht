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
	
	function loadAllMemes() {
		$memes = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT * FROM memes ORDER BY id DESC");
		$stmt->bind_result($Result["id"], $Result["title"],$Result["filename"],$Result["userid"],$Result["score"]); 
		$Errors[] = $mysqli->error;
		$stmt->execute();
		while($stmt->fetch()) {
			$memes .= 
			'<div class="meemid">
				<div class="meme">
					<a target="_blank" href="post.php?id='.$Result["id"].'">
						<div class="title">	
							'.$Result["title"].'
						</div>
						<img class="imgmeme" src="memes/'.$Result["filename"].'" alt="" />
					</a>
					<div class="scores">
						<button class="upvote" id="upvote_'.$Result["id"].'" onclick="upvote('.$Result["id"].')"><i class="fa fa-chevron-up" aria-hidden="true"></i></button>
						<button id="downvote_'.$Result["id"].'"onclick="downvote('.$Result["id"].')" class="downvote"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>
						<span><span id="upvote_count_'.$Result["id"].'">'.$Result["score"].'</span> punkti</span>
					</div>
				</div>
			</div>';
		}
		$stmt->close();
		$mysqli->close();
		
		Return $memes;
	}
	
	function loadMeme($id) {
		$meme = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT m.id, m.title, m.filename, m.score, u.username FROM memes m inner join users u on m.userid = u.id where m.id =".$id);
		$stmt->bind_result($Result["id"], $Result["title"],$Result["filename"],$Result["score"],$Result["username"]); 
		$Errors[] = $mysqli->error;
		$stmt->execute();
		while($stmt->fetch()) {
			$meme .= 
			'
			<div class="post">
				<div class="title">	
					<h1>'.$Result["title"].' ('.$Result["username"].')</h1>
				</div>
				<div class="meme-pic">
					<img class="meme-view" src="memes/'.$Result["filename"].'" alt="" />
					<div class="scores">
						<button class="upvote" id="upvote_'.$Result["id"].'" onclick="upvote('.$Result["id"].')"><i class="fa fa-chevron-up" aria-hidden="true"></i></button>
						<button id="downvote_'.$Result["id"].'"onclick="downvote('.$Result["id"].')" class="downvote"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>
						<span><span id="upvote_count_'.$Result["id"].'">'.$Result["score"].'</span> punkti</span>
					</div>
				</div>
			</div>';
		}
		$stmt->close();
		$mysqli->close();
		
		Return $meme;
	}
	
	function loadComments($id) {
		$comment = '';
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT count(*) FROM comments where memeid =".$id);
		$stmt->bind_result($count); 
		$Errors[] = $mysqli->error;
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();
		
		if(isset($_SESSION['userId'])) {
		$comment .= '
			<h3><span id="comment_count">'.$count.'</span> Kommentaarimiseks logi sisse</h3>
			<textarea id="meme-comment-box" class="meme-comment" rows="4" cols="50" placeholder="Kommentaar"></textarea><br>
			<button onclick="Comment('.$id.')" class="meme-comment-btn">Kommenteeri</button>	
			<div id="comments">';
		}
		else {
			$comment .= '
			<h3><span id="comment_count">'.$count.'</span> Kommentaari</h3>
			<textarea disabled id="meme-comment-box" class="meme-comment" rows="4" cols="50" placeholder="Kommentaar"></textarea><br>
			<button disabled class="meme-comment-btn">Kommenteerimiseks logi sisse</button>	
			<div id="comments">';
		}
		
		$stmt = $mysqli->prepare("SELECT u.username, c.text FROM `comments` c 
									inner join users u on c.user = u.id
									WHERE memeid = ".$id."
									ORDER BY c.id DESC");
									
		$stmt->bind_result($Result["username"], $Result["text"]); 
		$Errors[] = $mysqli->error;
		$stmt->execute();
		while($stmt->fetch()) {
			$comment .= 
			'
			<div class="comment">
				<h4>'.$Result["username"].'</h4>
				<br>'.$Result["text"].'       
			</div>';
		}
		$stmt->close();
		$mysqli->close();
		
		Return $comment;
	}
	
	
	//logout
	if(isset($_GET['logout'])) {
	//session_unset();
	session_destroy();
	header("Location: index.php");
	}
?>