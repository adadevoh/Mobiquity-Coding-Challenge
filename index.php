<?php
session_start();
require_once "vendor/dropbox/dropbox-sdk/lib/Dropbox/autoload.php";
use \Dropbox as dbx;
$appInfo = dbx\AppInfo::loadFromJsonFile("C:\wamp\www\MobiquityChallenge/app-info.json");
$webAuth = new dbx\WebAuthNoRedirect($appInfo, "Mobiquity Challenge");
$authorizeUrl = $webAuth->start();
define("PICS_DIR", "/Pictures");

//print_r($_SESSION);

if(empty($_FILES['webcam'])){
	echo"files webcam empty<br>";
}
else{
	print_r($_FILES['webcam']);
	move_uploaded_file($_FILES['webcam']['temp'], 'webcam.jpg');
	echo"<br>";
}

if(!empty($_POST['authCode'])) {
	$authCode = $_POST['authCode'];
	list($accessToken, $dropboxUserId) = $webAuth->finish($authCode);

	$_SESSION['userID'] = $dropboxUserId;
	$_SESSION['accessToken'] = $accessToken;

}

if(!empty($_SESSION['accessToken'])){
	$dbxClient = new dbx\Client($_SESSION['accessToken'], "Mob Challenge");
	$accountInfo =$dbxClient->getAccountInfo();
	print_r($accountInfo);

	$dbxClient->createFolder(PICS_DIR);
	//file upload
	$f = fopen("testFile.txt", "rb");
	$result = $dbxClient->uploadFile(PICS_DIR."/testFile.txt", dbx\WriteMode::add(), $f);
	fclose($f);

	print_r($result);



}


?>
<!doctype html>
<html>
	<head>
		<title>Mobiquity Challenge</title>
		<meta charset= "utf-8"/>
		<link rel="stylesheet" type="text/css" href="vendor/semantic/ui/dist/semantic.css" />
		<link rel="stylesheet" type="text/css" href="webCam.css">
		<script src="vendor/semantic/ui/dist/semantic.js" type="text/javascript"></script>
		<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="webCam.js"></script>
	</head>
	<body>
		<?php
		if(empty($_SESSION['accessToken'])){
			include"authorizeAppForm.php";
		}
		else{
			include"appForm.php";
		}
		?>
	</body>
</html>