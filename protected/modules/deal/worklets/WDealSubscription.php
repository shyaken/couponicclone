<?php
Yii::import('deal.worklets.WDealSubscribe');
class WDealSubscription extends WDealSubscribe
{
	public $missingDealsLocation;
	public $initSubs=false;
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function title()
	{
		return $this->missingDealsLocation
			? ($this->param('categories')<=0
				? $this->t('Deals for {city} are coming soon!', array('{city}' => $this->missingDealsLocation->cityName))
				: $this->t('Deals are coming soon!'))
			: $this->t('Email is the Best Way to Get the Daily Deal');
	}
	
	public function taskConfig()
	{
		if(app()->controller->routeEased == 'deal/subscription'
			&& !wm()->get('base.helper')->isMobile())
				app()->controller->layout = 'splash';
		if($this->missingDealsLocation)
			$this->missingDealsLocation = wm()->get('location.helper')->locationToData(
				$this->missingDealsLocation, true);
		parent::taskConfig();
		$this->properties['action'] = url('/deal/subscription');
	}
	
	public function taskRenderOutput()
	{
		$this->render('splash');
	}
	
	public function taskSave()
	{
		if(app()->user->isGuest && !wm()->get('base.helper')->getFromCookie('subscribed'))
			$this->initSubs = true;
		return parent::taskSave();
	}
	
	public function ajaxSuccess()
	{
		$w = wm()->get('location.helper');
		$location = $w->locationToData($this->model->location,true);
		$redirect = $this->initSubs
			? url('/',$w->urlParams($location))
			: url('/');
		
		wm()->get('base.init')->addToJson(array(
			'info' => $this->t('You have been successfully subscribed! Thank you!'),
			'content' => '<!-- # -->',
			'redirect' => $redirect,
		));
	}
}