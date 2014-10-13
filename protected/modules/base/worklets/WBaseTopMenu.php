<?php
class WBaseTopMenu extends UMenuWorklet
{	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function properties()
	{
		
		return array(
			'items'=>array(
				array('label'=>$this->t('Visit More Cities'),
					'url'=>'#', 'linkOptions'=>array('class' => 'topMenuLink', 'name' => 'wlt-LocationSelect'),
					'visible' => m('deal')->param('categories') <= 0),
				array('label'=>$this->t('Get Daily Alerts'),
					'url'=>'#', 'linkOptions'=>array('class' => 'topMenuLink', 'name' => 'wlt-DealSubscribe')),
			),
			'htmlOptions'=>array(
				'class' => 'horizontal clearfix'
			)
		);
	}
}