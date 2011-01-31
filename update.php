<?php
	require "includes/init.php";
	require "includes/twitter.php";
	require "includes/googl.php";
	require "includes/keys.php";
	
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

			$twitter = new Twitter($twi_consumer_key, $twi_consumer_secret);
			$twitter->setOAuthToken($twi_oath_token);
			$twitter->setOAuthTokenSecret($twi_oath_token_secret);
			
			$root = $xml->documentElement;
			$node = $root->firstChild;
			$messages = array();
			$messageIndex = -1;
	
			while ($node) {
				switch ($node->nodeName) {
					case "title":
						$messageIndex++;
						$content = $node->firstChild;
						$messages[$messageIndex] = $content->nodeValue . ": ";
						break;
					case "line":
						$content = $node->firstChild;
						$messages[$messageIndex] = $messages[$messageIndex] . " " . $content->nodeValue . " ";
						break;
				}
				$node = $node->nextSibling;
			}
			foreach ($messages as $index=>$message) {
				$article = trim(preg_replace('/\s{2,}/', ' ', $message));
				$goog = new Googl($goog_short_key);
				$longUrl = HOST_DOMAIN . "/index.php?" . $time . "#" . $index;
				$shortUrl = $goog->shorten($longUrl);
				
				$article = substr($article, 0, (140-strlen($shortUrl)-2));
				$tweet = trim($article)."- $shortUrl";
				$twitter->statusesUpdate($tweet);
			}
			$xml->save(DATA_XML."/b$time.xml");
		} elseif ($index >= 0) {
			// date was found, so no need to look for more data
			break;
		}
	}