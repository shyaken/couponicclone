/**
 * jQuery.ScrollTo - Easy element scrolling using jQuery.
 * Copyright (c) 2007-2009 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
 * Dual licensed under MIT and GPL.
 * Date: 5/25/2009
 * @author Ariel Flesler
 * @version 1.4.2
 *
 * http://flesler.blogspot.com/2007/10/jqueryscrollto.html
 */
;(function(d){var k=d.scrollTo=function(a,i,e){d(window).scrollTo(a,i,e)};k.defaults={axis:'xy',duration:parseFloat(d.fn.jquery)>=1.3?0:1};k.window=function(a){return d(window)._scrollable()};d.fn._scrollable=function(){return this.map(function(){var a=this,i=!a.nodeName||d.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!i)return a;var e=(a.contentWindow||a).document||a.ownerDocument||a;return d.browser.safari||e.compatMode=='BackCompat'?e.body:e.documentElement})};d.fn.scrollTo=function(n,j,b){if(typeof j=='object'){b=j;j=0}if(typeof b=='function')b={onAfter:b};if(n=='max')n=9e9;b=d.extend({},k.defaults,b);j=j||b.speed||b.duration;b.queue=b.queue&&b.axis.length>1;if(b.queue)j/=2;b.offset=p(b.offset);b.over=p(b.over);return this._scrollable().each(function(){var q=this,r=d(q),f=n,s,g={},u=r.is('html,body');switch(typeof f){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(f)){f=p(f);break}f=d(f,this);case'object':if(f.is||f.style)s=(f=d(f)).offset()}d.each(b.axis.split(''),function(a,i){var e=i=='x'?'Left':'Top',h=e.toLowerCase(),c='scroll'+e,l=q[c],m=k.max(q,i);if(s){g[c]=s[h]+(u?0:l-r.offset()[h]);if(b.margin){g[c]-=parseInt(f.css('margin'+e))||0;g[c]-=parseInt(f.css('border'+e+'Width'))||0}g[c]+=b.offset[h]||0;if(b.over[h])g[c]+=f[i=='x'?'width':'height']()*b.over[h]}else{var o=f[h];g[c]=o.slice&&o.slice(-1)=='%'?parseFloat(o)/100*m:o}if(/^\d+$/.test(g[c]))g[c]=g[c]<=0?0:Math.min(g[c],m);if(!a&&b.queue){if(l!=g[c])t(b.onAfterFirst);delete g[c]}});t(b.onAfter);function t(a){r.animate(g,j,b.easing,a&&function(){a.call(this,n,b)})}}).end()};k.max=function(a,i){var e=i=='x'?'Width':'Height',h='scroll'+e;if(!d(a).is('html,body'))return a[h]-d(a)[e.toLowerCase()]();var c='client'+e,l=a.ownerDocument.documentElement,m=a.ownerDocument.body;return Math.max(l[h],m[h])-Math.min(l[c],m[c])};function p(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);
jQuery(document).ready(function($) {
	$('.uLoad').live('click', function(e) {
		e.preventDefault();
		var worklet = $(this).attr('name');
		$('#'+worklet).uWorklet().load({url: $(this).attr('href')});
	});
	$('.uDialog').live('click', function(e) {
		e.preventDefault();
		$.uniprogy.dialog($(this).attr('href'));
	});
});
/**
 * jQuery UniProgy plugin file.
 *
 */

;(function($) {
	
$.fn.uWorklet = function() {
	var worklet = this;
	
	var plugin = {		
		load: function(options)
		{
			var defaultSettings = {
				url: false,
				position: 'replace',
				success: false,
				showLoading: true
			}
			var settings = $.extend(defaultSettings,options);
			if(settings.showLoading)
				plugin.loading(true);
			$.ajax({
				url: settings.url,
				success: function(data) {
					var vars = {};
					vars[settings.position] = data;
					plugin.process({content:vars});
					plugin.loading(false);
					if($.isFunction(settings.success))
						settings.success(data);
				}
			});
			return worklet;
		},
		
		process: function(data)
		{
			if(data.redirect) { 
				if(data.redirectDelay)
					setTimeout('window.location = "'+data.redirect+'";', data.redirectDelay);
				else
					window.location = data.redirect;
				delete data.redirect;
			}
			if(data.info) { plugin.pushContent('.worklet-info:first', data.info); delete data.info; }
			if(data.content) { plugin.pushContent('.worklet-content:first', data.content); delete data.content; }
			if(data.load) {
				var target = data.load.target ? $(data.load.target) : worklet.find('.worklet-content:first');
				var url = data.load.url ? data.load.url : data.load;
				target.load(url);
				delete data.load;
			}
			
			for(var item in data) {
				if($.isPlainObject(data[item])) {
					var nWorklet = data[item].worklet ? $(data[item].worklet) : worklet;
					nWorklet.uWorklet().process(data[item]);
				}
			}
			return worklet;
		},
		
		pushContent: function(target, data)
		{
			target = worklet.find(target);
			if(!$.isPlainObject(data))
				target.html(data).show();
			else {			
				if(data.prependReplace) {
					target.find('.worklet-pushed-content.prepended').remove();
					data.prepend = data.prependReplace;
				}
				
				if(data.appendReplace) {
					target.find('.worklet-pushed-content.appended').remove();
					data.append = data.appendReplace;
				}
				
				var div = $('<div />');
				div.addClass('worklet-pushed-content');
				
				if(data.prepend)
					div.addClass('prepended').prependTo(target.show()).html(data.prepend);
				else if(data.append)
					div.addClass('appended').appendTo(target.show()).html(data.append);
				else if(data.replace)
					div.appendTo(target.html('').show()).html(data.replace);
					
				if(data.fade)
				{
					if(data.fade == 'target')
						target.animate({opacity: 1.0}, 3000).fadeOut("normal");
					else if(data.fade == 'content')
						div.animate({opacity: 1.0}, 3000).fadeOut("normal");
					else
						$(data.fade).animate({opacity: 1.0}, 3000).fadeOut("normal");
				}
				
				if(data.focus)
					$.scrollTo(target);
			}
			return worklet;
		},
		
		resetWorklet: function()
		{
			worklet.find('.worklet-info').hide();
			worklet.find('.worklet-content').show();
			plugin.resetContent();
			return worklet;
		},
		
		resetContent: function()
		{
			worklet.find('.worklet-pushed-content').remove();
			return worklet;
		},
		
		loading: function(on)
		{
			worklet.toggleClass('loading',on);
			return worklet;
		}
	};
	
	return plugin;
};

$.fn.uForm = function() {
	var form = $(this);
	var button;
	
	var plugin = {
		attach: function()
		{
			form.submit(function(e){
				e.preventDefault();
				form = $(this);
				plugin.submit();
			});
			form.find('input:submit').click(function(){
				button = $(this);
			});
			return form;
		},
	
		submit: function()
		{
			plugin.resetErrors();
			if(form.attr('enctype') == 'multipart/form-data')
				form.each(function(){
					this.submit();
				});
			else
			{
				if(typeof(CKEDITOR)!='undefined')
					for (instance in CKEDITOR.instances) 
					{
						var id = CKEDITOR.instances[instance].element.getId();
						if($('#'+id).length)
							CKEDITOR.instances[instance].updateElement();
					}
				var data = form.serialize();
				if(button)
					data+= '&'+button.attr('name')+'='+button.val();
				form.closest('.worklet').uWorklet().loading(true);			
				$.uniprogy.loadingButton(form.find('input[name="submit"]'),true);
				form.find(':input').attr('disabled',true);
				$.ajax({
					'type':'POST',
					'url':form.attr('action'),
					'cache':false,
					'data':data,
					'dataType':'json',
					'success': function(data) {
						if(!data.redirect && !data.keepDisabled)
							form.find(':input').removeAttr('disabled');
						form.closest('.worklet').uWorklet().loading(false);
						$.uniprogy.loadingButton(form.find('input[name="submit"]'),false);
						plugin.process(data);
					}
				});
			}
			return form;
		},
		
		process: function(data)
		{
			if(data.hideForm) form.hide();
			if(data.errors)	plugin.errorSummary(data.errors);
			form.closest('.worklet').uWorklet().process(data);
			return form;
		},
		
		errorSummary: function(data)
		{
			summary = form.find('.errorSummary');
			if(!summary)
				return;
	
			var content = '';
			for(var i=0;i<data.length;i++)
				content+= '<li>' + data[i].message + '</li>';
			summary.find('ul').html(content);
			summary.toggle(content!='');
			$.scrollTo(summary);
			return form;
		},
		
		resetErrors: function()
		{
			summary = form.find('.errorSummary');
			if(summary)
			{
				summary.find('ul').html('');
				summary.hide();
			}
			return form;
		},
		
		resetForm: function()
		{
			$.each(form,function(){
				this.reset();
			});
			plugin.resetErrors();
			form.show();
			form.parents('.worklet').uWorklet().resetWorklet();
			return form;
		}
	};
	return plugin;
}

$.uniprogy = {
	version : '1.1',
	
	preloadImages: function(imgs)
	{
		$(imgs).each(function(){
			$('<img />')[0].src = this;
		});
	},
	
	loadingButton: function(button,on)
	{
		if(on) {
			var l = $('<div />').addClass('loading').css({display:'inline-block',width:'20px',height:'20px','vertical-align':'middle'});
			$(button).after(l);
		} else {
			$(button).next('.loading').remove();
		}
	},
	
	dialog: function(url)
	{
		$('#wlt-BaseDialog .content').load(url, function() {
			$('#wlt-BaseDialog .content').css({
					'max-height': $('#wlt-BaseDialog').dialog("option","maxHeight"),
					'overflow-y': 'auto'
			});
			var title = $('#wlt-BaseDialog .content .worklet-title');
			title.hide();
			$('#wlt-BaseDialog').dialog('option', 'title', title.html()); 
			$('#wlt-BaseDialog').dialog('open');
		});
	},
	
	dialogClose: function()
	{
		$('#wlt-BaseDialog').dialog('close');
	},
	
	ucfirst: function(str)
	{
		return str.substring(0, 1).toUpperCase() + str.substring(1).toLowerCase();
	},
	
	val: function(field,value)
	{
		if($(field).is(':radio'))
			$(field).val([value]);
		else
			$(field).val(value);
	}
};

})(jQuery);
(function($) {
	$.fn.uGoogleMap = function(address,options) {
		
		var defaultOptions = {
			zoom: 15,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			disableDefaultUI: false
		};
		var geocoder = new google.maps.Geocoder();
		
		if(options.osm)
		{
			var osmMapType = new google.maps.ImageMapType({
				getTileUrl: function(coord, zoom) {
					return "http://tile.openstreetmap.org/" +
					zoom + "/" + coord.x + "/" + coord.y + ".png";
				},
				tileSize: new google.maps.Size(256, 256),
				isPng: true,
				alt: "OpenStreetMap layer",
				name: "OpenStreetMap",
				maxZoom: 19
			});
			var defaultOptions = $.extend(defaultOptions,{
				mapTypeId: 'OSM',
				mapTypeControlOptions: {
					mapTypeIds: ['OSM', google.maps.MapTypeId.ROADMAP],
					style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
				}
			});
		}		
		
		var opts = $.extend(defaultOptions,options);		
		var map = new google.maps.Map($(this).get(0), opts);			
		
		if(options.osm)
		{
			map.mapTypes.set('OSM',osmMapType);
			map.setMapTypeId('OSM');
		}
		
		var markers = new Array;
		
		var addMarker = function(address)
		{
			if(address.lon && address.lat)
			{
				var pos = new google.maps.LatLng(address.lat, address.lon);
				var marker = new google.maps.Marker({
					map: map,
					position: pos
				});
				markers.push(pos);
				setCenterAndZoom();
			}
			else
			{		
				geocoder.geocode({'address':address.address}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK)
					{
						var marker = new google.maps.Marker({
							map: map,				
							position: results[0].geometry.location
						});
						markers.push(results[0].geometry.location);
						setCenterAndZoom();
					}
				});
			}
		};
		
		var setCenterAndZoom = function()
		{
			if(markers.length < address.length)
				return;
			
			if(markers.length > 1)
			{
				var bounds = new google.maps.LatLngBounds();
				for(var i in markers)
					if(!isNaN(i))
						bounds.extend(markers[i]);		
				map.fitBounds(bounds);
			}
			else
				map.setCenter(markers[0]);
				
			if(map.getZoom() > opts.zoom)
				map.setZoom(opts.zoom);
		};
		
		for(var i in address)
			addMarker(address[i]);
	}
})(jQuery);
jQuery(document).ready(function($) {
	var lastOpenedPanel;
	$('.topMenuLink').click(function(e){
		e.preventDefault();
		var panelId = '#'+$(this).attr('name');
		if(lastOpenedPanel && lastOpenedPanel != panelId)
		{
			$(lastOpenedPanel).slideUp('fast', function(){
				$(panelId).slideToggle('fast');
			});
		}
		else
			$(panelId).slideToggle('fast');		
		lastOpenedPanel = panelId;
	});
});
(function($) {

$.uniprogy.updTimers = function(){
	$(".afterUpdTimer").each(
		function(){ 
			var span = $(this).html().split("|");					
			$("#timer_"+span[0]).countdown({until: new Date(span[1]*1000),layout: span[2]});
		});
}

})(jQuery);
;(function($) {

$.uniprogy.uDeal = {
	updateLocation: function(id,options)
	{
		$('#LocationDialog-'+id).html(options);
	}
};
})(jQuery);
(function($) {
	$.fn.uUpdatePurchaseTotal = function(price)
	{
		var selected = this;

		var doUpdate = function()
		{
			var v = selected.val();
			selected.closest('.worklet').find('#total').html(v * price);
			selected.closest('.worklet').find('form').find(":input[name$=\'[quantity]\']").val(v);
		};
		
		this.blur(function(){
			doUpdate();
		});
		
		this.next('#quantityFieldUpdate').click(function(){
			doUpdate();
			return false;
		});
		
		return this;
	}
})(jQuery);
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
jQuery.fn.extend({
	everyTime: function(interval, label, fn, times, belay) {
		return this.each(function() {
			jQuery.timer.add(this, interval, label, fn, times, belay);
		});
	},
	oneTime: function(interval, label, fn) {
		return this.each(function() {
			jQuery.timer.add(this, interval, label, fn, 1);
		});
	},
	stopTime: function(label, fn) {
		return this.each(function() {
			jQuery.timer.remove(this, label, fn);
		});
	}
});

jQuery.extend({
	timer: {
		guid: 1,
		global: {},
		regex: /^([0-9]+)\s*(.*s)?$/,
		powers: {
			// Yeah this is major overkill...
			'ms': 1,
			'cs': 10,
			'ds': 100,
			's': 1000,
			'das': 10000,
			'hs': 100000,
			'ks': 1000000
		},
		timeParse: function(value) {
			if (value == undefined || value == null)
				return null;
			var result = this.regex.exec(jQuery.trim(value.toString()));
			if (result[2]) {
				var num = parseInt(result[1], 10);
				var mult = this.powers[result[2]] || 1;
				return num * mult;
			} else {
				return value;
			}
		},
		add: function(element, interval, label, fn, times, belay) {
			var counter = 0;
			
			if (jQuery.isFunction(label)) {
				if (!times) 
					times = fn;
				fn = label;
				label = interval;
			}
			
			interval = jQuery.timer.timeParse(interval);

			if (typeof interval != 'number' || isNaN(interval) || interval <= 0)
				return;

			if (times && times.constructor != Number) {
				belay = !!times;
				times = 0;
			}
			
			times = times || 0;
			belay = belay || false;
			
			if (!element.$timers) 
				element.$timers = {};
			
			if (!element.$timers[label])
				element.$timers[label] = {};
			
			fn.$timerID = fn.$timerID || this.guid++;
			
			var handler = function() {
				if (belay && this.inProgress) 
					return;
				this.inProgress = true;
				if ((++counter > times && times !== 0) || fn.call(element, counter) === false)
					jQuery.timer.remove(element, label, fn);
				this.inProgress = false;
			};
			
			handler.$timerID = fn.$timerID;
			
			if (!element.$timers[label][fn.$timerID]) 
				element.$timers[label][fn.$timerID] = window.setInterval(handler,interval);
			
			if ( !this.global[label] )
				this.global[label] = [];
			this.global[label].push( element );
			
		},
		remove: function(element, label, fn) {
			var timers = element.$timers, ret;
			
			if ( timers ) {
				
				if (!label) {
					for ( label in timers )
						this.remove(element, label, fn);
				} else if ( timers[label] ) {
					if ( fn ) {
						if ( fn.$timerID ) {
							window.clearInterval(timers[label][fn.$timerID]);
							delete timers[label][fn.$timerID];
						}
					} else {
						for ( var fn in timers[label] ) {
							window.clearInterval(timers[label][fn]);
							delete timers[label][fn];
						}
					}
					
					for ( ret in timers[label] ) break;
					if ( !ret ) {
						ret = null;
						delete timers[label];
					}
				}
				
				for ( ret in timers ) break;
				if ( !ret ) 
					element.$timers = null;
			}
		}
	}
});

if (jQuery.browser.msie)
	jQuery(window).one("unload", function() {
		var global = jQuery.timer.global;
		for ( var label in global ) {
			var els = global[label], i = els.length;
			while ( --i )
				jQuery.timer.remove(els[i], label);
		}
	});



(function($) {

$.fn.uLoc = function(data,selects) {
	var form = $(this);
	var data = data;
	
	form.find(':input[name$="[country]"]').change(function(){
		updateStates($(this).val(),1);
		return true;
	});
	
	form.find('select[name$="[state]"]').change(function(){
		updateCities(countryField().val(),$(this).val());
		return true;
	});
	
	var countryField = function()
	{
		return form.find(':input[name$="[country]"]');
	}
	
	var stateField = function()
	{
		return form.find('select[name$="[state]"]');
	}
	
	var cityField = function(type)
	{
		var cF = form.find(':input[name$="[city]"]');
			
		if(!cF.length)
			return false;
			
		if(type && (type.toUpperCase() != cF.get(0).tagName.toUpperCase()))
		{
			var id = cF.attr('id');
			var name = cF.attr('name');
			if(type == 'input')
				type+= ' type="text"';
			var newField = $('<'+type+' />').attr({'id': id, 'name': name});
			cF.after(newField).remove();
			return newField;
		}
		return cF;
	};
	
	var updateStates = function(country,uC)
	{
		var state = 0;
		if(data.states[country])
		{
			stateField().html('');
			var oneState = true;
			for(var i in data.states[country])
			{
				if(state===0)
					state = i;
				else
					oneState = false;
				$('<option />').attr('value',i).html(data.states[country][i]).appendTo(stateField());
			}
			if(oneState)
				stateField().parent().hide();
			else
				stateField().parent().show();
		}
		else
		{
			stateField().parent().hide();
		}
		if(uC)
			updateCities(country,state);
	}
	
	var updateCities = function(country,state)
	{
		if(!state)
			state = '0';
		var countryState = country+'_'+state;
		if(data.cities[countryState]===true || data.cities[country+'_*']===true)
		{
			var cF = cityField('input');
			if(cF)
				cF.parent().show();
		}
		else if(data.cities[countryState])
		{					
			var cF = cityField('select').html('');
			if(!cF)
				return;
			cF.parent().show().end();
			for(var i in data.cities[countryState])
			{
				$('<option />').attr('value',data.cities[countryState][i]).html(data.cities[countryState][i]).appendTo(cF);
			}
		}
		else
		{
			return cityField('input').parent().hide();
		}
	};
	
	if(!selects || !selects.country)
		updateStates(countryField().val(),1);
	else
	{
		countryField().val(selects.country);
		countryField().change();
		if(selects.state)
		{
			stateField().val(selects.state);
			stateField().change();
		}
		if(selects.city)
			cityField().val(selects.city);
	}
	return this;
}

})(jQuery);
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
(function($) {
	$.fn.uPaymentCart = function(calcUrl,locale)
	{
		var cart = this;
		
		$(cart).find('.quantityField').change(function(){
			updateItemTotal(this);
		});
		
		$(cart).find('.quantityField').keyup(function(){			
			updateItemTotal(this);
		});
		
		$(cart).find('.quantityField').keypress(function(e){
			var charCode = (e.which) ? e.which : e.keyCode;
			if(charCode == 13)
				return false;
			// not a number
			if(charCode > 31 && (charCode < 48 || charCode > 57))
			{
				if($(this).hasClass('allowDecimal') && String.fromCharCode(charCode) == locale.decimal)
					return true;
				return false;
			}
		});
		
		$(cart).find('.removeLink').click(function(){
			$(cart).uWorklet().load({
				url: $(this).attr("href"),
				position: "append",
				success: function() {
					location.reload(true);					
				}
			});
			return false;
		});
		
		var updateItemTotal = function(item)
		{
			var quantity = parseDecimal($(item).val());
			if(quantity >= 0)
			{
				if(!$(item).hasClass('negative'))
				{
					var price = parseDecimal($(item).closest('tr').find('.price').html());
					var total = price * quantity;								
					$(item).closest('tr').find('.total').html(formatNumber(total));
				}
				updateTotal();
			}
		};
		
		var updateTotal = function()
		{
			$(cart).closest('.worklet').uWorklet().loading(true);
			$('#uForm_PaymentCheckout').uForm().resetErrors();
			
			var post = '';
			$('.quantityField').each(function(){
				post+= encodeURI($(this).attr('name'))+"="+encodeURI($(this).val())+"&";
			});
			
			$.ajax({
				url: calcUrl,
				type: 'post',
				data: post,
				dataType: 'json',
				success: function(data)
				{
					pushTotal(data);
				}
			});
			
			$(cart).closest('.worklet').uWorklet().loading(false);
		};
		
		var pushTotal = function(data)
		{
			var total = data.total;
			var js = data.js ? data.js : '';
			$(cart).find('.cartTotal .total').html(formatNumber(total)+js);
			
			if (total <= 0) {
				$(cart).find('.field_type').toggle(false);
				$(cart).find('.paymentForm').toggle(false);
			} else {
				$(cart).find('.field_type').toggle(true);
				if($(cart).find('input[name$="[type]"]:radio').length)
					$(cart).find('input[name$="[type]"]:radio:checked').change();
				else
					$(cart).find('input[name$="[type]"]:hidden').change();
			}
		}
		
		var formatNumber = function(num)
		{
			num = Math.round(num*100)/100;
			num+= '';
			var ar = num.split(/\./);
			var re = new RegExp('(-?[0-9]+)([0-9]{3})'); 
			while(re.test(ar[0]))
				ar[0] = ar[0].replace(re, '$1'+locale.group+'$2');
			return ar.length > 0 ? ar.join(locale.decimal) : ar[0];
		};
		
		var parseDecimal = function(str)
		{
			str+= '';
			re = new RegExp('[^'+escapeRegex(locale.decimal)+'|0-9]','g');
			str = str.replace(re, '');
			re = new RegExp(escapeRegex(locale.decimal),'g');
			return str.replace(re, '.')*1;
		};
		
		var escapeRegex = function(text)
		{
		    return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
		};
	}
})(jQuery);
(function($) {
	$.uniprogy.subscription = {
		init: function(data, total)
		{
			for(var i in data)
				$.uniprogy.subscription.addList(data[i],i);
			$(".removeList").live('click', function(){
				var id = $(this).closest("div").attr("id");
				id = id.substring(5);
				$.uniprogy.subscription.removeList(id);
			});
			
			if(total <= 10)
				$('#selectedLists').closest("form").find('input[name$="[listsField]"]').bind('focus click',function(){
					$(this).autocomplete( "search", "" );
				});
		},
		
		addList: function(label, value)
		{
			$('#selectedLists').append($.uniprogy.subscription.renderListView(label,value)).closest("form").append($.uniprogy.subscription.rednerListField(value));
		},
		
		removeList: function(id)
		{
			$('#selectedLists').find("#list_"+id).remove();
			$('#selectedLists').closest("form").find('input[name="lists['+id+']"]').remove();
		},
		
		renderListView: function(label, value)
		{
			var r = $('<div />').attr({'id':'list_'+value});
			r.html(label+' <a href="#" class="removeList">[x]</a> ');
			return r;
		},
		
		rednerListField: function(value)
		{
			return $('<input type="hidden">').attr({'name':'lists['+value+']','value':value});
		}
	};
})(jQuery);
