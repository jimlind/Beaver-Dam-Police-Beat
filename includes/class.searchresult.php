<?php
	class SearchResult {
	 
	 	private $domain;
	 	private $link;
	 	private $html;

        public function __construct() {
            $this->domain = null;
            $this->link   = null;
        }
        
        public function setURL($domain, $link) {
        	$this->domain = $domain;
        	$this->link   = $link;
        	$this->html   = null;
        }
        
        public function process() {
        	// domain and link variables should be set.
        	if ($this->domain === null || $this->link === null) {
        		return false;
        	}
        	
        	$url = "{$this->domain}{$this->link}";
        	$this->html = @file_get_contents($url); // suppress warnings
        	
        	//Match all links on page with text "Beaver Dam*" except "Beaver Dam Warrant"
			preg_match_all('%<a href="(/bdc/[^"]+)" title="(.*)">Beaver Dam(?! Warrant)(.*)</a>%i', $this->html, $links);
			return $links[1];
        }
        
        public function findNext() {
        	if ($this->html === null) return false;

        	preg_match_all('%<p class="search-paging">(\s*)<a href="([^"]+)">&laquo; Previous</a>(\s*)<a href="([^"]+)">Next &raquo;</a>(\s*)</p>%', $this->html, $nav);
        	if (isset($nav[4][0])) {
        		return html_entity_decode($nav[4][0]);
        	}
        	
        	preg_match_all('%<p class="search-paging">(\s*)<a href="([^"]+)">Next &raquo;</a>(\s*)</p>%', $this->html, $nav);
        	if (isset($nav[2][0])) {
        		return html_entity_decode($nav[2][0]);
        	}
        	
        	return false;
   		}
	}
	