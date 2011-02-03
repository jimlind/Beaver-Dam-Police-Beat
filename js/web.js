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
	var $index = parseInt($url.substr($url.lastIndexOf("#")+1));
	if ($index >= 0) {
		var $head = $("#main h3:eq("+$index+")");
		var $offset = $($head).offset().top - 35;
		$('html,body').animate({scrollTop: $offset});
		
		var $content = $head.add($($head).nextUntil('h3'));		
		$content.wrapAll('<div class="highlight" />');
	}
});