<?php
class WPaymentAffiliateDelete extends UDeleteWorklet
{	
	public $modelClassName = 'MPaymentAffiliate';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
}