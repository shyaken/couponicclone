<?php
class WLocationInstallUpgrade_1_1_3 extends UInstallWorklet
{
	public $fromVersion = '1.1.2';
	public $toVersion = '1.1.3';
	
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array (
		  'src' => 'deals',
		  'fixed' => 
		  array (
		  ),
		));
	}
	
	public function taskSuccess()
	{
		$models = MLocation::model()->findAll();
		foreach($models as $m)
		{
			$cityASCII = wm()->get('location.helper')->cityToASCII($m->city);
			if(!trim($cityASCII))
				$cityASCII = $m->id;
			$m->cityASCII = $cityASCII;
			$m->save();
		}
		parent::taskSuccess();
	}
}