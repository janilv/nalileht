<?php

?>
<div class="navbar-inverse navbar-fixed-top navbar">
	<div class="container">
		<div class="float-left">
			<a href="index.php"><img src="lollog.png" border="0" alt="meievaikelogo.png" /></a>
		</div>
		<?= isset($_SESSION["userId"]) ? '
		<div class="float-right">
			<a class="logintext" href="login.php?logout">Logi välja ('.$_SESSION["userlogin"].')</a>
		</div>
		':'
		<div class="float-right">
			<a class="logintext" href="login.php?">Logi sisse</a>
		</div>'
		?>
		<?= isset($_SESSION["userId"]) ? '
		<div class="float-middle">
			<a class="logintext" href="newmeme.php">Lisa meme</a>
		</div>'
		: ''?>
	</div>
</div>



