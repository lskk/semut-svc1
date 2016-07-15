<?php
$filetype = $_GET["type"]; // image / video
$videoID = $_GET["vid"];

$arr = scandir("./");
$ext = $filetype=="video"?"mp4":"jpg";
$files = array();

foreach($arr as $file){
	$names = explode("-", $file);
	$exts = explode(".", $names[1]);
	
	if($names[0] == $videoID  && $exts[1] == $ext)
		array_push($files, $file);
}

sort($files);

$paths = explode("/", $_SERVER[REQUEST_URI]);
$paths[count($paths) - 1] = $files[count($files)-1];
$path = implode("/", $paths);
$redirect = "http://$_SERVER[HTTP_HOST]$path";
header("Location: ".$redirect);
?>