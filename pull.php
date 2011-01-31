<?php
	require "includes/init.php";
	
	$fileObj = new Files(DATA_XML);
	$files = $fileObj->getArray();
	
	if (count($files) > 0) {
		echo "XML files exist.  Pull not neccesary.";
		die;
	}
	
	$search = new SearchResult();
	$nextButtonFound = true;
	$urlPath = $config['firstResult'];
	
	while($nextButtonFound) {
		$search->setURL($config['domain'], $urlPath);
		$output = $search->process();
		
		if (count($output) == 0) die; // die if no data
		foreach($output as $url) {
			$article = new Article($config['domain'], $url);
			$time = $article->getTimeStamp();
			$xml = $article->getXml();
			$xml->save(DATA_XML."/b$time.xml");
		}
		
		$urlPath = $search->findNext();
		if ($urlPath === false) $nextButtonFound = false;
	}
	
	