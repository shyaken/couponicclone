<?php
/**
 * Gateway is a base class which you can modify to integrate almost any
 * payment gateway into Couponic script.
 * 
 */
class Gateway
{
	/**
	 * REQUIRED
	 * 
	 * This method must prepare a HTML form that needs to be passed
	 * to the payment provider and auto-submit it using JavaScript.
	 * 
	 * It must actually charge the payment (not just authorize).
	 *
	 * @param array payment data that has following items:
	 * params - module parameters
	 * currency - ISO currency code
	 * cart - array items from the shopping cart:
	 *   name - item name
	 *   amount - item price
	 *   quantity - item quantity
	 * amount - total amount of the order
	 * orderId - order id
	 * 
	 * @return array
	 * Positive reply: 'status' and 'form' items.
	 * return array('status' => true,
	 *   'form' => '<form action="...."><input type="hidden"....></form>');
	 * Negative reply: 'status' and 'error' items.
	 * return array('status' => false,
	 *   'error' => 'Some error');
	 * 
	 * If your payment provider uses any kind of payment status notifications, use this URL as the handler for these notifications:
	 * aUrl('/payment/gateway/ipn')
	 */
	public function charge($data)
	{
		// this is just an example of how the form should be created and returned
		$form = '<form action="#" method="post" id="gatewayPaymentForm" style="display:none">
			<input type="hidden" name="field_1" value="value_1">
			<input type="hidden" name="field_2" value="value_2">
			<input type="submit" name="submit" value="" id="gatewayPaymentFormSubmit">
		</form>';
		$form.= CHtml::script('jQuery("#gatewayPaymentFormSubmit").click();');
		
		return array('status' => true, 'form' => $form);
	}
	
	/**
	 * OPTIONAL UNDER CONDITIONS
	 * 
	 * This method is being called when payment provider
	 * reports back with payment info and status - 
	 * payment notification that can be verified.
	 * 
	 * The only case when you don't need this to be integrated is if your payment provider allows
	 * you to collect credit cards info directly on your site and send it in the background
	 * and you are not going to use it in any other way (redirect user to the payment provider
	 * site to make the payment).
	 *
	 * @return array
	 * status - true if order is valid; false otherwise
	 * type - authorize or charge (if your payment provider doesn't support authorize&capture this item should be always 'charge')
	 * orderId - Couponic order ID
	 * gatewayId - payment provider order/transaction ID
	 * after - null if the script should 'die' after the validation; 'redirect' - if it needs to redirect user so some URL (see below)
	 * redirectUrl - URL where user needs to be redirected after the validation.
	 * 
	 * Recommended URL: aUrl('/payment/success',array('id' => <orderId>));
	 * Make sure to replaced <orderId> with actual Couponic order ID (variable).
	 */
	public function validate()
	{
	}
	
	/**
	 * OPTIONAL
	 * 
	 * Integration required only if your payment provider supports "authorize" and "capture"
	 * technology. That is when the funds can be first "authorized" (not charged yet)
	 * and if everything goes well they can be later "captured" (user gets charged at this time)
	 * or "voided" (order cancellation) otherwise.
	 * 
	 * This method must prepare a HTML form that needs to be passed
	 * to the payment provider and auto-submit it using JavaScript.
	 * 
	 * It must authorize payment only and not charge user yet.
	 *
	 * @param array payment data that has following items:
	 * params - module parameters
	 * currency - ISO currency code
	 * cart - array items from the shopping cart:
	 *   name - item name
	 *   amount - item price
	 *   quantity - item quantity
	 * amount - total amount of the order
	 * orderId - order id
	 * @return array
	 * Positive reply: 'status' and 'form' items.
	 * return array('status' => true,
	 *   'form' => '<form action="...."><input type="hidden"....></form>');
	 * Negative reply: 'status' and 'error' items.
	 * return array('status' => false,
	 *   'error' => 'Some error');
	 */
	public function authorize($data)
	{
		// this is just an example of how the form should be created and returned
		$form = '<form action="#" method="post" id="gatewayPaymentForm">
			<input type="hidden" name="field_1" value="value_1">
			<input type="hidden" name="field_2" value="value_2">
		</form>';
		$form.= CHtml::script('jQuery("#gatewayPaymentForm").submit();');
		
		return array('status' => true, 'form' => $form);
	}
	
	
	/**
	 * OPTIONAL
	 * 
	 * Integration required only if your payment provider supports "authorize" and "capture"
	 * technology. That is when the funds can be first "authorized" (not charged yet)
	 * and if everything goes well they can be later "captured" (user gets charged at this time)
	 * or "voided" (order cancellation) otherwise.
	 * 
	 * This method should "capture" previously authorized payment.
	 *
	 * @param array payment data that has following items:
	 * params - module parameters
	 * orderId - order id
	 * gatewayId - payment provider order/transaction ID
	 * amount - total amount of the order
	 * currency - ISO currency code
	 * 
	 * @return array
	 * Positive reply:
	 * status - true
	 * gatewayId - payment provider order/transaction ID (if it has not changed during the "capture"
	 *   transaction return the same gatewayId that has been passed within function parameters array)
	 * return array('status' => true, 'gatewayId' => '123abc');
	 * Negative reply:
	 * status - false
	 * error - error as string
	 * return array('status' => false, 'error' => 'Some error');
	 */
	public function capture($data)
	{
	}
	
	/**
	 * OPTIONAL
	 * 
	 * Integration required only if your payment provider allows you to setup your own payment page,
	 * let your users input their credit card info on your site and send collected info to the gateway
	 * in the background via some API calls.
	 * 
	 * This method should send the whole necessary information to the payment provider that is required
	 * to process credit card payment, receive reply and either return an error or an ID of the transation.
	 *
	 * @param array payment data that has following items:
	 * params - module parameters
	 * currency - ISO currency code
	 * cart - array items from the shopping cart:
	 *   name - item name
	 *   amount - item price
	 *   quantity - item quantity
	 * amount - total amount of the order
	 * orderId - order id
	 * address - array buyer billing address
	 *   street - street address
	 *   city - city
	 *   state - state
	 *   country - country (2-characters ISO code)
	 *   zip - ZIP/Postal code
	 * cc - array credit card info
	 *   month - expire date (month)
	 *   year - expire date (year)
	 *   type - cc type (Visa, MasterCard, etc.)
	 *   number - cc number
	 *   code - cvv2 code
	 *   firstname - first name
	 *   lastname - last name
	 * 
	 * @param string transaction type
	 * authorize or charge
	 * 
	 * @return array
	 * Positive reply:
	 * status - true
	 * gatewayId - payment provider order/transaction ID
	 * return array('status' => true, 'gatewayId' => '123abc');
	 * Negative reply:
	 * status - false
	 * error - error as string
	 * return array('status' => false, 'error' => 'Some error');
	 */
	public function direct($data,$type)
	{
	}
	
	/**
	 * OPTIONAL
	 * 
	 * Integration required only if your payment provider supports "authorize" and "capture"
	 * technology. That is when the funds can be first "authorized" (not charged yet)
	 * and if everything goes well they can be later "captured" (user gets charged at this time)
	 * or "voided" (order cancellation) otherwise.
	 * 
	 * This method should "void" previously authorized payment.
	 *
	 * @param array payment data that has following items:
	 * params - module parameters
	 * orderId - order id
	 * gatewayId - payment provider order/transaction ID
	 * amount - total amount of the order
	 * currency - ISO currency code
	 * 
	 * @return array
	 * Positive reply:
	 * status - true
	 * gatewayId - payment provider order/transaction ID (if it has not changed during the "capture"
	 *   transaction return the same gatewayId that has been passed within function parameters array)
	 * return array('status' => true, 'gatewayId' => '123abc');
	 * Negative reply:
	 * status - false
	 * error - error as string
	 * return array('status' => false, 'error' => 'Some error');
	 */
	public function void($data)
	{
	}
	
	/**
	 * OPTIONAL
	 * 
	 * Integration required only if your payment provider allows you to refund orders via
	 * API calls.
	 * 
	 * This method should refund the payment.
	 *
	 * @param array payment data that has following items:
	 * params - module parameters
	 * orderId - order id
	 * gatewayId - payment provider order/transaction ID
	 * amount - total amount of the order
	 * currency - ISO currency code
	 * 
	 * @return array
	 * Positive reply:
	 * status - true
	 * gatewayId - payment provider order/transaction ID (if it has not changed during the "capture"
	 *   transaction return the same gatewayId that has been passed within function parameters array)
	 * return array('status' => true, 'gatewayId' => '123abc');
	 * Negative reply:
	 * status - false
	 * error - error as string
	 * return array('status' => false, 'error' => 'Some error');
	 */
	public function refund($data)
	{
	}
}