<?php
class WApiSettings extends UApiWorklet
{
	public function afterConfig()
	{
		$location = app()->request->getParam('location',null); 
		$language = app()->request->getParam('language',null); 
		
		if(is_null($location))
			$location =wm()->get('deal.helper')->location();
		
		$langs = wm()->get('base.language')->languages();
		
		if (!is_array($langs))
			$this->errorMessage = $this->t('Error getting list of languages.');
			
		// let's check if selected language exists
		if(!isset($langs[$language]))
		{
			if(strpos($language,'_') !== false)
				$language = substr($language,0,2);
			$language = $this->findLanguage($langs,$language);
		}

		if(!isset($langs[$language]))
			$language = app()->language;
		
		app()->language = $language;

		$data['settings'][]['language'] = $language;
		$data['settings'][]['location'] = $location;
		$data['settings'][]['moneyFormat'] = app()->locale->getCurrencyFormat();
		$data['settings'][]['currency'][]['code'] = wm()->get('payment.helper')->param('cCode');
		$data['settings'][count($data['settings'])-1]['currency'][]['symbol'] = wm()->get('payment.helper')->param('cSymbol');
		
		foreach ($langs as $key => $value) {
			$data['languages'][]['lang']['id'] = $key;
			$data['languages'][count($data['languages'])-1]['lang']['name'] = $value;
		}
		
		$categories = MDealCategory::model()->findAll('enabled=1');
		
		foreach ($categories as $key=>$value) {
			$data['categories'][$key]['cat']['id'] = $value->id;
			$data['categories'][$key]['cat']['name'] = $value->name;	
		}
		
		$models = MLocationPreset::model()->with('loc')->findAll(array(
			'order' => 'city',
		));
        
		
        $cityAr = array();
		
		foreach ($models as $key=>$value) 
			$cityAr[]['loc'] = array('id' => $value->loc->id, 'name' => $value->loc->cityName);			
		
		$data['locations'] = $cityAr;
		
		$messAr = array();
		if(file_exists(app()->basePath.DS.'messages'.DS.$language.DS.'api.php')){
			$messages = require_once(app()->basePath.DS.'messages'.DS.$language.DS.'api.php');
			if($messages)
				foreach ($messages as $key => $value) 
					$messAr[]['mes'] = array('original' => $key,'translation' => $value);	
				
 	           $data['messages'] = $messAr;
		}
		$this->data = $data;
	}
	
	public function findLanguage($langs, $language)
	{
		foreach($langs as $k=>$v)
			if(substr($k,0,2) == $language)
				return $k;
		return $language;
	}
	
}
