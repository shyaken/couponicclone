<?php
class WDealEditNewsletter extends UFormWorklet
{
	public $modelClassName = 'UDummyModel';
	
	public function title()
	{
		return $this->t('{title}: Newsletter', array(
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
			array('allow', 'roles' => array('citymanager')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function beforeAccess()
	{
		if(isset($_GET['id']) && wm()->get('deal.edit.helper')->authorize($_GET['id']))
			return null;
		$this->accessDenied();
		return false;
	}
	
	public function properties()
	{
		return array(
			'elements' => array(
				'<p>Newsletters are automatically created and scheduled within one hour before the deals starts. You can manually publish newsletter from here if you need to edit it in admin -> subscriptions.</p>',
				'<p>Also please note, that when published manually this deal will not be added into any automatically generated newsletter as a side deal.</p>',
				'attribute' => array('type' => 'hidden')
			),
			'buttons' => array(
				'submit' => array('type' => 'submit',
					'label' => $this->t('Publish Newsletter'))
			),
			'model' => $this->model
		);
	}
	
	public function afterConfig()
	{
		wm()->add('deal.edit.menu', null, array('deal' => $this->deal()));
	}
	
	public function taskBreadCrumbs()
	{
		$r = array();
		if(!app()->user->checkAccess('citymanager'))
			$r[$this->t('Company Admin')] = url('/company/admin');
		$r[$this->t('Deals')] = url('/deal/admin/list');
		$r[] = $this->deal()->name;
		$r[] = $this->t('Newsletter');
		return $r;
	}
	
	public function taskSave()
	{
		if($this->deal()->active != 1)
		{
			$this->model->addError('attribute',$this->t('Deal has to be published first.'));
			return false;
		}
		
		// we need to create an email campaign for this deal
		wm()->get('deal.helper')->emailCampaign($this->deal(),array());
	}
	
	public function ajaxSuccess()
	{
		wm()->get('base.init')->addToJson(array(
			'info' => array(
				'replace' => $this->t('Deal newsletter has been successfully published.'),
				'fade' => 'target',
				'focus' => true,
			),
		));
	}
}