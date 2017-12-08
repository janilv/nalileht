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
		//echo "Hakkan sisse logima!";
		$notice = signIn($loginUsername, $_POST["loginPassword"]) ;
		}
	} // sisselogimise nupu kontroll
	
	//kui loo kasutaja nuppu on vajutatud
	if(isset($_POST["signupButton"])) {
			$notice = header("Location: signup.php");
			exit();
	}
	
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
		<div class="container">
			<h1>Sisselogimine</h1>
			<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>">	
				<label>Kasutajanimi: </label>
				<input name="loginUsername" type="text" value="<?php echo $loginUsername; ?>"><span><?php echo $loginUsernameError; ?></span>
				<br><br>
				<label>Parool: </label>
				<input name="loginPassword" type="password"><span></span>
				<br><br>
				<input name="signinButton" type="submit" value="Logi sisse"><span><?php echo $notice; ?></span>
				<input name="signupButton" type="submit" value="Loo kasutaja">
			</form>
		</div>
	</div>
</body>
</html>


