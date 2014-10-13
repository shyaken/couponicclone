<?php
class WPaymentWireInstallInstall extends UInstallWorklet
{
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array(
			'name' => 'Bank Wire',
            'info' => 'Please send your payments to:<br />Bank:<br />Account Number:<br />Details: Payment for order #{orderID}<br />',
		));
	}
	
	public function taskModuleFilters()
	{
		return array (
		  'payment' => 'payment.wire.main',
		);
	}
	
	public function taskSuccess()
	{
		$config['modules']['payment']['modules']['wire']['enabled'] = false;
		UHelper::saveConfig(Yii::getPathOfAlias('application.config.public.modules').'.php',$config);
		parent::taskSuccess();
	}
}