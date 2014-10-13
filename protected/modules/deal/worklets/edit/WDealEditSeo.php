<?php
class WDealEditSeo extends UFormWorklet
{
	public $modelClassName = 'MDealSeoForm';
	public $primaryKey='id';
	
	public function title()
	{
		return $this->t('{title}: SEO', array(
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
		Yii::import('application.modules.deal.extensions.barcode.CBarcode');
		
		return array(
			'elements' => array(
				'metaKeywords' => array(
					'type' => 'UI18NField', 'attributes' => array(
						'type' => 'text',
						'languages' => wm()->get('base.language')->languages(),
					), 'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
				),
				'metaDescription' => array(
					'type' => 'UI18NField', 'attributes' => array(
						'type' => 'textarea',
						'languages' => wm()->get('base.language')->languages(),
					), 'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
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
		wm()->get('base.helper')->translations('Deal',$this->model,'metaKeywords');
		wm()->get('base.helper')->translations('Deal',$this->model,'metaDescription');
	}
	
	public function taskBreadCrumbs()
	{
		$r = array();
		if(!app()->user->checkAccess('citymanager'))
			$r[$this->t('Company Admin')] = url('/company/admin');
		$r[$this->t('Deals')] = url('/deal/admin/list');
		$r[] = $this->deal()->name;
		$r[] = $this->t('SEO');
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