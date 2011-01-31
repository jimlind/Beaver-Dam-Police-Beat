<?php
	require "includes/init.php";
	
	$fileObj = new Files(DATA_XML);
	$files = $fileObj->getArray();
	
	$fileNum = 0;
	
	$timestamp = intval(filter_input(INPUT_GET, "b", FILTER_SANITIZE_NUMBER_INT));
	
	$index = array_search("b$timestamp.xml", $files);
	if ($index !== false) $fileNum = $index;

	//Previous and Next links
	$next = false;
	$prev = false;
	if (isset($files[$fileNum-1])) {
		$prev = "index.php?b=" . substr($files[$fileNum-1], 1 ,10);
	}
	if (isset($files[$fileNum+1])) {
		$next = "index.php?b=" . substr($files[$fileNum+1], 1, 10);		
	}
	
	$time = intval(substr($files[$fileNum], 1));

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
	if ($prev) {
		echo "<a id='previous' href='$prev'><span>Previous</span></a>";
	}
	if ($next) {
		echo "<a id='next' href='$next'><span>Next</span></a>";
	}
	echo "<h2>".date("l F j, Y, g:i a", $time)."</h2>";
	
	$doc = new DOMDocument();
	$doc->load(DATA_XML . "/" . $files[$fileNum]);
	$root = $doc->documentElement;
	$node = $root->firstChild;
	
	while ($node) {
		switch ($node->nodeName) {
			case "title":
				$content = $node->firstChild;
				$output = $content->nodeValue;
				echo "<h3>$output</h3>";
				break;
			case "line":
				$content = $node->firstChild;
				$output = $content->nodeValue;
				echo "<p>$output</p>";
				break;
		}
		$node = $node->nextSibling;
	}
	
	echo "</div>";
	echo "<p id='iOS'>Swipe Left or Right for Previous or Next</p>";
	echo "<ul id='footer'>";
	if ($prev) {
		echo "<a id='previous' href='$prev'>Previous</a>";
	}
	if ($next) {
		echo "<a id='next' href='$next'>Next</a>";
	}
	echo "<ul>";	
	echo "</body>";