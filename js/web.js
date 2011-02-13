$(document).ready(function() {
	$(window).scroll(function(){
		var $top = $(this).scrollTop();
		$("#previous").css('top', ($top)+'px');
		$("#next").css('top', ($top)+'px');
	});
	
	$("#previous, #next").bind('click', function(e) {
		e.preventDefault();
		var $link = $(this).attr('href');
		window.location = $link;
	});
	
	$(".fb_share").bind('click', function(e) {
		e.preventDefault();
		var $u = $(this).attr('href');
		$u = $u.substr($u.lastIndexOf("http"));
		window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent($u),'sharer','toolbar=0,status=0,width=626,height=436');
	});
	
	$(document).touchwipe({
	    wipeLeft: function() { $("#next").trigger('click'); },
	    wipeRight: function() { $("#previous").trigger('click') },
	    preventDefaultEvents: false
	});
	
	var $url = window.location.toString();
	var $symbol =  $url.lastIndexOf("#") > 0 ? "#" : ".";
	
	var $index = parseInt($url.substr($url.lastIndexOf($symbol)+1));
	if ($index >= 0) {
		var $div = $("#main div.report:eq("+$index+")");
		var $offset = $($div).offset().top - 35;
		$div.addClass("highlight");
		
		$('html,body').animate({scrollTop: $offset});
	}
});