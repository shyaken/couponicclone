<?php
class FPaymentMain extends UWorkletFilter
{
	public function filters()
	{
		return array(
			'admin.menu' => array('behaviors' => array('payment.adminMenu')),
			'user.admin.delete' => array('behaviors' => array('payment.userDelete')),
		);
	}
}