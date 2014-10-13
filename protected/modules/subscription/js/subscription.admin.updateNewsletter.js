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