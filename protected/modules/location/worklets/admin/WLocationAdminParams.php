<?php
class WLocationAdminParams extends UParamsWorklet
{
	public function title()
	{
		return txt()->format(ucfirst($this->module->name),' ',$this->t('Module'));
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function properties()
	{
		$countries = CHtml::listData(wm()->get('location.helper')->loadCountries(),'code','name');
		$countries = array('*' => $this->t('All Countries')) + $countries;
		return array(
			'elements' => array(
				'<h4>Other Settings</h4>',
				'defaultCountry' => array('type' => 'dropdownlist',
					'items' => $countries,
					'label' => $this->t('Supported Country')),
				'ipinfodbApiKey' => array(
					'type' => 'text',
					'label' => $this->t('IPInfoDB.com API Key'),
					'hint' => $this->t('{app} uses {site} API to extract visitors location from their IP. You can get your API key by registering with them.',
						array('{app}' => app()->title,
							'{site}' => CHtml::link('IPInfoDB.com',
								'http://ipinfodb.com', array('target'=>'_blank')))),
				),
				'selector' => array(
					'label' => $this->t('Locations Selector Type'),
					'type' => 'radiolist', 'items' => array(
						'simple' => $this->t('Basic'),
						'complex' => $this->t('Advanced')
					),
					'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
					'hint' => $this->t('Advanced selector allows users to filter locations by countries and states.')
				),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit', 'label' => $this->t('Save'))
			),
			'model' => $this->model
		);
	}
	
	public function beforeBuild()
	{
		$b = $this->attachBehavior('location.form','location.form');
	}
	
	public function beforeCreateForm()
	{
		$this->insertBefore('location', array('locTitle'=>array('type' => 'UForm','elements'=>array('<h4>Default Location</h4>'))));
	}
	
	public function afterSave()
	{
		wm()->get('base.init')->addToJson(array(
			'load' => array(
				'url' => url('/location/admin/params')
			)
		));
	}
	
	public function afterConfig()
	{
		if(app()->request->isAjaxRequest)
			$this->layout = false;
	}
}