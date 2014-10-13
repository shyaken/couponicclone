<?php
class WPaymentAdminDelete extends UDeleteWorklet
{
	public $modelClassName = array(
		'MPaymentOrder' => 'id',
		'MPaymentOrderItem' => 'orderId',
	);
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function taskDelete($id)
	{
		$items = MPaymentOrderItem::model()->findAll('orderId=?', array($id));
		foreach($items as $i)
			MPaymentOrderOptions::model()->deleteAll('itemId=?', array($i->id));
		parent::taskDelete($id);
	}
}