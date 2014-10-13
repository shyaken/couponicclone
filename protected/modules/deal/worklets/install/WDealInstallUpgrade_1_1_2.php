<?php
class WDealInstallUpgrade_1_1_2 extends UInstallWorklet
{
	public $fromVersion = '1.1.1';
	public $toVersion = '1.1.2';
	
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array(
			'rssChannelDescription' => 'Get enough people to win a massive discount on something fun to do in {city}',
		));
	}
	
	public function taskModuleFilters()
	{
		return array(
			'user' => 'deal.main',
		);
	}
}