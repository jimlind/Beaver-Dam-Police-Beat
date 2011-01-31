<?php
define("ROOT", realpath(dirname(__FILE__) . "/../"));
define("DATA_XML", ROOT . "/xml");
define("DATA_CACHE", ROOT . "/cache");

// Need access to the folders.  If we don't, just stop.
if (!(is_dir(DATA_XML) && is_dir(DATA_CACHE) && is_writable(DATA_XML) && is_writable(DATA_CACHE))) {
	echo "XML and Cache Directory are Neccesary";
	die;
}

//check read and write status of xml and cache directory
$config = array();
$config["domain"] = "http://www.wiscnews.com";
$config["firstResult"] = "/bdc/search/?f=html&t=article&l=25&s=start_time&sd=desc&sForm=false&c=news/local/crime_and_courts*&q=%23bdc&sHeading=Police+Beat&o=0&app[0]=editorial";

require ROOT . "/includes/class.article.php";
require ROOT . "/includes/class.files.php";
require ROOT . "/includes/class.searchresult.php";