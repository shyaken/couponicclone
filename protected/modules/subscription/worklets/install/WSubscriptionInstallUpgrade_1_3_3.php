<?php
class WSubscriptionInstallUpgrade_1_3_3 extends UInstallWorklet
{
	public $fromVersion = '1.3.2';
	public $toVersion = '1.3.3';
	
	public function taskSuccess()
	{
		$listFields = app()->db->createCommand("SHOW FIELDS FROM `{{SubscriptionCampaignList}}`")->queryAll();
		$exists = 0;
		foreach($listFields as $f)
			if($f['Field'] == 'listId')
				$exists = 1;
			
		if(!$exists)
			app()->db->createCommand("ALTER TABLE `{{SubscriptionCampaignList}}`
				ADD `listId` BIGINT UNSIGNED")->execute();
			
		$campaignFields = app()->db->createCommand("SHOW FIELDS FROM `{{SubscriptionCampaign}}`")->queryAll();
		foreach($campaignFields as $f)
			if($f['Field'] == 'listId')
				app()->db->createCommand("ALTER TABLE `{{SubscriptionCampaign}}` DROP `listId`")->execute();
				
		parent::taskSuccess();
	}
}