<?php
class WDealEditMenu extends UMenuWorklet
{
	public $space = 'sidebar';
	public $deal;
	public $company;
	
	public function accessRules()
	{
		return array(
			array('allow','roles'=>array('company')),
			array('deny','users'=>array('*'))
		);
	}
	
	public function properties()
	{	
		return array('items' => array(
			array('label' => $this->t('General Settings'), 
				'url' => array('/deal/edit/general', 'id' => $this->deal->id)),
			array('label' => $this->t('Locations'), 
				'url' => array('/deal/location/list', 'dealId' => $this->deal->id)),
			array('label' => $this->t('Categories'),
				'url' => array('/deal/edit/category', 'id' => $this->deal->id),
				'visible' => $this->param('categories')>=0),
			array('label' => $this->t('Price'),
				'url' => array('/deal/price/list', 'id' => $this->deal->id)),
			array('label' => $this->t('Payment Options'),
				'url' => array('/deal/edit/payment', 'id' => $this->deal->id)),
			array('label' => $this->t('Information'), 
				'url' => array('/deal/edit/info', 'id' => $this->deal->id)),
			array('label' => $this->t('SEO'), 
				'url' => array('/deal/edit/seo', 'id' => $this->deal->id)),
			array('label' => $this->t('Reviews'), 
				'url' => array('/deal/review/list', 'id' => $this->deal->id)),
			array('label' => $this->t('Slideshow'), 
				'url' => array('/deal/media/list', 'dealId' => $this->deal->id)),
			array('label' => $this->t('Custom Background'),
				'url' => array('/deal/edit/background', 'id' => $this->deal->id)),
			array('label' => $this->t('Status'), 
				'url' => array('/deal/edit/status', 'id' => $this->deal->id)),
			array('label' => $this->t('Newsletter'),
				'url' => array('/deal/edit/newsletter', 'id' => $this->deal->id),
				'visible' => app()->user->checkAccess('citymanager')),
			array('label' => $this->t('Re-post this deal'),
				'url' => array('/deal/edit/repost', 'id' => $this->deal->id),
				'visible' => app()->user->checkAccess('citymanager')),
		));
	}
	
	public function active($route)
	{
		return app()->controller->getRouteEased() == $route;
	}
}