<?php
class CategoryController extends UController
{
	public function init()
	{
		wm()->get('base.init')->setState('admin',true);
		return parent::init();
	}
}