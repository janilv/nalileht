<?php
require("functions.php");
if(isset($_GET['action']) && $_GET['action']) {
	$action = $_GET['action'];
}

switch($action) {
	case "upvote":
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
			$stmt = $mysqli->prepare("SELECT score FROM memes where id = ".$id);
			echo $mysqli->error;
			$stmt->bind_result($score);
			$stmt->execute();
			$stmt->fetch();
			$stmt->close();
			$score++;
			$stmt = $mysqli->prepare("UPDATE memes SET score = '".$score."' where id = ".$id);
			echo $mysqli->error;
			$stmt->execute();
			$stmt->close();
			$mysqli->close();
			echo json_encode(array('score' => $score));
		}
		break;
		
	case "downvote":
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
			$stmt = $mysqli->prepare("SELECT score FROM memes where id = ".$id);
			echo $mysqli->error;
			$stmt->bind_result($score);
			$stmt->execute();
			$stmt->fetch();
			$stmt->close();
			$score--;
			$stmt = $mysqli->prepare("UPDATE memes SET score = '".$score."' where id = ".$id);
			echo $mysqli->error;
			$stmt->execute();
			$stmt->close();
			$mysqli->close();
			echo json_encode(array('score' => $score));
		}
		break;
		
	case "comment":
		if(isset($_GET['id']) && isset($_GET['message'])) {	
			
			// $message = $_GET['message'];
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
			$message = $mysqli->escape_string($_GET['message']);
			$stmt = $mysqli->prepare('INSERT INTO comments (user, text, memeid) VALUES ("'.$_SESSION["userId"].'", "'.$message.'", "'.$_GET["id"].'")');
			echo $mysqli->error;
			$stmt->execute();
			$stmt->close();
			$mysqli->close();
			
			echo json_encode(array());
		}
}
?>