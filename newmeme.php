<?php
$database = "if17_veebiprog12";
$serverHost = "localhost";
$serverUsername = "if17";
$serverPassword = "if17";

	//foto laadimise osa
	$picsDir = "memes";
	$picFiles = "";
	$uploadOk = 1;
	$picFileTypes = "";
	$maxWidth = 600;
	$maxHeight = 400;
	$notice = "";
	$myTempImage = "";
	$userid = 2;
	

	//Kas vajutati nuppu
	if(isset($_POST["submit"])) {
		
		//kas fail on valitud
		if(!empty($_FILES["fileToUpload"]["name"])){
			//fikseerin nimelaiendi
			$picFileTypes = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
			
			//Kas on pildifail
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				echo "Fail on korrektne - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				echo "Fail ei ole pilt.";
				$uploadOk = 0;
			}
			
			//kas faili suurus on õige
			if ($_FILES["fileToUpload"]["size"] > 1000000) {
				echo "Vabandust, su fail on liiga suur.";
				$uploadOk = 0;}

			//kontrollime failitüüpi
			if($picFileTypes != "jpg" && $picFileTypes != "png" && $picFileTypes != "jpeg" && $picFileTypes != "gif") {
				echo "Ainult jpg, jpeg, png & gif failid on lubatud.";   
			} 
			//Kas saab laadida
			if($uploadOk == 0) {
				$notice .= "Vabandust, pilti ei laetud üles";
			}else {//kas fail oli valitud lõppes
				$notice .="Palun vali fail";
			}
			
			
			//lähtudes failist loome objekti
			if ($picFileTypes == "jpg" or $picFileTypes == "jpeg"){
				$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
			}
			if ($picFileTypes == "png" or $picFileTypes == "png"){
				$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
			}
			if ($picFileTypes == "gif" or $picFileTypes == "gif"){
				$myTempImage = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
			}
			
			//teeme kindlaks originaalsuuruse
			$imageWidth = imagesx($myTempImage);
			$imageHeight = imagesy($myTempImage);
			if($imageWidth > $imageHeight){
				$sizeRatio = $imageWidth / $maxWidth;
			} else{
				$sizeRatio = $imageHeight / $maxHeight;
			}
		
			//muudame suurust
			$myImage = resizeImage($myTempImage, $imageWidth, $imageHeight, round($imageWidth / $sizeRatio), round($imageHeight / $sizeRatio));
			
			//faili salvestamine
			if ($picFileTypes == "jpg" or $picFileTypes == "jpeg"){
				if(imagejpeg($myImage, $picFiles, 90)){
					$notice = "Fail: " .$_FILES["fileToUpload"]["name"] ." laeti üles!";
				}
				else{
					$notice = "Faili üleslaadimine ebaõnnestus!";
				}
			}
				if ($picFileTypes == "png"){
			if(imagejpeg($myImage, $picFiles, 6)){
				$notice = "Fail: " .$_FILES["fileToUpload"]["name"] ." laeti üles!";
			}
			else{
				$notice = "Faili üleslaadimine ebaõnnestus!";
			}
				}
				if ($picFileTypes == "gif"){
			if(imagejpeg($myImage, $picFiles)){
				$notice = "Fail: " .$_FILES["fileToUpload"]["name"] ." laeti üles!";
			}
			else{
				$notice = "Faili üleslaadimine ebaõnnestus!";
			}
				}
			
			imagedestroy($myTempImage);
			imagedestroy($myImage);
			
			
		}
	
	}
			
//loome andmebaasiphenduse
$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	
//valmistame ette käsu andmebaasiserverile
$stmt = $mysqli->prepare("INSERT INTO memes (title, userid) VALUES (?, ?)");
echo $mysqli->error;
$stmt->bind_param("si", $picFiles, $userid);
//$stmt->execute();
if ($stmt->execute()){
	echo "\n Õnnestus!";
} else {
	echo "\n Tekkis viga : " .$stmt->error;}
	
function resizeImage($image, $origW, $origH, $w, $h){
	$newImage = imagecreatetruecolor($w, $h);
	imagecopyresampled($newImage, $image, 0, 0, 0, 0, $w, $h, $origW, $origH);
	return $newImage;
}
$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="main.css">
<title>Meme üleslaadimine</title>
</head>
	<body>
	<form action="newmeme.php" method="post" enctype="multipart/form-data">
    <input type="file" name="fileToUpload" id="fileToUpload"> 
    <input type="submit" value="Lae üles" name="submit"> </br> </br>
	
	<p><a href="index.php">Tagasi pealehele</a></p>
	
	</form>
	</body>
</html>