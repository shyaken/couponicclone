<?php
class WDealInstallUpgrade_1_5_1 extends UInstallWorklet
{
	public $fromVersion = '1.5.0';
	public $toVersion = '1.5.1';
	
	public function taskSuccess()
	{
		$deals = MDeal::model()->findAll();
		foreach($deals as $d)
		{
			if(!$d->priceOption)
			{
				$m = new MDealPrice;
				$m->dealId = $d->id;
				$m->price = null;
				$m->value = null;
				$m->main = 1;
				$m->save();
			}
		}
		return parent::taskSuccess();
	}
}