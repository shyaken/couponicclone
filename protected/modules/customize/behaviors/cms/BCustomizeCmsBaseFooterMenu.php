<?php
class BCustomizeCmsBaseFooterMenu extends UWorkletBehavior
{
	public function afterConfig()
	{
		$models = MCmsPage::model()->findAll('footerMenu=1');
		foreach($models as $m)
			$this->owner->insert('bottom', array(
				array('label'=>$m->title, 'url'=>url('/base/page', array('view' => $m->url)))
			));
	}
}