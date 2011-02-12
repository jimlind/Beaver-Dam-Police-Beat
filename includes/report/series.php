<?php
	class Report_Series {
	
		private $source;
		private $timestamp;
		private $seriesArray;
		
		public function __construct() {
            $this->source = null;
        }
		
        public function loadSource($file) {
        	$this->source = $file;
        	$seriesObj = new Report_Series_Obj();
        	
        	$doc = new DOMDocument();
			$doc->load($file);
			$root = $doc->documentElement;
			$node = $root->firstChild;
			
        	while ($node) {
				switch ($node->nodeName) {
					case "title":
						if ($seriesObj->title != null) {
							$this->seriesArray[] = $seriesObj;
							$seriesObj = new Report_Series_Obj();
						}
						$content = $node->firstChild;
						$seriesObj->title = $content->nodeValue;
					break;
					case "line":
						$content = $node->firstChild;
						$seriesObj->lines[] = $content->nodeValue;
					break;
				} //end switch
				$node = $node->nextSibling;
			} //end while
			
			return $this->seriesArray;
        }
	}
	
	class Report_Series_Obj {
		
		public $title;
		public $lines;
		
		public function __construct() {
            $this->title = null;
            $this->lines = array();
        }
        
	    public function getLinesAsString() {
        	return implode(" ", $this->lines);
        }
	}	