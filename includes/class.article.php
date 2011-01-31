<?php
	class Article {
	 	private $html;
	 	private $time;
	 	private $xml;

        public function __construct($domain, $link) {
        	$url = "$domain$link";
            $this->html = @file_get_contents($url); // suppress warnings
            $this->time = null;
            $this->xml = null;
        }

		public function getTimeStamp() {
			if ($this->time != null) return $this->time;
			preg_match_all('%<p class="byline">(.*)Posted: (.*m)\n*\r*%', $this->html, $byline);
			$this->time = strtotime($byline[2][0]." CST");
			return $this->time;
		}
		
		public function getXml() {
			if ($this->xml != null) return $this->xml;
			// get div holding all content
			preg_match_all('%<div id="blox-story-text">(.*?)</div>%s', $this->html, $paragraphs);
			$paragraphs = $paragraphs[1][0];
			// get paragraph tags
			preg_match_all('%<p>(.*?)</p>%s', $paragraphs, $text);
			$text = $text[1];
			
			$imp = new DOMImplementation;
			$dtd = $imp->createDocumentType("beat", "", "policebeat.dtd");
			$dom = $imp->createDocument("", "", $dtd);

			$dom->encoding = 'UTF-8';
			$dom->formatOutput = true;
			
			$beat = $dom->createElement('beat');
			$dom->appendChild($beat);

			foreach ($text as $node) {
				preg_match_all('%(.{3,30}?)\s(-|—|–)\s*(.*)%s', $node, $output);
				$titleArr = $output[1];
				$lineArr = $output[3];

				if (isset($titleArr[0])) {					
					$title = $dom->createElement('title');
					$title->appendChild($dom->createTextNode($titleArr[0]));
					$beat->appendChild($title);
					
					$line = $dom->createElement('line');
					$line->appendChild($dom->createTextNode($lineArr[0]));
					$beat->appendChild($line);
				} else {					
					$line = $dom->createElement('line');
					$line->appendChild($dom->createTextNode($node));
					$beat->appendChild($line);
				}
			}
						
			return $dom;
		}
	}