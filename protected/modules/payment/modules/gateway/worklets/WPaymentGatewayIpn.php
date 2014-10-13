<?php
class WPaymentGatewayIpn extends UWidgetWorklet
{
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function taskConfig()
	{
		if(app()->request->isSecureConnection)
			wm()->get('base.init')->requireSecure = true;
		wm()->get('base.init')->setState('subscribe',false);
		
		include_once(dirname(__FILE__).'/../components/Gateway.php');
		$g = new Gateway;		
		$r = $g->validate(array(
			'params' => $this->module->params,
		));
		
		if($r['status'] === true)
		{
			if($r['type'] == 'authorize')
				wm()->get('payment.order')->authorize($r['orderId'],$r['gatewayId']);
			else
				wm()->get('payment.order')->charge($r['orderId'],$r['gatewayId']);
		}
		
		if($r['after'] == 'redirect')
			app()->request->redirect($r['redirectUrl']);
		else
			app()->end();
	}
}