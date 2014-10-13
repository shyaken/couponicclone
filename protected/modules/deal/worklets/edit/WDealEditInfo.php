<?php
class WDealEditInfo extends UFormWorklet
{
	public $modelClassName = 'MDealInfoForm';
	public $primaryKey='id';
	
	public function title()
	{
		return $this->t('{title}: Information', array(
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
				'finePrint' => array(
					'type' => 'UI18NField', 'attributes' => array(
						'type' => 'textarea',
						'languages' => wm()->get('base.language')->languages(),
					), 'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
				),
				'highlights' => array(
					'type' => 'UI18NField', 'attributes' => array(
						'type' => 'textarea',
						'languages' => wm()->get('base.language')->languages(),
					),
					'hint' => $this->t('single item per line'),
					'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
				),
				'description' => array(
					'type' => 'UI18NField', 'attributes' => array(
						'type' => 'UCKEditor',
						'languages' => wm()->get('base.language')->languages(),
					),
					'layout' => "<div class='clearfix'>{label}</div>{input}\n{hint}"
				),
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
		wm()->add('deal.edit.menu', null, array('deal' => $this->deal()));
	}
	
	public function afterSave()
	{		
		$w = wm()->get('base.helper');
		$w->translations('Deal',$this->model,'finePrint');
		$w->translations('Deal',$this->model,'highlights');
		$w->translations('Deal',$this->model,'description',true);
	}
	
	public function taskBreadCrumbs()
	{
		$r = array();
		if(!app()->user->checkAccess('citymanager'))
			$r[$this->t('Company Admin')] = url('/company/admin');
		$r[$this->t('Deals')] = url('/deal/admin/list');
		$r[] = $this->deal()->name;
		$r[] = $this->t('Information');
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