<?php
class WDealCategoryHelper extends USystemWorklet
{
	public function taskCategories()
	{
		$c = new CDbCriteria(array(
			'condition' => 'enabled=:enabled',
			'params' => array(':enabled' => 1),
			'with' => array('i18n' =>
				array('condition' => 'language=:lang', 'params' => array(':lang' => app()->language))),
			'order' => 'i18n.value ASC',
		));
		return MDealCategory::model()->findAll($c);
	}
	
	public function taskUserCategory()
	{
		return wm()->get('base.helper')->getFromCookie('category');
	}
	
	public function taskCategory($id)
	{
		return MDealCategory::model()->findByPk($id);
	}
	
	public function taskTranslations($model,$attribute,$purify=false)
	{
		// we need to save all fields translations		
		foreach($model->$attribute as $lang=>$text)
			if($text)
			{
				$m = MDealCategoryI18N::model()->find('language=? AND categoryId=? AND name=?',array(
					$lang,$model->id,$attribute
				));
				if(!$m)
				{
					$m = new MDealCategoryI18N;
					$m->categoryId = $model->id;
					$m->language = $lang;
					$m->name = $attribute;
				}
				$m->value = $text;
				
				$m->save();
			}
	}
}