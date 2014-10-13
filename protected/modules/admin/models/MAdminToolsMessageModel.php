<?php
class MAdminToolsMessageModel extends UFormModel
{
	public $modules;
	public $themes;
	public $language;
	
	public static function module()
	{
		return 'admin';
	}
	
	public function rules()
	{
		return array(
			array('modules,themes,language','required')
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'modules' => $this->t('Modules'),
			'themes' => $this->t('Themes'),
			'language' => $this->t('Language')
		);
	}
}