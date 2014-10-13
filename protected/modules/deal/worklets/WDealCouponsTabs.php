<?php
class WDealCouponsTabs extends UWidgetWorklet
{	
	public $tabs;
	public $select=0;
	public $space = 'inside';
	
	public function taskConfig()
	{
		$this->tabs = array(
			$this->t('Available') => array('ajax'=>array('/deal/coupons/type/available')),
			$this->t('Used') => array('ajax'=>array('/deal/coupons/type/used')),
			$this->t('Expired') => array('ajax'=>array('/deal/coupons/type/expired')),
			$this->t('All') => array('ajax'=>array('/deal/coupons/type/all')),
		);
	}
	
	public function taskRenderOutput()
	{
		$id = 'uTabs_'.$this->getDOMId().'_'.CHtml::$count++;
		
		$this->widget('zii.widgets.jui.CJuiTabs', array(
			'id' => $id,
			'headerTemplate' => '<li><a href="{url}">{title}</a></li>',
		    'tabs' => $this->tabs,
		    // additional javascript options for the tabs plugin
		    'options'=>array(
		    	'select' => 'js:function(event,ui){$("#wlt-DealCoupons").remove();}',
		    ),
		));
		
		cs()->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id}').tabs().tabs('select', ".$this->select.");");
	}
}