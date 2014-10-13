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