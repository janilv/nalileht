<?php
?>
<div class="navbar-inverse navbar-fixed-top navbar">
	<div class="container">
		<div class="float-left">
			<a class="text" href="index.php">Kodu</a>
		</div>
		<div class="float-right">
			<a class="text" href="login.php">Logi sisse</a>
		</div>
		<?= isset($_SESSION["userId"]) ? '
		<div class="float-middle">
			<a class="text" href="newmeme.php">Lisa meme</a>
		</div>'
		: ''?>
	</div>
</div>



