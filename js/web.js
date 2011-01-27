$(document).ready(function() {
	$(window).scroll(function(){
		$top = $(this).scrollTop();
		$("#previous").css('top', ($top)+'px');
		$("#next").css('top', ($top)+'px');
	});
	
	$("#previous, #next").bind('click', function(e) {
		e.preventDefault();
		$link = $(e.target).attr('href');
		window.location = $link;
	});
	
	$(document).touchwipe({
	    wipeLeft: function() { $("#next").trigger('click'); },
	    wipeRight: function() { $("#previous").trigger('click') },
	    preventDefaultEvents: false
	});
});