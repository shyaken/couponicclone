<?php
class WPaymentAdminView extends UFormWorklet
{
	public $modelClassName = 'MPaymentOrder';
	public $primaryKey = 'id';
	public $showSuccess = false;
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function properties()
	{
		$buttons = array();
		if($this->model->status == 0)
			$buttons['authorize'] = array('type' => 'submit',
				'label' => $this->t('Mark as {type}',array('{type}'=>'authorized')));
		if($this->model->status == 1)
			$buttons['charge'] = array('type' => 'submit',
				'label' => $this->t('Mark as {type}',array('{type}'=>'paid')));
		if($this->model->status != 0 && $this->model->status != 3)	
			$buttons['void'] = array('type' => 'submit',
				'label' => $this->t('Mark as {type}',array('{type}'=>'voided/refunded')));
		
		return array(
			'elements' => array('attribute' => array('type' => 'hidden')),
			'buttons' => $buttons,
			'model' => $this->model
		);
	}
	
	public function taskSave()
	{
		$helper = wm()->get('payment.order');
		if($this->form->clicked('authorize'))
		{
			$helper->authorize($this->model->id,0);
			$this->showSuccess = true;
		}
		elseif($this->form->clicked('charge'))
			$helper->charge($this->model->id);
		elseif($this->form->clicked('void'))
			$helper->void($this->model->id);
	}
	
	public function taskRenderOutput()
	{
		$this->render('orderInfo');
		return parent::taskRenderOutput();
	}
	
	public function taskBreadCrumbs()
	{
		$r = array();
		$r[$this->t('Orders')] = url('/payment/admin/list');
		$r[] = $this->t('Order #{id}',array('{id}'=>$_GET['id']));
		return $r;
	}
	
	public function ajaxSuccess()
	{
		if(count(wm()->get('base.init')->getJson()) > 0)
			return;
		
		$json = array(
			'info' => array(
				'replace' => $this->t('Changes have been applied successfully!'),
				'fade' => 'target',
				'focus' => true,
			),
			'redirect' => url('/payment/admin/view',array('id'=>$_GET['id'])),
			'redirectDelay' => 2000,
		);
		
		if($this->showSuccess)
		{
			$_GET['id'] = $this->model->id;
			$content = app()->controller->worklet('payment.success', array(), true);
			$json['content'] = array('append' => '<div style="visibility: hidden">'.$content.'</div>');
		}
		
		wm()->get('base.init')->addToJson($json);
	}
}