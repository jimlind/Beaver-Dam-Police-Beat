<?php
	require "includes/init.php";
	
	$fileObj = new Files(DATA_XML);
	$files = $fileObj->getArray();
	
	$search = new SearchResult();
	$search->setURL($config['domain'], $config['firstResult']);
	$output = $search->process();
	if (count($output) == 0) die; // die if no data
	foreach($output as $url) {
		$article = new Article($config['domain'], $url);
		$time = $article->getTimeStamp();
		$index = array_search("b$time.xml", $files);
		
		if ($index === false) {
			$xml = $article->getXml();
			$xml->save(DATA_XML."/b$time.xml");
		} elseif ($index >= 0) {
			// date was found, so no need to look for more data
			break;
		}
	}