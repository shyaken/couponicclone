<?php
class WPaymentCalculate extends UWidgetWorklet
{
	public $layout = false;
	
	public function taskConfig()
	{
		$json = array('total' => 0, 'js' => '');
		if(isset($_POST['items']))
		{
			$w = wm()->get('payment.checkout');
			$w->init();
			$json['total'] = $w->amount;
			
			foreach($_POST['items'] as $module=>$item)
				foreach($item as $id=>$quantity)
					if(($error=wm()->get($module.'.order')->verify($id,$quantity))!==true)
						$w->model->addError('type',$error);
			
			if($w->form->hasErrors())
			{
				$data = CJavaScript::jsonEncode(array('errors' => $w->form->errorSummaryAsArray()));
				$json['js'] = CHtml::script('jQuery("#uForm_PaymentCheckout").uForm().process('.$data.')');
			}
		}
		wm()->get('base.init')->addToJson($json);
	}
}