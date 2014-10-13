<?php
class WPaymentMenu extends UMenuWorklet
{
	public $space = 'sidebar';
	
	public function taskRenderOutput()
	{
		if($this->param('creditsOnly'))
			$this->render('buyMoreButton');
	}


	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function beforeConfig()
	{
		if(app()->user->isGuest)
			return $this->show = false;
	}
	
	public function title()
	{
		return $this->t('You have {amount} of credit',
			array('{amount}' => m('payment')->format(wm()->get('payment.helper')->credit())));
	}
	
	public function properties()
	{
		return array(
			'items'=>array()
		);
	}
	
	public function taskEnabled()
	{
		return wm()->get('payment.helper')->credit() || $this->param('creditsOnly')?true:false;
	}
}