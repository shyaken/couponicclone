<?php
class WDealMediaMain extends UWidgetWorklet
{
	public $show = false;
	
	public function beforeAccess()
	{
		if(!wm()->get('deal.edit.helper')->authorize($this->model()->dealId))
		{
			$this->accessDenied();
			return false;
		}
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company.editor')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function taskModel()
	{
		static $m;
		if(!isset($m))
			$m = MDealMedia::model()->findByPk($_GET['id']);
		return $m;
	}
	
	public function taskConfig()
	{
		$m = $this->model();
		if($m)
		{
			$original = MDealMedia::model()->find('dealId=? AND data=?',array($m->dealId,'original'));
			$bin = app()->storage->bin($m->deal->image);
			
			$from = $bin->get($m->data);
			$to = $bin->get('original');
			if($from && $to)
				$this->switch($from, $to);
			
			$from = $bin->get($m->data.'_t');
			$to = $bin->get('original_t');
			if($from && $to)
				$this->switch($from, $to);
			
		}
	}
	
	public function taskSwitch($from,$to)
	{
		$tmp = $from->name;
		$from->name = $to->name;
		$to->name = $tmp;
		$from->save();
		$to->save();
		
	}
}