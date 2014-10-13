<?php
class WDealCategoryIndex extends UWidgetWorklet
{
	public function taskConfig()
	{
		$category = $_GET['url'] == '_' 
			? null
			: MDealCategory::model()->find('url=?', array($_GET['url']));
		wm()->get('base.helper')->saveToCookie('category',($category?$category->id:null));
			
		$this->show = false;
		app()->request->redirect(app()->request->urlReferrer?app()->request->urlReferrer:url('/'));
	}
}