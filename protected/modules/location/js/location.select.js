(function($) {

$.fn.uLocSelect = function(selection) {
	
	var that = $(this);
	var currentCountry = null;
	
	var resetAll = function()
	{
		that.find('.state,.city').hide();
	}
	
	var buildAlphabet = function()
	{
		$('.alphabet').html('');
		
		var chars = [];
		var states = that.find('ul.country.'+currentCountry).find('.state');
		if(states.length > 0)
		{
			states.each(function(){
				var c = charFromClass($(this)[0].className);
				if(c && $.inArray(c, chars)<0)
					chars.push(c);
			});
		}
		else
		{
			that.find('ul.country.'+currentCountry).find('.city').each(function(){
				var c = charFromClass($(this)[0].className);
				if(c && $.inArray(c, chars)<0)
					chars.push(c);
			});
		}
		
		for(var i=0;i<chars.length;i++)
			$('<a>'+chars[i]+'</a>').attr({name: chars[i], href: '#'}).appendTo('.alphabet');
	}
	
	var charFromClass = function(className)
	{
		return className.substr(className.indexOf('char_')+5);
	}
	
	that.find('a.showCountries').click(function(){
		that.find('ul.country').hide();
		that.find('ul.country.ALL').show();
	});
	
	that.find('ul.country.ALL a').click(function(){
		var country = $(this).attr('name');
		that.find('ul.country').hide();
		that.find('ul.country.'+country).show();
		that.find('.currentCountry').html($(this).html()+':');
		currentCountry = country;
		buildAlphabet();
		that.find('.alphabet a:first').click();
	});
	
	that.find('.alphabet a').live('click', function(){
		
		that.find('.alphabet a').removeClass('current');
		$(this).addClass('current');
		
		resetAll();
		
		var c = $(this).attr('name');
		var states = that.find('ul.country.'+currentCountry).find('.state.char_'+c);
		if(states.length >0)
		{
			states.show();
			if($.browser.msie && parseInt($.browser.version) < 8)
				that.find('ul.country.'+currentCountry).find('.state.char_'+c).find('.city').show();
			else
				that.find('ul.country.'+currentCountry).find('.state.char_'+c+' + .cities').find('.city').show();
		}
		else
		{
			that.find('ul.country.'+currentCountry).find('.city.char_'+c).show();
		}
	});
	
	if(selection.country)
		that.find('ul.country.ALL a[name="'+selection.country+'"]').click();
	if(selection.c)
		that.find('.alphabet a[name="'+selection.c+'"]').click();
}

})(jQuery);