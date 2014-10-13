<?php
class WCustomizeCmsBlock extends UWidgetWorklet
{
	public $model;
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function taskConfig()
	{
		if(!$this->model)
			return $this->show = false;
			
		$this->space = $this->model->space;
		$this->position = $this->model->positionData;
		
		return parent::taskConfig();
	}
	
	public function title()
	{
		return $this->model->title;
	}
	
	public function taskRenderOutput()
	{
		wm()->get('customize.cms.helper')->readContent('block.'.$this->model->id);
	}
	
	public function getDOMId()
	{
		return parent::getDOMId().'-'.$this->model->id;
	}
}