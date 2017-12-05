<?php

require("functions.php");
require("classes/Photoupload.class.php");

//kui pole sisse logitud, liigume login lehele
if(!isset($_SESSION["userId"])){
	header("Location: login.php");
	exit();
}

$database = "if17_veebiprog12";
$serverHost = "localhost";
$serverUsername = "if17";
$serverPassword = "if17";

	// //foto laadimise osa
	// // $picsDir = "memes";
	// // $picFiles = "";
	// $uploadOk = 1;
	// $picFileTypes = "";
	// $maxWidth = 600;
	// $maxHeight = 400;
	// $notice = "";
	// $myTempImage = "";  
	//$userid = ($_SESSION["userId"]);
	
	//Algab foto laadimise osa
	$target_dir = "memes/";
	// $thumbs_dir = "../../thumbnails/";
	$target_file = "";
	$thumb_file = "";
	$uploadOk = 1;
	$imageFileType = "";
	$maxWidth = 600;
	$thumbsize = 100;
	$maxHeight = 400;
	$marginVer = 10;
	$marginHor = 10;
	$notice = "";
	//Kas vajutati üleslaadimise nuppu
	if(isset($_POST["submit"])) {
		
		if(!empty($_FILES["fileToUpload"]["name"])){
			
			//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]))["extension"]);
			$timeStamp = microtime(1) *10000;
			//$target_file = $target_dir . pathinfo(basename($_FILES["fileToUpload"]["name"]))["filename"] ."_" .$timeStamp ."." .$imageFileType;
			$target_file = "hmv_" .$timeStamp ."." .$imageFileType;
			// $thumb_file = "hmv_" .$timeStamp .".jpg";
		
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				$notice .= "Fail on pilt - " . $check["mime"] . ". ";
				$uploadOk = 1;
			} else {
				$notice .= "See pole pildifail. ";
				$uploadOk = 0;
			}
		
			//Kas selline pilt on juba üles laetud
			if (file_exists($target_file)) {
				$notice .= "Kahjuks on selle nimega pilt juba olemas. ";
				$uploadOk = 0;
			}
			//Piirame faili suuruse
			/*if ($_FILES["fileToUpload"]["size"] > 1000000) {
				$notice .= "Pilt on liiga suur! ";
				$uploadOk = 0;
			}*/
			
			//Piirame failitüüpe
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
				$notice .= "Vabandust, vaid jpg, jpeg, png ja gif failid on lubatud! ";
				$uploadOk = 0;
			}
			
			//Kas saab laadida?
			if ($uploadOk == 0) {
				$notice .= "Vabandust, pilti ei laetud üles! ";
			//Kui saab üles laadida
			} else {		
								
				//kasutame klassi
				$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
				$myPhoto->readExif();
				$myPhoto->resizeImage($maxWidth, $maxHeight);
				// $myPhoto->addWatermark($marginHor, $marginVer);
				//$myPhoto->addTextWatermark($myPhoto->exifToImage);
				// $myPhoto->addTextWatermark("Heade mõtete veeb");
				$notice .= $myPhoto->savePhoto($target_dir, $target_file);
				//$myPhoto->saveOriginal(kataloog, failinimi);
				// $notice .= $myPhoto->createThumbnail($thumbs_dir, $thumb_file, $thumbsize, $thumbsize);
				$myPhoto->clearImages();
				unset($myPhoto);
				
				//lisame andmebaasi
				if(isset($_POST["altText"]) and !empty($_POST["altText"])){
					$alt = $_POST["altText"];
				} else {
					$alt = "Foto";
				}
				if(!isset($_POST["title"])) {
					$_POST["title"] = "Post Title";
				}
				addPhotoData($_POST["title"], $target_file);
				
			}
		
		} else {
			$notice = "Palun valige kõigepealt pildifail!";
		}//kas faili nimi on olemas lõppeb
	}//kas üles laadida lõppeb
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="main.css">
<title>Meme üleslaadimine</title>
</head>
	<body>
	<?php require("header.php")?>
		<div class="meemid">
			
				<div class="meme">
					<span class="text">	
						Memede laadimine
					</span>
						<div class="wrap">
						</div>
				</div>
		</div>
		
	<form action="newmeme.php" method="post" enctype="multipart/form-data">
	</br> </br>
	<div class="meemilaadimine">
		<input type="file" name="fileToUpload" id="fileToUpload"> </br> </br>
		<input type="text" value="Lisa pealkiri" name="title"> </br> </br>
		<input type="submit" value="Lae üles" name="submit">
	</div>
	
	
	</form>
	</body>
</html>