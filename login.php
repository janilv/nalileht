<?php
	require("functions.php");
	
	// muutujad
	$loginUsername = "";
	$loginUsernameError = "";
	
	$notice = "";
	
	//kui sisselogimise nuppu on vajutatud
	if(isset($_POST["signinButton"])) {
		//kasutajanime lahtri kontroll
		if (isset ($_POST["loginUsername"])){
			if (empty ($_POST["loginUsername"])){
				$loginUsernameError ="NB! Sisselogimiseks on vajalik kasutajanimi!";
			} else {
				$loginUsername = $_POST["loginUsername"];
			}
		}
		//parooli lahtri kontroll
		if(!empty($loginUsername) and !empty($_POST["loginPassword"])) {
		echo "Hakkan sisse logima!";
		$notice = signIn($loginEmail, $_POST["loginPassword"]) ;
		}
	} // sisselogimise nupu kontroll
	
?>

<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="main.css">
	<title>Sisselogimine</title>
</head>
<body>
	<div class="wrap">
		<?php require("header.php")?>
		<h1>Sisselogimine</h1>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>">	
			<label>Kasutajanimi</label>
			<input name="loginUsername" type="text" value="<?php echo $loginUsername; ?>"><span><?php echo $loginUsernameError; ?></span>
			<br><br>
			<input name="loginPassword" placeholder="Salasõna" type="password"><span></span>
			<br><br>
			<input name ="signinButton" type="submit" value="Logi sisse"><span><?php echo $notice; ?></span>
		</form>
	</div>
</body>
</html>


