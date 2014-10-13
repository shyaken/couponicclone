<?php
class WDealMediaPosition extends UWidgetWorklet
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
		$max = app()->db->createCommand("SELECT MAX(`order`) FROM {{DealMedia}} WHERE dealId=?")->queryScalar(array(
			$m->dealId
		));
		$query = '';
		if($_GET['dir'] == 'up' && $m->order > 1)
		{
			$m->order--;
			$m->save();
			$query = "UPDATE {{DealMedia}} SET `order` = `order`+1 WHERE `order`=? AND
				dealId=? AND id<>?";
		}
		elseif($_GET['dir'] == 'down' && $m->order < $max)
		{
			$m->order++;
			$m->save();
			$query = "UPDATE {{DealMedia}} SET `order` = `order`-1 WHERE `order`=? AND
				dealId=? AND id<>?";
		}
		if($query)
			app()->db->createCommand($query)->execute(array($m->order,$m->dealId,$m->id));
	}
}