<?php
class WBaseFollowMenu extends UMenuWorklet
{	
	public $assetsUrl;
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function properties()
	{
		$items = array(
			array('label' => $this->t('Follow Us').':')
		);
		if($this->assetsUrl===null)
			$this->assetsUrl = asma()->publish($this->module->basePath .DS. 'assets' .DS. 'follow');
		foreach($this->param('follow') as $p)
			$items[] = array(
				'label' => CHtml::image($this->assetsUrl.'/'.$p[1],$p[0]),
				'url' => strpos($p[2],'http')===false?eval($p[2]):$p[2],
				'linkOptions' => array('target' => '_blank'),
			);
		
		return array(
			'encodeLabel'=>false,
			'items'=>$items,
			'htmlOptions'=>array(
				'class' => 'horizontal clearfix'
			)
		);
	}
}