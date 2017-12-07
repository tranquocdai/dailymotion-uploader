<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);

$name = $_FILES["upload_file"]["name"];
move_uploaded_file($_FILES["upload_file"]["tmp_name"], "videos/".$name);
$testVideoFile = "videos/".$name;
require_once 'sdk/Dailymotion.php';
$api = new Dailymotion();
$apiKey = $_POST['apikey'];
$apiSecret = $_POST['apiSecret'];
$testUser = $_POST['user'];
$testPassword = $_POST['passw'];
$videoTitle = $_POST['title_file'];
$videoCategory = "TV";
$videoDescription = $_POST['videoDescription'];
$videoTags= $_POST['tags'];

if (empty($apiKey) || empty($apiSecret) || empty($testUser) || empty($testPassword)){
    echo "Please fill up All field username, password, Title,Description, Tags ";
	die();
}

try {
	$api->setGrantType(Dailymotion::GRANT_TYPE_PASSWORD, $apiKey, $apiSecret, array('write','delete','manage_videos'), array('username' => $testUser, 'password' => $testPassword));
	//sleep(2);
	$progressUrl = null;
	$url = $api->uploadFile($testVideoFile, null, $progressUrl);
	//var_dump($progressUrl);
	//var_dump($url);
    $result = $api->call('video.create', array('url' => $url, 'title' => $videoTitle , 'channel' => $videoCategory,'description' => $videoDescription, 'tags' => $videoTags, 'published' => true));
	//var_dump ($result);
}
catch (DailymotionAuthRequiredException $e)
{
    // If the SDK doesn't have any access token stored in memory, it tries to
    // redirect the user to the Dailymotion authorization page for authentication.
	echo $e->getMessage();
	unlink($testVideoFile);
    //return header('Location: ' . $api->getAuthorizationUrl());
}
catch (DailymotionAuthRefusedException $e)
{
    // Handle the situation when the user refused to authorize and came back here.
	unlink($testVideoFile);
    echo $e->getMessage();
}
    $videourl = 'http://www.dailymotion.com/video/'.$result['id'];  
    if($result) {  
       ?><a href="<?php echo $videourl; ?>" target='_blank'>Click Here </a> <?php echo "click here to see this video.";  
       echo "Video uploaded successfully on dailymotion.com";
	   unlink($testVideoFile);
     }
	else { echo "OOp Error !";}
	echo "<p>Upload Next Video <a href='https://tool.tranquocdai.com/daily/'>Click Here</a> </p>";

 ?>