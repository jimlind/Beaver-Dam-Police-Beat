<?php
/*
 * Script that pulls Police Beat pages.
 * Saves xml files (UTF8) based on Post timestamps.
 */

$domain = "http://www.wiscnews.com";
chdir('../xml/');
$file = fopen("links.xml", "r");
$fileContents = fread($file, filesize("links.xml"));
fclose($file);
//Replace the whitespace with nothing
$fileContents = preg_replace("/>\s+</", "><", $fileContents);
//Do some DOM magic
$doc = new DOMDocument();
$doc->loadXML($fileContents);

$i = 0;

$xml = $doc->documentElement;
foreach ($xml->childNodes AS $link)
{
	$url = "$domain{$link->nodeValue}";
	$html = file_get_contents($url);

	preg_match_all('%<p class="byline">(.*)Posted: (.*m)\n*\r*%', $html, $byline);
	$time = strtotime($byline[2][0]);

	preg_match_all('%<div id="blox-story-text">(.*?)</div>%s', $html, $paragraphs);
	$paragraphs = $paragraphs[1][0];

	preg_match_all('%<p>(.*?)</p>%s', $paragraphs, $text);
	$text = $text[1];

	$doc = new DOMDocument();
	$doc->formatOutput = true;
	
	$r = $doc->createElement("beat");
	$doc->appendChild($r);
	
	foreach($text as $line) {
		$l = $doc->createElement("line");
		$l->appendChild($doc->createTextNode($line));
		$r->appendChild($l);
	}
	
	$doc->saveXML();
	$doc->save("b$time.xml");
}