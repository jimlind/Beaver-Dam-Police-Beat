<?php
	require "includes/init.php";
	require "includes/keys.php";
	
	$fileObj = new Files(DATA_XML);
	$files = $fileObj->getArray();
	
	$fileNum = 0;
	$timestamp = intval(@substr($_GET["b"], 0, 10));
	if ($timestamp == 0 && !empty($_GET)) {
		$keys = array_keys($_GET);
		$timestamp = intval(@substr($keys[0], 0, 10));
	}
	
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
	$link = "index.php?b=$time";
	
	$series = new Report_Series();
	$seriesData = $series->loadSource(DATA_XML . "/" . $files[$fileNum]);
?>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
<head>
<title>Beaver Dam Police Beat for <?= date("M jS Y - g:i a", $time) ?></title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
<link rel='stylesheet' type='text/css' href='css/web.css?t=<?= filemtime('css/web.css') ?>'>
<link rel='stylesheet' media='only screen and (max-device-width: 1024px) and (orientation:portrait)' href='css/ipad.portrait.css' type='text/css' />
<link rel='stylesheet' media='only screen and (max-device-width: 1024px) and (orientation:landscape)' href='css/ipad.landscape.css' type='text/css' />
<script src='js/jquery-1.4.4.min.js' type='text/javascript'></script>
<script src='js/jquery.touchwipe.min.js' type='text/javascript'></script>
<script src='js/web.js?t=<?= filemtime('js/web.js') ?>'>' type='text/javascript'></script>
<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', '<?= $goog_analytics_key ?>']);
	_gaq.push(['_trackPageview']);
	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>
<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
<?php 
	$arr = explode(".", @$_GET["b"]);
	if (count($arr) > 1 && is_numeric($arr[count($arr)-1])) {
		$index = intval($arr[count($arr)-1]);
		$seriesObj = $seriesData[$index];
		echo "<meta property='og:type' content='article'/>" . PHP_EOL;
		echo "<meta property='og:title' content='{$seriesObj->title}'/>" . PHP_EOL;
		echo "<meta property='og:description' content='{$seriesObj->getLinesAsString()}'/>" . PHP_EOL;
	}
?>
</head>
<body>

<h1>Beaver Dam Police Beat</h1>
<ul id="external_links">
	<li><a href="http://www.facebook.com/apps/application.php?id=164140956966428">Facebook</a></li>
	<li><a href="http://twitter.com/#!/bdpolicebeat">Twitter</a></li>
	<li><a href="https://github.com/jimlind/Beaver-Dam-Police-Beat">Source Code</a></li>
</ul>
<div id="main">	
<?php
	if ($prev) {
		echo "<a id='previous' href='$prev'><span>Previous</span></a>";
	}
	if ($next) {
		echo "<a id='next' href='$next'><span>Next</span></a>";
	}
	echo "<h2>".date("l F j, Y, g:i a", $time)."</h2>";
	
	foreach ($seriesData as $index=>$seriesObj) {
		echo "<div class='report'>" . PHP_EOL;
		echo "<h3>";
		echo "<a href='$link.$index'>{$seriesObj->title}</a>";
		echo "</h3>" . PHP_EOL;
		echo "<a class='fb_share' href='http://www.facebook.com/share.php?u=" . HOST_DOMAIN . "$link.$index'><img src='images/fb-share.png' width='55' height='20'></a>" . PHP_EOL;
		echo "<span class='fb_like'><fb:like href='" . HOST_DOMAIN . "$link.$index' show_faces='false' layout='button_count' width='90'></fb:like></span>" . PHP_EOL;
		
		foreach ($seriesObj->lines as $line) {
			echo "<p>$line</p>" .  PHP_EOL;
		}
		echo "</div>" . PHP_EOL;
	}
?>
</div>
<p id="iOS">Swipe Left or Right for Previous or Next</p>
<ul id="footer">
<?php
	if ($prev) {
		echo "<li><a href='$prev'>Previous</a></li>";
	}
	if ($next) {
		echo "<li><a href='$next'>Next</a></li>";
	}
?>
</ul>
</body>