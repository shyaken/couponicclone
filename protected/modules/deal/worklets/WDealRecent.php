<?php
class WDealRecent extends UListWorklet
{
	public $modelClassName = 'MDealRecentListModel';
	public $type = 'list';
	
	public function taskConfig()
	{
		parent::taskConfig();
		$this->model->location = wm()->get('deal.helper')->location();
		$category = wm()->get('deal.category.helper')->userCategory();
		if($category)
			$this->model->category = $category;
	}
	
	public function title()
	{
		if($this->param('categories') <= 0)
		{
			$location = wm()->get('location.helper')->locationToData(
				wm()->get('deal.helper')->location(), true
			);
			return $this->t('Recent Deals for {city}',array('{city}'=>$location->cityName));
		}
		else
			return $this->t('Recent Deals');
	}
	
	public function itemView()
	{
		return 'deal';
	}
	
	public function taskRenderOutput()
	{
		$this->beginContent('list');
		parent::taskRenderOutput();
		$this->endContent();
	}
	
	public function afterBuild()
	{
		if($this->param('categories') >=0)
			wm()->add('deal.category.select',null,array('forPage' => 'recent'));
	}
}