<?php
class BDealController extends UWorkletBehavior
{
	public function afterCreateController($route,$owner,$value)
	{
		if($value===null)
		{
			$location = wm()->get('location.helper')->urlToLocation($route);
			if(!$location)
				return;
			return app()->createController('base/default/index/location/'.$route,$owner);
		}
	}
	
	public function afterUrlRules($rules)
	{
		return CMap::mergeArray($rules, array(
			'deals/<url>*' => 'deal/view',
			'deal/rss/<location>' => 'deal/rss',
			'c/<url>' => 'deal/category',
		));
	}
	
	public function beforeBuild()
	{
		wm()->get('base.init')->setState('subscribe',false);
		$setting = (int)$this->module->param('requireSubscribe');
		if($setting<0)
			return;
		
		$allowedPages = wm()->get('deal.helper')->bypassSubscription();
		$route = app()->controller->getRouteEased();
		$bypass = in_array($route,$allowedPages);
		
		while($route && !$bypass)
		{
			$bypass = in_array($route.'/*',$allowedPages);
			if(!$bypass)
				$route = substr($route,0,strrpos($route,'/'));
		}
		
		if(!isset($_GET['bypass'])
			&& app()->user->isGuest
			&& !wm()->get('base.helper')->getFromCookie('subscribed')
			&& !$bypass)
			{
				if($setting>0 || !app()->user->getState('subscribed'))
				{
					app()->user->setState('subscribed',true);
					wm()->get('base.init')->setState('subscribe',true);
				}
			}
	}
	
	public function beforeRenderPage()
	{
		if(wm()->get('base.init')->states['subscribe'])
		{
			app()->user->setFlash('info', app()->user->getFlash('info'));
			app()->request->redirect(url('/deal/subscription'));
			return false;
		}
	}
}