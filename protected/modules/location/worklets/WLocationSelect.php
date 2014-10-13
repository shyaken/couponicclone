<?php
class WLocationSelect extends UWidgetWorklet
{
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function taskConfig()
	{
		if(m('deal')->param('categories') > 0)
			$this->show = false;
		parent::taskConfig();
	}
	
	public function taskRenderOutput()
	{
		$current = wm()->get('deal.helper')->location();
		$currentModel = wm()->get('location.helper')->locationToData($current,true);
		
		$countries = array();
		$locations = array();
		
		$models = MLocationPreset::model()->with('loc')->findAll(array(
			'order' => 'state, city',
		));
		
		foreach($models as $m)
		{
			if($m->loc->state && $this->param('selector') == 'complex')
				$locations[$m->loc->country][$m->loc->state][] = $m;
			else
				$locations[$m->loc->country][] = $m;
		}
		
		$viewFile = 'select';
		if($this->param('selector') == 'complex' && !wm()->get('base.helper')->isMobile())
		{
			$viewFile = 'complex';
			$char = $currentModel->state
				? $currentModel->state
				: $currentModel->cityName;
				
			cs()->registerScript(__CLASS__,'jQuery("#'.$this->getDOMId().'").uLocSelect({
				"country": "'.$currentModel->country.'",
				"c": "'.app()->locale->textFormatter->utf8substr($char,0,1).'"
			});');
		}
		
		$this->render($viewFile,array('locations'=>$locations,'current'=>$current,
			'showCountry'=>!wm()->get('location.helper')->defaultCountry()));
	}
}