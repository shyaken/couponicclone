(function($) {
	$.fn.uTimeLeftTick = function(periods,total) {		
		var removeTimerClass = function(that)
		{
			for(var i=0;i<=5;i++)
			{
				if(that.hasClass('timer-'+i))
					that.removeClass('timer-'+i);
			}
		};
		
		removeTimerClass(this.find('.timerContent'));
		var nClass = 'timer-'+(4-Math.round(((
			periods[3]*86400+
			periods[4]*3600+periods[5]*60+periods[6]
		)/total)*4));
		this.find('.timerContent').addClass(nClass);
	}
})(jQuery);