<?php
class WBaseInstallUpgrade_1_1_1 extends UInstallWorklet
{
	public $fromVersion = '1.1.0';
	public $toVersion = '1.1.1';
	
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array (
			'follow' => 
			array (
				0 => 
					array (
					0 => 'Twitter',
					1 => 'twitter.png',
					2 => 'http://www.twitter.com',
					),
				1 => 
					array (
					0 => 'Facebook',
					1 => 'facebook.png',
					2 => 'http://www.facebook.com',
					),
				2 => 
					array (
					0 => 'RSS',
					1 => 'rss.png',
					2 => 'return wm()->get("deal.rss")->link();',
					),
				3 => 
					array (
					0 => 'Email',
					1 => 'email.png',
					2 => 'return url("/deal/subscription");',
					),
			),
		));
	}
}