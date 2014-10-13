<?php
class WDealCategorySelect extends UMenuWorklet
{
	public $forPage;
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function title()
	{
		return $this->t('Categories');
	}
	
	public function properties()
	{
		$items = array(array(
			'label' => $this->t('All Categories'),
			'url' => url('/deal/category', array('url' => '_')),
			'active' => !wm()->get('deal.category.helper')->userCategory()
		));
		$models = wm()->get('deal.category.helper')->categories();
		foreach($models as $m)
		{
			$items[] = array(
				'label' => $m->name.' (' . $this->count($m->id) . ')',
				'url' => url('/deal/category', array('url' => $m->url)),
				'active' => wm()->get('deal.category.helper')->userCategory() == $m->id,
			);
		}
		$options = $this->space == 'content'
			? array('class' => 'horizontal clearfix')
			: array();
		
		return array('items' => $items, 'htmlOptions' => $options);
	}
	
	public function beforeConfig()
	{
		switch($this->forPage)
		{
			case 'active':
				$this->space = 'sidebar';
				break;
			case 'recent':
				$this->space = 'content';
				break;
		}
		$this->position = 'top';
	}
	
	public function taskCount($category)
	{
		$c = new CDbCriteria;
		$c->with = array();
		$c->params = array();
		
		if($this->param('categories') >= 0)
		{
			$c->with[] = 'categories';
			$c->addCondition('categories.id=:category');
			$c->params[':category'] = $category;
		}
		if($this->param('categories') <= 0)
		{
			$c->with[] = 'locs';
			$c->addCondition('locs.location = 0 OR locs.location = :loc');
			$c->params[':loc'] = wm()->get('deal.helper')->location();
		}
		
		if($this->forPage == 'active')
		{
			$gmtNow = UTimestamp::getNow();
			$c->compare('t.active','1');
			$c->compare('t.status','1');
			$c->compare('start','<='.$gmtNow);
			$c->compare('end','>='.$gmtNow);		
		}
		elseif($this->forPage == 'recent')
		{
			$c->addCondition('t.status IN (1,3)');
			$c->compare('end', '<'.time());
			$c->compare('active',1);
		}
		
		return MDeal::model()->count($c);
	}
}