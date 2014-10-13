<?php
class FSubscriptionMain extends UWorkletFilter
{
	public function filters()
	{
		return array(
			'admin.menu' => array('behaviors' => array('subscription.adminMenu')),
		);
	}
}