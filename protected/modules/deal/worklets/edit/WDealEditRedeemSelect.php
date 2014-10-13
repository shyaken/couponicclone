<?php
class WDealEditRedeemSelect extends UFormWorklet
{
	public $modelClassName = 'MDealRedeemSelectForm';
	public $primaryKey='dealId';
	
	public function title()
	{
		return $this->t('{title}: Settings', array(
			'{title}' => $this->deal()->name
		));
	}
	
	public function taskDeal()
	{
		return MDeal::model()->findByPk($_GET['dealId']);
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
	
	public function properties()
	{		
		return array(
			'elements' => array(
				'requireRedeemLoc' => array('type' => 'checkbox',
					'layout' => "<fieldset>{input}\n{label}\n{hint}</fieldset>", 'afterLabel' => '')
			),
			'buttons' => array(
				'submit' => array('type' => 'submit',
					'label' => $this->t('Save'))
			),
			'model' => $this->model
		);
	}
	
	public function taskBreadCrumbs()
	{
		$r = array();
		if(!app()->user->checkAccess('citymanager'))
			$r[$this->t('Company Admin')] = url('/company/admin');
		$r[$this->t('Deals')] = url('/deal/admin/list');
		$r[] = $this->deal()->name;
		$r[] = $this->t('Redeem Location Select');
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