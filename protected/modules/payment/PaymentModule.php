<?php
class PaymentModule extends UWebModule
{
	public function getTitle()
	{
		return 'Payment';
	}
	
	public function format($amount)
	{
		$format = app()->numberFormatter->formatCurrency($amount,$this->param('cSymbol'));
		return str_replace(app()->locale->getNumberSymbol('decimal').'00','',$format);
	}
}