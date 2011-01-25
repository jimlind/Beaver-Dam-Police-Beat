<?php
/*
 * THIS HEADER GIVES YOU PROPER SYMBOL OUTPUT
 */

echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'><head>";
echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'></head>";
echo "<body>";

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
echo "<h2>".date("r", $time)."</h2>";

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

echo "<ul>";
if (isset($files[$fileNumber-1])) {
	echo "<li><a href='display.php?b=".$files[$fileNumber-1]."'>Previous</a></li>";
}
if (isset($files[$fileNumber+1])) {
	echo "<li><a href='display.php?b=".$files[$fileNumber+1]."'>Next</a></li>";
}
echo "<ul>";
echo "</body>";