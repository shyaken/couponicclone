<?php
class WDealLocationRedeemUpdate extends UFormWorklet
{	
	public $modelClassName = 'MDealRedeemLocation';
	public $primaryKey = 'id';
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
		if(isset($_GET['id']))
		{
			$m = MDealRedeemLocation::model()->findByPk($_GET['id']);
			if($m)
				$_GET['dealId'] = $m->dealId;
		}
		
		if(wm()->get('deal.edit.helper')->authorize($_GET['dealId']))
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
				'address' => array('type' => 'text'),
				'zipCode' => array('type' => 'text'),
				'<h4>'.$this->t('Map Settings').'</h4>',
				'<p>'.$this->t('Leave fields below empty if you want Google Maps widget to show location automatically based on address.').'</p>',
				'<p>'.$this->t('Search Google for information on how you can obtain some place\'s longitude and latitude using Google Maps.').'</p>',
				'lat' => array('type' => 'text'),
				'lon' => array('type' => 'text'),
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
		$b->ignoreFixed = true;
	}
	
	public function afterConfig()
	{
		if(isset($_GET['ajax']))
			$this->layout = false;
	}
	
	public function ajaxSuccess()
	{
		$listWorklet = wm()->get('deal.location.redeemList');
		$json = array(
			'content' => array(
				'append' => CHtml::script('$.fn.yiiGridView.update("' .$listWorklet->getDOMId(). '-grid");
					$("#'.$this->getDOMId().'").closest(".worklet-pushed-content").remove();')
			),
		);
		wm()->get('base.init')->addToJson($json);
	}
}