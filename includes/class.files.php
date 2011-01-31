<?php
	class Files {
	 	private $files;
	 	private $dir;

        public function __construct($dir) {
            $files = array();
            $this->dir = opendir($dir);
            
            while(($file = readdir($this->dir)) !== false) {
            	if($file !== '.' && $file !== '..' && !is_dir($file) && $file{0} === "b") {
            		$files[] = $file;
            	}
            }
            rsort($files);
			$this->files = $files;
			return $this->files;
        }
        
        public function getArray() {
        	return $this->files;
        }
        
	}
	