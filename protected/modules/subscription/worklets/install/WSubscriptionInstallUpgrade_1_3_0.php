<?php
class WSubscriptionInstallUpgrade_1_3_0 extends UInstallWorklet
{
	public $fromVersion = '1.2.3';
	public $toVersion = '1.3.0';
	
	public function taskSuccess()
	{
		$models = MSubscriptionCampaign::model()->findAll();
		foreach($models as $m)
		{
			$n = new MSubscriptionCampaignList;
			$n->campaignId = $m->id;
			$n->listId = $m->listId;
			$n->save();
		}
		app()->db->createCommand("ALTER TABLE `{{SubscriptionCampaign}}` DROP `listId`")->execute();
		parent::taskSuccess();
	}
}