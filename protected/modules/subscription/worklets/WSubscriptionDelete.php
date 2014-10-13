<?php
class WSubscriptionDelete extends UWidgetWorklet
{
	public $show = false;
	
	public function taskConfig()
	{
		if(isset($_GET['h']))
		{
			wm()->get('subscription.helper')->removeEmailByHash($_GET['h']);
			app()->user->setFlash('info', $this->t('You have been successfully unsubscribed!'));
			app()->user->setFlash('info.fade', false);
			app()->request->redirect(url('/'));
		}
	}
}