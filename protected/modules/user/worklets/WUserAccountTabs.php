<?php
class WUserAccountTabs extends UWidgetWorklet
{	
	public $tabs;
	public $select=0;
	
	public function taskConfig()
	{
		$this->tabs = array(
			$this->t('My Coupons') => array('ajax'=>url('/deal/coupons',array('_r' => time()))),
			$this->t('My Account') => array('ajax'=>url('/user/account',array('_r' => time()))),
		);
		parent::taskConfig();
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
		       
		    ),
		));
		
		cs()->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id}').tabs().tabs('select', ".$this->select.");");
	}
	
	public function meta()
	{
		$md = parent::meta();
		$md['title'] = $this->t('My Stuff');
		return $md;
	}
	
	public function afterBuild()
	{
		wm()->add('payment.menu');
	}
}