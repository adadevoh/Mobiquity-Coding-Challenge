<?php
session_start();
require_once "vendor/dropbox/dropbox-sdk/lib/Dropbox/autoload.php";
use \Dropbox as dbx;
$appInfo = dbx\AppInfo::loadFromJsonFile("C:\wamp\www\MobiquityChallenge/app-info.json");
$webAuth = new dbx\WebAuthNoRedirect($appInfo, "Mobiquity Challenge");
$authorizeUrl = $webAuth->start();
define("PICS_DIR", "/Pictures");

echo'
	<div class="ui grid">
		<div class="six wide column">
			<div class="ui raised segment">
				<div class="ui warning message">
					<div class="header"> Please create folder "uploads" in project folder if not already done</div>
				</div>
			</div>
		</div>
	</div>';

if(!empty($_POST['picture'])) {
	$binary_data = base64_decode($_POST['picture']);
	$result = file_put_contents('uploads/webcam.jpg', $binary_data);
	if(!$result) die('Could not save image!  Check file permissions
						or please create an "uploads" folder in your project<br>.');	
}
else{
	//add logic later
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
	

	$dbxClient->createFolder(PICS_DIR);/*echo $_POST['picUpload'];*/
	if(!empty($_POST['picUpload']) ){
		$f = fopen("uploads/webcam.jpg", "rb");
		$result = $dbxClient->uploadFile(PICS_DIR."/image.jpg", dbx\WriteMode::add(), $f);
		fclose($f);
	}
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
		<script type="text/javascript">
			$(document).ready(function() {
				Webcam.set({
					width: 320,
					height: 240,
					image_format: 'jpeg',
					jpeg_quality: 90
				});
				Webcam.attach('#camera');
			});
			function take_snapshot() {
				Webcam.snap( function(data_uri) {
					var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
					$('input[name=picture]').val(raw_image_data);
					$('form').submit();

				});
			}
	</script>
	</head>
	<body>
		<?php
		if(empty($_SESSION['accessToken'])){
			include"authorizeAppForm.php";
		}
		else{
			?>
			<div class="ui grid">
				<div class="six wide column">
					<div class="ui raised segment">
						<div id="camera" ></div>
						<form method="post" action="index.php">
		    				<input id="mydata" type="hidden" name="picture" value=""/>
		    				<input type="button" class="green ui button" value="Take Snapshot" onClick="take_snapshot()" />
						</form>
					</div>

				</div>
				<div class="six wide column">
					<div id="preview" class="ui raised segment"><?php 
						$d = rand();
						echo'<img id="prev"  src="uploads/webcam.jpg?'.$d.'"/>';
						?>				
						<form method="post" action="index.php">
							<input id="picUpload" type="hidden" name="picUpload" value="pizzur" />
							<input type="submit" class="blue ui button"  value="upload to DropBox" />
						</form>
					</div>
				</div>
			</div>

			<div class="ui raised segment">
				<p>List of items in folder. Sorry, I couldnt figure out how to proper display them in time for submission</p>
				<?php
				$folderMetadata = $dbxClient->getMetadataWithChildren("/");
				print_r($folderMetadata['contents']);
				?>
			</div>
			<?php
		}
			?>
	</body>
</html>