<?php
class WInstallAppUpgrade_1_4_0 extends UInstallWorklet
{
	public $fromVersion = '1.3.4';
	public $toVersion = '1.4.0';
	
	public function taskSuccess() {
        parent::taskSuccess();
		$original = require(Yii::getPathOfAlias('application.config.public.instance') . '.php');
        $config['theme'] = isset($original['theme']) ? $original['theme'] : '';
		$config['name'] = isset($original['name']) ? $original['name'] : '';
        UHelper::saveConfig(Yii::getPathOfAlias('application.config.public.modules') . '.php', $config);
    }
	
	public function getModule()
	{
		return app();
	}
}