<?php
class WDealInstallUpgrade_1_3_0 extends UInstallWorklet
{
	public $fromVersion = '1.2.3';
	public $toVersion = '1.3.0';
	
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array (
		  'requireSubscribe' => '0',
		));
	}
	public function taskSuccess()
	{
		$models = MDeal::model()->findAll();
		foreach($models as $m)
		{
			$loc = new MDealLocation;
			$loc->dealId = $m->id;
			$loc->location = $m->location;
			$loc->save();
			
			$redeem = new MDealRedeemLocation;
			$redeem->dealId = $m->id;
			$redeem->location = $m->location;
			$redeem->address = $m->address;
			$redeem->save();
		}
		
		app()->db->createCommand("ALTER TABLE `{{Deal}}`
			DROP `location`,
			DROP `address`")->execute();
		parent::taskSuccess();
	}
}