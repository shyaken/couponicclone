<?php
class BCustomizeCmsPage extends UWorkletBehavior
{
	public $model;
	
	public function afterConfig()
	{
		if(isset($_GET['view']) && ($model = MCmsPage::model()->find('url=?',array($_GET['view']))) !== null)
		{
			$this->model = $model;
			$this->owner->title = $model->title;
		}
	}
	
	public function beforeRenderOutput()
	{
		if($this->model)
		{
			wm()->get('customize.cms.helper')->readContent('page.'.$this->model->id);
			return false;
		}
	}
}