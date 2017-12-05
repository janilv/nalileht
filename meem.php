<?php
	if(isset($_GET["id"])){
		$id= $_GET["id"];
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $ttmysqli->prepare("SELECT * FROM memes WHERE id = ".$ttSelectedLanguage);
		$stmt->bind_result($Result);
		$Errors[] = $mysqli->error;
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();
		$mysqli->close();
	} else {
		$Errors[] = "Sellist meemi ei leitud!";
	}
}
?>

<!DOCTYPE html>
<html>
<head>

	<meta charset="UTF-8">
	<link rel="stylesheet" href="main.css">
	<title>NaliLeht</title>
</head>
<body>
	<div class="wrap">
		<?php require("header.php")?>
		<span>
			
		</span>
	</div>
		<div class="meemid">
			<div class="meme">
				<div class="img">	
					<img src="https://i.imgur.com/h9y2yjs.jpg" alt="" />
				</div>
			</div>
		</div>
</body>
</html>


