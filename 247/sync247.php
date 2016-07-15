<?php
$FILE_COPY_MAX_COUNT = 3;

// fungsi untuk mengontrol jumlah file
function control_file_count($id, $ext){
	$arr = scandir("content/");
	$files = array();
	
	foreach($arr as $file){
		$names = explode("-", $file);
		$exts = explode(".", $names[1]);
		
		if($names[0] == $id  && $exts[1] == $ext)
			array_push($files, $file);
	}
	
	sort($files);
	if(count($files) > $FILE_COPY_MAX_COUNT){
		for($i=0; $i<count($files)-3; $i++){
			unlink("content/".$files[$i]);
		}
	}
}

// main execution

$html = file_get_contents('http://bandung247.com/partner/view/11/Oafk9Xh6fNV/all');

$doc = new DOMDocument();
$doc->loadHTML($html);

$li = $doc->getElementsByTagName('li');

$screenShots = array();
$videos = array();
foreach ($li as $element) {
	$arr = explode("/", $element->nodeValue);
	
	if($arr[count($arr)-3] == "vid"){
		$id =$arr[count($arr)-2];
		
		array_push($videos, array("id"=>$id, "url"=>$element->nodeValue));
	}else if($arr[count($arr)-3] == "img"){
		$id =$arr[count($arr)-2];
		
		array_push($screenShots, array("id"=>$id, "url"=>$element->nodeValue));
	}
}

$today = $today = date("YmdHis");
foreach($screenShots as $dict){
	$content = file_get_contents($dict["url"]);
	$filename = "content/".$dict["id"]."-".$today.".jpg";
	file_put_contents($filename, $content);
	
	control_file_count($dict["id"], "jpg");
}

foreach($videos as $dict){
	$content = file_get_contents($dict["url"]);
	$filename = "content/".$dict["id"]."-".$today.".mp4";
	file_put_contents($filename, $content);
	
	control_file_count($dict["id"], "mp4");
}


?>