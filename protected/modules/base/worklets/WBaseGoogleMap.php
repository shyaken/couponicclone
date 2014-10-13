<?php
class WBaseGoogleMap extends UWidgetWorklet
{
	public $country;
	public $address;
	public $printView=false;
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function taskRenderOutput()
	{
		echo CHtml::tag('div',array('id' => 'googleMap'),'');
		$options = array();
		if($this->printView)
			$options = array('navigationControl' => false,
				'scaleControl' => false, 'mapTypeControl' => false,
				'zoom' => 15);
				
		if($this->country == 'IL')
			$options['osm'] = true;
			
		$http = app()->request->isSecureConnection ? 'https' : 'http';
		
		cs()->registerScriptFile($http.'://maps.google.com/maps/api/js?sensor=false&language='.app()->language);
		cs()->registerScript(__CLASS__,'jQuery("#googleMap").uGoogleMap('
			. CJavaScript::jsonEncode($this->address).','.CJavaScript::jsonEncode($options).')');
	}
}