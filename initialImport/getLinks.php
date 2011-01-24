<?php

$domain = "http://www.wiscnews.com";
$link = "/bdc/search/?f=html&t=article&l=25&s=start_time&sd=desc&sForm=false&c=news/local/crime_and_courts*&q=%23bdc&sHeading=Police+Beat&o=0&app[0]=editorial";

$beatURLs = array();
$nextLink = true;

while($nextLink) {
	$html = file_get_contents("$domain$link");
	//Links to Individual Pages
	preg_match_all('%<a href="(/bdc/[^"]+)" title="Beaver Dam(.*)">Beaver Dam(.*)</a>%', $html, $links);
	//Links to Previous and Next Article
	preg_match_all('%<p class="search-paging">(\s*)<a href="([^"]+)">&laquo; Previous</a>(\s*)<a href="([^"]+)">Next &raquo;</a>(\s*)</p>%', $html, $nav);
	$link = "";
	
	if (isset($nav[3][0])) {
		$link = html_entity_decode($nav[4][0]);
	} else {
		//Try just a Link to Next Article
		preg_match_all('%<p class="search-paging">(\s*)<a href="([^"]+)">Next &raquo;</a>(\s*)</p>%', $html, $nav);
		if (!isset($nav[2][0])) {
			$nextLink = false;
		} else {
			$link = html_entity_decode($nav[2][0]);
		}
	}
	$beatURLs = array_merge($beatURLs, $links[1]);
}

$doc = new DOMDocument();
$doc->formatOutput = true;

$r = $doc->createElement("links");
$doc->appendChild($r);

foreach($beatURLs as $url) {
	$l = $doc->createElement("link");
	$l->appendChild($doc->createTextNode($url));
	$r->appendChild($l);
}

$doc->saveXML();
chdir('../xml/');
$doc->save("/var/www/bdpb/xml/links.xml");