<?php
class WDealEditHelper extends USystemWorklet
{
	public function taskAuthorize($id)
	{
		$deal = $id instanceOf MDeal ? $id : $this->deal($id);
		
		$locations = array();
		foreach($deal->locs as $loc)
			$locations[] = $loc->location;
		
		if($deal && (app()->user->checkAccess('administrator')
			|| (app()->user->checkAccess('citymanager')
				&& wm()->get('agent.citymanager.helper')->checkAccess($deal->id, $locations, 'deal'))
			|| ($deal->active == 0
				&& app()->user->checkAccess('company.edit',$deal->company,false))))
					return true;
		return false;
	}
	
	public function taskDeal($id)
	{
		static $deal;
		if(!isset($deal))
			$deal = MDeal::model()->findByPk($id);
		return $deal;
	}
	
	public function taskDealName($id,$name)
	{
		$language = wm()->get('base.helper')->defaultConfig('language');
		if(!$language)
			$language = app()->sourceLanguage;
		
		MI18N::model()->deleteAll('model=? AND relatedId=? AND name=?', array('Deal', $id, 'name'));
		
		$m = new UDynamicModel;
		$m->import(array('id','name'), array());
		$m->id = $id;
		$m->name = array($language => isset($name[$language])?$name[$language]:null);
		wm()->get('base.helper')->translations('Deal', $m, 'name');
	}
}