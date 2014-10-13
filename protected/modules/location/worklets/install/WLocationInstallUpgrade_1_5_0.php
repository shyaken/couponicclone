<?php
class WLocationInstallUpgrade_1_5_0 extends UInstallWorklet
{
	public $fromVersion = '1.4.4';
	public $toVersion = '1.5.0';
	
	public function taskSuccess()
	{
		// moving locations i18n data to universal i18n table
		if(file_exists(Yii::getPathOfAlias('application.models.MLocationI18N').'.php'))
		{
			$models = MLocationI18N::model()->findAll();
			foreach($models as $m)
			{
				$n = new MI18N;
				$n->model = 'Location';
				$n->relatedId = $m->locationId;
				$n->language = $m->language;
				$n->name = $m->name;
				$n->value = $m->value;
				$n->save();
			}
		}
		
		parent::taskSuccess();
	}
}