<?php
class WDealRss extends UWidgetWorklet
{
	public $deals;
	public $location;
	public $layout = false;
	
	public function taskLink()
	{
		$locHelper = wm()->get('location.helper');
		$location = $locHelper->locationToData(wm()->get('deal.helper')->location(), true);
		return url('/deal/rss',$locHelper->urlParams($location));
	}
	
	public function taskConfig()
	{
		$this->location = wm()->get('deal.helper')->location();
		$c = new CDbCriteria;
		$c->with = array('locs');
		$c->condition = 'locs.location = 0 OR locs.location = :loc';
		$c->params = array(':loc' => $this->location);
		$c->compare('active',1);
		$c->compare('start','<='.time());
		$c->order = '`end` DESC';
		$this->deals = MDeal::model()->findAll($c);		
		
		wm()->get('base.init')->renderType = 'ajax-no-scripts';
		parent::taskConfig();
	}
	
	public function taskRenderOutput()
	{
		Header('Content-Type:text/xml; charset=UTF-8');
		$this->render('rss');
	}
}