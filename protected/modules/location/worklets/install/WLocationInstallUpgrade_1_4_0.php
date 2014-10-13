<?php
class WLocationInstallUpgrade_1_4_0 extends UInstallWorklet
{
	public $fromVersion = '1.3.4';
	public $toVersion = '1.4.0';
	
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array(
			'selector' => 'simple',
		));
	}
	
	public function taskSuccess()
	{
		$fixed = $this->param('fixed');
		foreach($fixed as $f)
		{
			$exists = MLocationPreset::model()->exists('location=?', array($f));
			if(!$exists)
			{
				$o = MLocation::model()->findByPk($f);
				$m = new MLocationPreset;
				$m->location = $f;
				$m->url = $o->cityASCII;
				$m->save();
			}
		}
		parent::taskSuccess();
	}
}