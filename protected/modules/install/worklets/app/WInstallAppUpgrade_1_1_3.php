<?php
class WInstallAppUpgrade_1_1_3 extends UInstallWorklet
{
	public $fromVersion = '1.1.2';
	public $toVersion = '1.1.3';
	
	public function getModule()
	{
		return app();
	}
	
	public function taskSuccess()
	{
		$sql = "SHOW TABLES";
		$command = app()->db->createCommand($sql);
		$dataReader = $command->query();
		while(($row=$dataReader->read())!==false)
		{
			$sql = "ALTER TABLE `".current($row)."` CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci";
			app()->db->createCommand($sql)->execute();
		}
		
		//clean up assets dir
		app()->file->set(Yii::getPathOfAlias('webroot.assets'))->purge();
		
		parent::taskSuccess();
	}
	
}