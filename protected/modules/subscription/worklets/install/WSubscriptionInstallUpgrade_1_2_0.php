<?php
class WSubscriptionInstallUpgrade_1_2_0 extends UInstallWorklet
{
	public $fromVersion = '1.1.3';
	public $toVersion = '1.2.0';
	
	public function taskSuccess()
	{
		parent::taskSuccess();
		
		Yii::import('subscription.models.*');
		
		$sql = "SELECT * FROM {{DealSubscribe}}";
		$reader = app()->db->createCommand($sql)->query();
		while(($row=$reader->read())!==false)
		{
			wm()->get('subscription.helper')->addEmailToList($row['email'],array(
				'type' => 0, 'relatedId' => $row['location']
			),$row['hash']);
		}
		
		app()->db->createCommand("DROP TABLE IF EXISTS `{{DealSubscribe}}`")->execute();
	}
}