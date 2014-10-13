<?php
class WPaymentCcdirectInstallInstall extends UInstallWorklet
{
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array(
			'name' => 'Credit Card',
            'gateway' => 'paypal',
            'cconly' => '0'
		));
	}
	
	public function taskSuccess()
	{
		$config['modules']['payment']['modules']['ccdirect']['enabled'] = false;
		UHelper::saveConfig(Yii::getPathOfAlias('application.config.public.modules').'.php',$config);
		parent::taskSuccess();
	}
}