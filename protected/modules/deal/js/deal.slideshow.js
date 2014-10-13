(function($) {

$.fn.uSlideshow = function(options) {
	var selected = $(this);
	
	selected.parent().find('.controls a').click(function(){
		selected.anythingSlider($(this).attr('name'));
		return false;
	});
	
	var defaultOptions = {
		easing: 'easeInOutExpo',
		buildNavigation: false,
		buildArrows: false,
		buildAutoPlay: false,
		delay: 5000,
		onSlideComplete: function(slider) {
			uSelectSlider(slider,selected);
		},
		onShowStart: function(slider)
		{
			uSelectSlider(slider,selected);
		}
	};
	options = $.extend(defaultOptions,options);
	selected.anythingSlider(options);
	
    return this;
}

function uSelectSlider(slider,selected)
{
	selected.closest('.wlt-DealSlideshow').find('.controls a').each(function(){
		if($(this).attr('name') != slider.currentPage)
			$(this).removeClass('active');
		else
			$(this).addClass('active');
	});
}

})(jQuery);