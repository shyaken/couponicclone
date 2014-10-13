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