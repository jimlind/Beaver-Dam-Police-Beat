<?php
/*
 * THIS HEADER GIVES YOU PROPER SYMBOL OUTPUT
 */

echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'><head><base href='http://www.wiscnews.com/content/tncms/live/'>";
echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'></head>";
echo "<body>";

$files = array();
$dir = opendir('./xml/');
//$files = readdir($dir);

while(($file = readdir($dir)) !== false) {
	if($file !== '.' && $file !== '..' && !is_dir($file) && $file{0} === "b") {
		$files[] = $file;
	}
}

rsort($files);

$file = fopen("./xml/".$files[0], "r");
$fileContents = fread($file, filesize("./xml/".$files[0]));
fclose($file);
//Replace the whitespace with nothing
$fileContents = preg_replace("/>\s+</", "><", $fileContents);
//Do some DOM magic
$doc = new DOMDocument();
$doc->loadXML($fileContents);

$xml = $doc->documentElement;
foreach ($xml->childNodes AS $p) {
	echo "<p>";
	echo $p->nodeValue;
	echo "</p>";
}

echo "</body>";