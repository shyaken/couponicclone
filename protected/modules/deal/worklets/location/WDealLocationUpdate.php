<?php
class WDealLocationUpdate extends UFormWorklet
{	
	public $modelClassName = 'MDealLocation';
	public $space = 'inside';
	
	public function title()
	{
		return $this->t('Add New Location');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company.editor')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function beforeAccess()
	{
		if(isset($_GET['dealId']) && wm()->get('deal.edit.helper')->authorize($_GET['dealId']))
			return true;
		$this->accessDenied();
		return false;
	}
	
	public function afterModel()
	{
		$this->model->dealId = $_GET['dealId'];
	}
	
	public function properties()
	{			
		return array(
			'elements' => array(
			),
			'buttons' => array(
				'cancel' => app()->request->isAjaxRequest
					? array('type' => 'UJsButton', 'attributes' => array(
						'label' => $this->t('Close'),
						'callback' => '$(this).closest(".worklet-pushed-content").remove()'))
					: null,
				'submit' => array('type' => 'submit',
					'label' => $this->t('Add')),
			),
			'model' => $this->model
		);
	}
	
	public function beforeBuild()
	{
		$b = $this->attachBehavior('location.form','location.form');
		if(!app()->user->checkAccess('administrator') && app()->user->checkAccess('citymanager'))
		{
			$locs = wm()->get('agent.citymanager.helper')->locations();
			if($locs !== true)
			{
				$c = new CDbCriteria;
				$c->addInCondition('location', $locs);
				$preset = MLocationPreset::model()->findAll($c);
				$b->preset = $preset;
			}
		}
	}
	
	public function afterConfig()
	{
		if(isset($_GET['ajax']))
			$this->layout = false;
	}
	
	public function afterSave()
	{
		MDealLocation::model()->deleteAll('dealId=? AND location=0', array(
			$_GET['dealId']
		));
	}
	
	public function ajaxSuccess()
	{
		$listWorklet = wm()->get('deal.location.list');
		$json = array(
			'content' => array(
				'append' => CHtml::script('$.fn.yiiGridView.update("' .$listWorklet->getDOMId(). '-grid");
					$("#'.$this->getDOMId().'").closest(".worklet-pushed-content").remove();')
			),
		);
		wm()->get('base.init')->addToJson($json);
	}
}