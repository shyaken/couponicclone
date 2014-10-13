<?php
class WBaseInstallUpgrade_1_3_0 extends UInstallWorklet
{
	public $fromVersion = '1.2.3';
	public $toVersion = '1.3.0';
	
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array (
			'languages' => array(
				'en_us' => 'English (US)',
				'pt_br' => 'Português (Brasil)',
				'he' => 'עברית',
				'cs_cz' => 'Český',
				'es_es' => 'Español',
				'el_gr' => 'Ελληνικά',
				'ja_jp' => '日本語',
			),
		));
	}
}