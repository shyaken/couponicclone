<?php
class BLocationForm extends UWorkletBehavior
{
	public static $cacheKeyPrefix = 'UniProgy.BLocationForm.';
	public $insert;
	public $model;
	public $elementsKey;
	public $required=true;
	public $ignoreFixed=false;
	public $preset;
	public $select;
	
	public function properties()
	{
		$data = app()->cache->get(self::$cacheKeyPrefix . 'data');
		if($data === false)
		{
			$elements = array();
			list($countries,$states,$cities) = wm()->get('location.helper')->locations($this->ignoreFixed, $this->preset);
			if(count($countries)>1)
				$elements['country'] = array(
					'type' => 'dropdownlist',
					'items' => CHtml::listData($countries,'code','name'),
					'required' => $this->required,
				);
			else
				$elements['country'] = array('type' => 'hidden', 'value' => array_pop(array_keys($countries)));
				
			$elements['state'] = array('type' => 'dropdownlist', 'required' => $this->required);			
			$elements['city'] = array('type' => 'text', 'required' => $this->required);
			
			$data = array('elements' => $elements, 
				'json' => CJavaScript::jsonEncode(array('states' => $states,'cities' => $cities)));
			app()->cache->set(self::$cacheKeyPrefix . 'data', $data, app()->params['maxCacheDuration']);
		}
		
		$selectedLocation = $this->select
			? $this->select
			: wm()->get('location.helper')->locationToData($this->getOwnerModel()->location);
		
		if(isset($selectedLocation['country']) && !array_key_exists($selectedLocation['country'],$countries))
			$selectedLocation = array();
			
		if(!isset($selectedLocation['country']))
		{
			if($this->getModule()->param('defaultCountry') && $this->getModule()->param('defaultCountry')!='*')
				$selectedLocation['country'] = $this->getModule()->param('defaultCountry');
		}
		
		$locationModel = new MLocationForm;
		$locationModel->attributes = $selectedLocation;
		
		$selects = CJavaScript::jsonEncode($selectedLocation);
		
		$jsFile = asma()->publish($this->getModule()->basePath .DS. 'js' .DS. 'jquery.uniprogy.loc.js');
		cs()->registerScriptFile($jsFile,CClientScript::POS_HEAD);
		$script = '$("#' .$this->getOwner()->getFormId(). '").uLoc('. $data['json'] . ', ' . $selects . ');';
		cs()->registerScript('UniProgy.location.form.'.$this->getOwner()->getFormId().time(),$script);
		return array(
			'location' => array('type' => 'UForm',
				'elements' => $data['elements'],
				'model' => $locationModel
			)
		);
	}
	
	public function getOwnerModel()
	{
		return $this->model?$this->model:$this->getOwner()->model;
	}
	
	public function afterConfig()
	{
		if(!$this->insert)
			$this->insert = array('before'=>'address');
			
		if(key($this->insert) == 'before')
			$this->getOwner()->insertBefore(current($this->insert),$this->properties(),$this->elementsKey);
		else
			$this->getOwner()->insertAfter(current($this->insert),$this->properties(),$this->elementsKey);
	}
	
	public function beforeSave()
	{
		$model = $this->getOwnerModel();
		$form = $this->getOwner()->form;
		if(is_array($this->elementsKey))
		{
			reset($this->elementsKey);
			while(($k = current($this->elementsKey))!==false)
			{
				if(isset($form[$k]))
				{
					$form = $form[$k];
					next($this->elementsKey);
				}
				else
					break;
			}
		}
		
		$locModel = isset($form['location'])?$form['location']->model:null;		
		if(!$locModel)
			return;
		
		$location = wm()->get('location.helper')->dataToLocation($locModel->attributes);
		if(!$location && $this->required)
			$locModel->addError('country', $this->getModule()->t('Invalid location selected. Please verify.'));
		else
			$model->location = $location;
	}
}