<?php
class WDealEditGeneral extends UFormWorklet
{
	public $modelClassName = 'MDealGeneralForm';
	public $primaryKey='id';
	
	public function title()
	{
		return $this->t('{title}: General Settings', array(
			'{title}' => $this->deal()->name
		));
	}
	
	public function taskDeal()
	{
		return MDeal::model()->findByPk($_GET['id']);
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
		if(isset($_GET['id']) && wm()->get('deal.edit.helper')->authorize($_GET['id']))
			return true;
		$this->accessDenied();
		return false;
	}
	
	public function properties()
	{		
		return array(
			'elements' => array(
				'priority' => array('type' => 'text',
					'hint' => $this->t('When multiple deals are running at the same time in the same city the one with the highest priority will be shown on the city homepage; others will go to "side deals" block. Less number means higher priority.')),
				'timeZone' => array('type'=>'dropdownlist',
					'items' => include(app()->basePath.DS.'data'.DS.'timezones.php')),
				'companyId' => array('type' => 'dropdownlist',
					'items' => CHtml::listData(wm()->get('company.helper')->list(),'id','name')),
				'url' => array('type' => 'text', 'hint' => aUrl('/',array('deals'=>'')).'/', 'layout' => "{label}\n<span class='hintInlineBefore'>{hint}\n{input}</span>"),
				'startField' => array('type' => 'UDateTimePicker', 'htmlOptions'=>array('class'=>'medium')),
				'endField' => array('type' => 'UDateTimePicker', 'htmlOptions'=>array('class'=>'medium')),
				'redeemStartField' => array('type' => 'UDateTimePicker', 'htmlOptions'=>array('class'=>'medium')),
				'expireField' => array('type' => 'UDateTimePicker', 'htmlOptions'=>array('class'=>'medium')),
				'purchaseMin' => array('type' => 'text', 'class' => 'short'),
				'purchaseMax' => array('type' => 'text', 'class' => 'short'),
				'limitPerUser' => array('type' => 'text', 'class' => 'short'),
				'useCredits' => array('type' => 'radiolist', 'items' => array(
					1 => $this->t('Yes, users can use their credits to pay for this deal'),
					0 => $this->t('No, users are not allowed to use their credits to pay for this deal'),
				), 'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}"),
				'dummy1' => '<h4>'.$this->t('Commission').'</h4>',
				'dummy2' => '<p>'.$this->t('You can set your special commission for the deal. It will override system default and company commission settings.').'</p>',
				'commission' => array('type' => 'text', 'hint' => '%',
					'label' => $this->t('Commission'),
					'class' => 'short', 'layout' => "{label}\n<span class='hintInlineAfter'>{input}\n{hint}</span>"),
				'dummy3' => '<h4>'.$this->t('Adjust Stats').'</h4>',
				'statsAdjust'=>array('type'=>'text', 'class'=>'short'),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit',
					'label' => $this->isNewRecord?$this->t('Add'):$this->t('Save'))
			),
			'model' => $this->model
		);
	}
	
	public function afterConfig()
	{
		if(!app()->user->checkAccess('citymanager'))
		{
			unset($this->properties['elements']['companyId']);
			unset($this->properties['elements']['dummy1']);
			unset($this->properties['elements']['dummy2']);
			unset($this->properties['elements']['commission']);
		}
		else
			$this->model->scenario = 'admin';
		wm()->add('deal.edit.menu', null, array('deal' => $this->deal()));
	}
	
	public function afterSave()
	{
		wm()->get('deal.helper')->verifyTipped($this->model);
	}
	
	public function taskBreadCrumbs()
	{
		$r = array();
		if(!app()->user->checkAccess('citymanager'))
			$r[$this->t('Company Admin')] = url('/company/admin');
		$r[$this->t('Deals')] = url('/deal/admin/list');
		$r[] = $this->deal()->name;
		$r[] = $this->t('General Settings');
		return $r;
	}
	
	public function ajaxSuccess()
	{
		wm()->get('base.init')->addToJson(array(
			'info' => array(
				'replace' => $this->t('Deal has been successfully updated.'),
				'fade' => 'target',
				'focus' => true,
			),
		));
	}
}