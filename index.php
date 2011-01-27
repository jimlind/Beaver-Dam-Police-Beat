<?php
$files = array();
$dir = opendir('./xml/');

$fileNumber = 0;

while(($file = readdir($dir)) !== false) {
	if($file !== '.' && $file !== '..' && !is_dir($file) && $file{0} === "b") {
		$files[] = $file;
	}
}
rsort($files);

$timestamp = intval(filter_input(INPUT_GET, "b", FILTER_SANITIZE_NUMBER_INT));
$index = array_search("b$timestamp.xml", $files);
if ($index !== false) $fileNumber = $index;

$file = fopen("./xml/".$files[$fileNumber], "r");
$fileContents = fread($file, filesize("./xml/".$files[$fileNumber]));

fclose($file);
//Replace the whitespace with nothing
$fileContents = preg_replace("/>\s+</", "><", $fileContents);
//Do some DOM magic
$doc = new DOMDocument();
$doc->loadXML($fileContents);

$time = intval(substr($files[$fileNumber], 1));


echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>";
echo "<head>";
echo "<title>Beaver Dam Police Beat for ".date("M jS Y - g:i a", $time)."</title>";
echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
echo "<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>";
echo "<link rel='stylesheet' type='text/css' href='css/web.css'>";
echo "<link rel='stylesheet' media='only screen and (max-device-width: 1024px) and (orientation:portrait)' href='css/ipad.portrait.css' type='text/css' />";
echo "<link rel='stylesheet' media='only screen and (max-device-width: 1024px) and (orientation:landscape)' href='css/ipad.landscape.css' type='text/css' />";
echo "<script src='js/jquery-1.4.4.min.js' type='text/javascript'></script>";
echo "<script src='js/jquery.touchwipe.min.js' type='text/javascript'></script>";
echo "<script src='js/web.js' type='text/javascript'></script>";
echo "</head>";
echo "<body>";

echo "<h1>Beaver Dam Police Beat</h1>";
echo "<div id='main'>";
if (isset($files[$fileNumber-1])) {
	echo "<a id='previous' href='index.php?b=".$files[$fileNumber-1]."'><span>Previous</span></a>";
}
if (isset($files[$fileNumber+1])) {
	echo "<a id='next' href='index.php?b=".$files[$fileNumber+1]."'><span>Next</span></a>";
}
echo "<h2>".date("l F j, Y, g:i a", $time)."</h2>";

$xml = $doc->documentElement;
foreach ($xml->childNodes AS $p) {
	$node = $p->nodeValue;
	preg_match_all('%(.{3,30}?)\s(-|—|–)\s*(.*)%s', $node, $output);
	
	if (isset($output[1][0])) {
		echo "<h3>{$output[1][0]}</h3>";
		echo "<p>{$output[3][0]}</p>";
	} else {
		echo "<p>$node</p>";
	}
}

echo "</div>";
echo "<p id='iOS'>Swipe Left or Right for Previous or Next</p>";
echo "<ul id='footer'>";
if (isset($files[$fileNumber-1])) {
	echo "<li><a href='index.php?b=".$files[$fileNumber-1]."'>Previous</a></li>";
}
if (isset($files[$fileNumber+1])) {
	echo "<li><a href='index.php?b=".$files[$fileNumber+1]."'>Next</a></li>";
}
echo "<ul>";

echo "</body>";