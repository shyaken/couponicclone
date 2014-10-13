<?php
class WDealEditStatus extends UFormWorklet
{
	public $modelClassName = 'MDealStatusForm';
	public $primaryKey='id';
	
	public function title()
	{
		return $this->t('{title}: Status', array(
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
				'<p>'.$this->t('Deal can be edited only in a "Draft" mode. If you switch to any other status you won\'t be able to edit the deal until it is switched back to "Draft".').'</p>',
				'active' => array('type' => 'radiolist', 'items' => array(
					0 => $this->t('Draft'),
					2 => $this->t('Awaiting Approval'),
					1 => $this->t('Published'),
				), 'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}"),
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
			unset($this->properties['elements']['active']['items'][1]);
		wm()->add('deal.edit.menu', null, array('deal' => $this->deal()));
	}
	
	public function beforeSave()
	{
		if($this->model->active == 0)
			return;
			
		if(!app()->user->checkAccess('citymanager') && $this->model->active == 1)
			$this->model->active = 0;
			
		$m = new MDealForm;
		$m->attributes = $this->model->attributes;
		$m->validate();
		if($m->hasErrors())
		{
			foreach($m->getErrors() as $a => $errs)
				foreach($errs as $e)
					$this->model->addError('active',$e);
		}
		
		if(!$this->model->name)
			$this->model->addError('active',Yii::t('yii','{attribute} cannot be blank.', array(
				'{attribute}' => $this->t('Name')
			)));
		
		$specials = array('value' => $this->t('Value'), 'price' => $this->t('Price'));
		
		foreach($specials as $s=>$n)
			if($this->model->$s===null)
				$this->model->addError('active',Yii::t('yii','{attribute} cannot be blank.', array(
					'{attribute}' => $n
				)));
		
		$locs = MDealLocation::model()->count('dealId=?',array($this->model->id));
			
		if(!$locs)
			$this->model->addError('active',$this->t('You must choose at least one location for this deal.'));
			
		if($this->model->hasErrors())
			return false;
	}
	
	public function taskBreadCrumbs()
	{
		$r = array();
		if(!app()->user->checkAccess('citymanager'))
			$r[$this->t('Company Admin')] = url('/company/admin');
		$r[$this->t('Deals')] = url('/deal/admin/list');
		$r[] = $this->deal()->name;
		$r[] = $this->t('Status');
		return $r;
	}
	
	public function ajaxSuccess()
	{
		$json = array(
			'info' => array(
				'replace' => $this->t('Deal has been successfully updated.'),
				'fade' => 'target',
				'focus' => true,
			),
		);
		if(!app()->user->checkAccess('citymanager') && $this->model->active != 0)
			$json['redirect'] = url('/company/admin');
		
		wm()->get('base.init')->addToJson($json);
	}
}