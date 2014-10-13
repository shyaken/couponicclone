<?php
class WPaymentCheckout extends UFormWorklet
{
	public $modelClassName = 'MPaymentCheckoutForm';
	public $amount=0;
	public $items=array();
	public $transactionType='pay';
	public $paymentTypes=null;
	public $forceShow=false;
	
	public function accessRules()
	{
		return array(
			array('allow', 'users'=>array('*'))
		);
	}
	
	public function properties()
	{
		$types = $this->paymentTypes();
		if(!$this->model->type)
			$this->model->type = key($types);
		$typeField = array('type' => 'radiolist', 'items' => $types,
			'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>");
		if(count($types) == 1)
			$typeField = array('type' => 'hidden', 'value' => key($types));
		
		$elements = array('type' => array('type' => 'hidden', 'value' => 'system'));
		if(!$this->param('creditsOnly') || $this->forceShow)
		{
			$elements = array(
				'opendiv' => '<div class="paymentInformation">',
				'paymentHeader' => (count($types) == 1 && key($types)!='ccdirect') ? '' : '<h3>'.$this->t('Payment Information').'</h3>',
				'type' => $typeField,
				'closediv' =>'</div>',
			);
		}
		
		return array(
			'action' => url('/payment/checkout'),
			'elements' => $elements,
			'buttons' => array(
				'submit' => array('type' => 'submit',
					'label' => $this->t('Proceed')),
			),
			'model' => $this->model
		);
	}
	
	public function paymentTypes()
	{
		$modules = app()->modules['payment']['modules'];
		$types = array();
		foreach($modules as $name=>$config)
			if((!isset($config['enabled']) || $config['enabled'])
				&& (!isset($config['params']['cconly']) || !$config['params']['cconly']))
				$types[$name] = $config['params']['name'];
		if($this->paymentTypes)
			foreach($types as $k=>$v)
				if(!isset($this->paymentTypes[$k]))
					unset($types[$k]);
		return $types;
	}
	
	public function beforeBuild()
	{
		foreach($this->paymentTypes() as $id => $title)
		{
			$w = wm()->get('payment.'.$id.'.form');
			if($w)
				$this->attachBehavior('payment.'.$id.'.form','payment.'.$id.'.form');
		}
		
		if(app()->user->isGuest)
			$this->attachBehavior('user.authForm','user.authForm');
	}
	
	public function afterCreateForm()
	{
		if(isset($_POST['items']))
		{
			foreach($_POST['items'] as $module=>$item)
				foreach($item as $id=>$quantity)
				{
					$_POST['items'][$module][$id] = $quantity
						= str_replace(app()->locale->getNumberSymbol('decimal'),'.',$quantity) * 1;
					if($quantity == 0)
					{
						unset($_POST['items'][$module][$id]);
						if(!count($_POST['items'][$module]))
							unset($_POST['items'][$module]);
					}
					else
						wm()->get($module.'.order')->verify($id,$quantity);						
				}
						
			if($this->amount==0)
				$this->model->type = 'system';
		}
	}
	
	public function taskSave()
	{
		if($this->form->hasErrors())
			return;
		
		$this->amount = 0;
		$this->items = array();
		
		$items = isset($_POST['items'])?$_POST['items']:null;
		if(!$items || !is_array($items) || !count($items))
			return $this->model->addError('type',$this->t('Cart is empty.'));
			
		foreach($items as $module=>$item)
			foreach($item as $id=>$quantity)
				if(($error=wm()->get($module.'.order')->verify($id,$quantity))!==true)
					return $this->model->addError('type',$error);
		
		if($this->amount<0)
			return $this->model->addError('type', $this->t('Total amount may not be negative.'));
			
		if($this->amount==0)
			$this->model->type = 'system';
		
		if($this->model->type == 'system' && !isset($items['payment']['0']))
		{
			$_POST['items']['payment']['0'] = $this->amount;
			return $this->taskSave();
		}
			
		$order = wm()->get('payment.order')->place($items,$this->amount,$this->model->type);
		$this->successUrl = url('/payment/success', array('id' => $order->id));
		
		$methodsToConvert = explode(',',$this->param('convertMethods'));
		if($this->param('convertRate') && $this->param('convertCode')
			&& in_array($this->model->type, $methodsToConvert))
		{
			foreach($this->items as $k=>$v)
				$this->items[$k]['price'] = round($v['price'] * $this->param('convertRate'),2);
			$this->module->params['cCode'] = $this->param('convertCode');
		}
		
		$processor = 'payment.' . $this->model->type . '.' . $this->transactionType;
		wm()->get($processor)->run($this->items,$order->id);
	}
	
	public function ajaxSuccess()
	{
		if(count(wm()->get('base.init')->getJson()) > 0)
			return;
		return parent::ajaxSuccess();
	}
	
	public function taskRenderBegin()
	{		
		cs()->registerScript(__CLASS__,'jQuery(\'#'.$this->form->id.' input[name$="[type]"]:radio,#'.$this->form->id.' input[name$="[type]"]:hidden\').change(function(){
			if($(this).is(":hidden") || $(this).is(":checked"))
			{
				$(".paymentForm").hide();
				$("#"+$(this).val()).show();
			}
		});
		if(jQuery(\'#'.$this->form->id.' input[name$="[type]"]:radio\').length)
			jQuery(\'#'.$this->form->id.' input[name$="[type]"]:radio:checked\').change();
		else
			jQuery(\'#'.$this->form->id.' input[name$="[type]"]:hidden\').change();
		');
		parent::taskRenderBegin();
	}
}