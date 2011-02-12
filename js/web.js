$(document).ready(function() {
	$(window).scroll(function(){
		var $top = $(this).scrollTop();
		$("#previous").css('top', ($top)+'px');
		$("#next").css('top', ($top)+'px');
	});
	
	$("#previous, #next").bind('click', function(e) {
		e.preventDefault();
		var $link = $(e.target).attr('href');
		window.location = $link;
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