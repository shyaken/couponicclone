<?php
class WPaymentCreditList extends UListWorklet
{
	public $modelClassName = 'MPaymentUserModel';
	public $addCheckBoxColumn=false;
	public $addMassButton=false;
	
	public function title()
	{
		return $this->t('Manage Users Credit');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Name'),'name' => 'lastName',
				'value' => '$data->name'),
			array('header' => $this->t('Email'),'name' => 'email'),
			array('header' => $this->t('Credit'),'name' => 'credit',
				'value' => 'm("payment")->format($data->credit?$data->credit->amount:0)',
				'filter' => false),
			'buttons' => array(
				'class' => 'CButtonColumn',
				'template' => '{update}',
				'buttons' => array(
					'update' => array('click' => 'function(){
						$("#' .$this->getDOMId(). '").uWorklet().load({position:"appendReplace",url: $(this).attr("href")});
						return false;}'),					
				),
			)
		);
	}
	
	public function beforeConfig()
	{
		if(!isset($_GET[$this->modelClassName.'_sort']))
			$_GET[$this->modelClassName.'_sort'] = 'email';
	}
	
	public function afterBuild()
	{
		wm()->get('base.init')->setState('admin',true);
	}
}