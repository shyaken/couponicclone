<?php
class WDealLoc extends UFormWorklet
{
	public $modelClassName = 'UDummyModel';
	public $space = 'inside';
	
	public function title()
	{
		return $this->t('Select Redeem Location');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'users'=>array('*')),
		);
	}
	
	public function beforeAccess()
	{
		if(!isset($_GET['id']))
		{
			$this->accessDenied();
			return false;
		}
	}
	
	public function taskPrice()
	{
		static $model;
		if(!isset($model))
			$model = MDealPrice::model()->findByPk($_GET['id']);
		return $model;
	}
	
	public function properties()
	{
		$session = wm()->get("deal.helper")->session;
		
		if(!isset($session["loc.".$_GET['id']])){
			$criteria = new CDbCriteria;
			$criteria->condition = 'dealId=?';
			$criteria->params = array($this->price()->dealId);
			$criteria->order = 'id ASC';
			$model = MDealRedeemLocation::model()->find($criteria);
			$session["loc.".$_GET['id']] = $model->id;
			wm()->get("deal.helper")->session = $session;
		}
		
		$location = $session["loc.".$_GET['id']];
		$this->model->attribute = $location;

		return array(
			'elements' => array('attribute' => array('type'=>'radiolist',
				'items' => $this->getItems(),
				'label' => $this->t("Location"),
				'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>"),
				),
			'buttons' => array(
				'cancel' => app()->request->isAjaxRequest && !wm()->get('base.helper')->isMobile()
					? array('type' => 'UJsButton', 'attributes' => array(
						'label' => $this->t('Cancel'),
						'callback' => '$.uniprogy.dialogClose()'))
					: null,
				'submit' => array('type' => 'submit',
					'label' => $this->t('Save')),
			),
			'model' => $this->model
		);
	}
	
	public function taskGetItems(){
		$models = MDealRedeemLocation::model()->findAll('dealId=?', array($this->price()->dealId));
		$data = array();
		foreach($models as $m)
			$data[$m->id] = wm()->get('location.helper')->locationAsText($m->loc,
				$m->address,$m->zipCode,' ');
		return $data;
	}
	
	public function taskSave()
	{
		if(!$this->model->hasErrors())
		{
            wm()->get('deal.helper')->saveLocation($this->model->attribute, $_GET['id']);
		}
		return;
	}
	
	public function ajaxSuccess()
	{
		$script = '$.uniprogy.uDeal.updateLocation('.$this->price()->id.',"'.CJavaScript::quote(wm()->get('deal.helper')->currentLocation($_GET['id'])).'");$.uniprogy.dialogClose();';
		wm()->get('base.init')->addToJson(array(
			'content' => array('append' => CHtml::script($script)),
		));
	}
	
	public function afterBuild()
	{
		if(wm()->get('base.helper')->isMobile())
			$this->space = 'content';
	}
}
