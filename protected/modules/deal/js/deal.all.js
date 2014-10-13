(function($) {

$.uniprogy.updTimers = function(){
	$(".afterUpdTimer").each(
		function(){ 
			var span = $(this).html().split("|");					
			$("#timer_"+span[0]).countdown({until: new Date(span[1]*1000),layout: span[2]});
		});
}

})(jQuery);