<?php
class WDealInstallUpgrade_1_1_3 extends UInstallWorklet
{
	public $fromVersion = '1.1.2';
	public $toVersion = '1.1.3';
	
	public function taskSuccess()
	{
		$deals = MDeal::model()->findAll();
		foreach($deals as $deal)
		{
			if($deal->image)
			{
				$m = new MDealMedia;
				$m->dealId = $deal->id;
				$m->type = 1;
				$m->data = 'original';
				$m->order = 1;
				$m->save();
			}
		}
		parent::taskSuccess();
	}
}