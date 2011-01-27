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

$domain = "http://www.wiscnews.com";
$link = "/bdc/search/?f=html&t=article&l=25&s=start_time&sd=desc&sForm=false&c=news/local/crime_and_courts*&q=%23bdc&sHeading=Police+Beat&o=0&app[0]=editorial";

$beatURLs = array();
$nextLink = true;

chdir('xml/');

while($nextLink) {
	$html = file_get_contents("$domain$link");
	//Links to Individual Articles
	preg_match_all('%<a href="(/bdc/[^"]+)" title="Beaver Dam(.*)">Beaver Dam(?! Warrant)(.*)</a>%i', $html, $links);
	foreach($links[1] as $url) {
		$url = "$domain{$url}";
		$html = file_get_contents($url);
		preg_match_all('%<p class="byline">(.*)Posted: (.*m)\n*\r*%', $html, $byline);
		$time = strtotime($byline[2][0]);
		
		$index = array_search("b$time.xml", $files);
		if ($index === false) {
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
		} else if ($index >= 0) {
			//xml file found, give up looking
			$nextLink = false;
			break;
		}
	}
	
	//Do something here to find the NEXT link.
	//I doubt if the Police Beat is going 
	//to get updated so quick that new content 
	//wouldn't appear on the front page.
}