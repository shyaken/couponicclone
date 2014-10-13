(function($) {
	$.fn.uDealStatusPing = function(url)
	{
		var s = this;
		return this.everyTime(5000, 'ping', function(){
			var curr = s.find('.bought').html();
			s.uWorklet().load({
				url: url,
				success: function() {
					if(curr != s.find('.bought').html())
						s.find('.box').addClass('blink').removeClass('blink', 1000);
				},
				showLoading: false
			});
		});
	}
})(jQuery);