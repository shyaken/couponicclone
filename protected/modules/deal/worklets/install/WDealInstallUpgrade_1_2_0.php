<?php
class WDealInstallUpgrade_1_2_0 extends UInstallWorklet
{
	public $fromVersion = '1.1.3';
	public $toVersion = '1.2.0';
	
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array (
		  'delimiter' => ',',
		));
	}
	public function taskModuleFilters()
	{
		return array (
		  'subscription' => 'deal.main',
		);
	}
	
	public function taskSuccess()
	{
		parent::taskSuccess();
		
		$sql = "SELECT * FROM {{Deal}}";
		$reader = app()->db->createCommand($sql)->query();
		while(($row=$reader->read())!==false)
		{
			$sql = "INSERT INTO {{DealI18N}} VALUES (null,?,?,?,?)";
			$fields = array('name','finePrint','highlights','description',
				'metaKeywords','metaDescription');
			foreach($fields as $f)
			{
				app()->db->createCommand($sql)->execute(array(
					$row['id'], app()->sourceLanguage, $f, $row[$f]
				));
			}
		}
		
		app()->db->createCommand("ALTER TABLE `{{Deal}}`
			DROP `name`,
			DROP `finePrint`,
			DROP `highlights`,
			DROP `description`,
			DROP `metaKeywords`,
			DROP `metaDescription`;")->execute();
	}
}