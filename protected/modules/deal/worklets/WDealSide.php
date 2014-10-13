<?php
class WDealSide extends UListWorklet
{
	public $space = 'sidebar';
	public $type = 'list';
	public $deals;
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function form()
	{
		return false;
	}
	
	public function taskConfig()
	{
		if(!$this->deals)
			return $this->show = false;
		parent::taskConfig();
		$this->options = array(
			'template' => '{items}',
			'enablePagination' => false,
		);
	}
	
	public function title()
	{
		return $this->t('Side Deals');
	}
	
	public function itemView()
	{
		return 'deal';
	}
	
	public function dataProvider()
	{
		return new CArrayDataProvider($this->deals, array(
			'pagination' => false
		));
	}
}