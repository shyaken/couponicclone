<?php
class WPaymentPaypalIpn extends UWidgetWorklet
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
			
		include_once(dirname(__FILE__).'/../components/paypal.ipn.class.php');
		$p = new paypal_class;
		$p->ipn_log = false;
		if($this->param('sandbox'))
			$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		
		if ($p->validate_ipn()
			&& ($p->ipn_data['payment_status'] == 'Completed'
			|| $p->ipn_data['payment_status'] == 'Pending'))
		{
			if(isset($p->ipn_data['auth_id']) && $p->ipn_data['auth_id'])
				wm()->get('payment.order')->authorize($p->ipn_data['custom'],$p->ipn_data['txn_id']);
			else
				wm()->get('payment.order')->charge($p->ipn_data['custom'],$p->ipn_data['txn_id']);
		}
		app()->end();
	}
}