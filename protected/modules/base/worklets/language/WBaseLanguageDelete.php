<?php
class WBaseLanguageDelete extends UDeleteWorklet
{	
	public $modelClassName = 'dummy';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function taskDelete($id)
	{
		$langs = $this->param('languages');
		foreach($langs as $k=>$v)
			if($k == $id)
			{
				if(count($langs) == 1)
					throw new CHttpException(403,$this->t('At least one language should stay.'));
				unset($langs[$k]);
			}
		$file = Yii::getPathOfAlias('application.config.public.modules').'.php';
		$config['modules']['base']['params']['languages'] = null;
		UHelper::saveConfig($file,$config);
		$config['modules']['base']['params']['languages'] = $langs;
		UHelper::saveConfig($file,$config);
		$this->module->params['languages'] = $langs;
	}
}