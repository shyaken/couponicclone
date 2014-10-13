<?php
class WDealPriceDialog extends UListWorklet
{
	public $type = 'list';
	public $modelClassName = 'MDealPrice';
	public $space = 'inside';
	public $dealId;
	
	public function title()
	{
		return $this->t('Choose Your Deal');
	}
	
	public function form()
	{
		return false;
	}
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function beforeConfig()
	{
		if(!isset($_GET['id']) && !$this->dealId)
			return $this->show = false;
	}
	
	public function afterConfig()
	{
		$this->model->dealId = isset($_GET['id'])?$_GET['id']:$this->dealId;
		$this->options = array('template' => "{items}\n{pager}");
	}
	
	public function itemView()
	{
		return 'price';
	}
	
	public function taskLink($data)
	{
		return url('/deal/purchase', array('id' => $data->id));
	}
}