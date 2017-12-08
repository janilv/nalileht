<?php
	require("functions.php");
	
	// muutujad
	$loginUsername = "";
	$loginUsernameError = "";
	$loginPasswordError = "";
	$loginEmail = "";
	$loginEmailError = "";
	$notice = "";
	
	
	//kui loo kasutaja nuppu on vajutatud
	if(isset($_POST["signupButton"])) {
	
		//kasutajanime lahtri kontroll
		if (isset ($_POST["loginUsername"])){
			if (empty ($_POST["loginUsername"])){
				$loginUsernameError ="Kasutajanimi on sisestamata!";
			} else {
				$loginUsername = $_POST["loginUsername"];
			}
		}
		//parooli lahtri kontroll
		if (isset ($_POST["loginPassword"])){
			if(empty($_POST["loginPassword"])){
				$loginPasswordError = "Parool on sisestamata!";
			} else {
				$loginPassword = $_POST["loginPassword"];
			}
		}
		//e-maili lahtri kontroll
		if (isset ($_POST["loginEmail"])){
			if(empty($_POST["loginEmail"])){
				$loginEmailError = "E-mail on sisestamata!";
			} else {
				$loginEmail = $_POST["loginEmail"];
			}
		}	
		//kui erroreid pole, siis registreerub sisse
		if(empty($loginUsernameError) and empty($loginPasswordError) and empty($loginEmailError)) {
			$notice = "Kasutaja on registreeritud!";
			$loginPassword = hash("sha512", $_POST["loginPassword"]);
			signUp($loginUsername, $loginPassword, $loginEmail);
		}
			
	} // loo kasutaja nupu kontroll
?>

<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="main.css">
	<title>Kasutaja loomine</title>
</head>
<body>
	<div class="wrap">
		<?php require("header.php")?>
		<div class="container">
			<h1>Kasutaja loomine</h1>
			<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>">	
				<label>Kasutajanimi: </label>
				<input name="loginUsername" type="text" value="<?php echo $loginUsername; ?>"><span><?php echo $loginUsernameError; ?></span>
				<br><br>
				<label>Parool: </label>
				<input name="loginPassword" type="password"><span><?php echo $loginPasswordError; ?></span>
				<br><br>
				<label>E-mail: </label>
				<input name="loginEmail" type="email" value="<?php echo $loginEmail; ?>"><span><?php echo $loginEmailError; ?></span>
				<br><br>
				<input name="signupButton" type="submit" value="Loo kasutaja"><span><?php echo $notice; ?></span>
			</form>
		</div>
	</div>
</body>
</html>
