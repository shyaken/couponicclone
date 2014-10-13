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