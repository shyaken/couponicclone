<?php
class WLocationHelper extends USystemWorklet
{
	/**
	 * @return array list of all supported locations (countries, states, cities)
	 */
	public function taskLocations($ignoreFixed=false, $preset=null)
	{
		// trying to return cached value
		$cacheKey = 'location.'.$ignoreFixed;
		if(!$preset && ($locations = $this->cacheGet($cacheKey))!==false)
			return $locations;
		
		$countries = array();
		$states = array();
		$cities = array();
		$fixed = array('countries' => array());
		if(!$ignoreFixed)
		{
			$models = $preset ? $preset : MLocationPreset::model()->with('loc')->findAll();
			foreach($models as $preset)
			{
				$m = $preset->loc;
				$fixed['countries'][$m->country] = true;
				$fixed['states'][$m->country][$m->state] = true;
				$fixed['cities'][$m->country][$m->state][] = $m->city;
			}
		}
		
		// loading all countries
		$countries = $this->loadCountries();
		// if there is a default country set - returning only that country
		if(!$ignoreFixed)
		{
			if($this->param('defaultCountry') && $this->param('defaultCountry')!=='*')
				$countries = array_intersect_key($countries,array($this->param('defaultCountry')=>true));
			elseif(isset($fixed['countries']))
				$countries = array_intersect_key($countries,$fixed['countries']);
		}
		
		// building states list
		foreach($countries as $code=>$name)
		{
			// loading states for a country
			// and setting cities to "true" for all states
			$st = $this->loadStates($code);
			if($st)
			{
				$states[$code] = $st;
				if(!$ignoreFixed && isset($fixed['states'][$code]))
					$states[$code] = array_intersect_key($states[$code],$fixed['states'][$code]);
				foreach($states[$code] as $c=>$s)
					$cities[$code.'_'.$c] = !$ignoreFixed && isset($fixed['cities'][$code][$c])
						? $fixed['cities'][$code][$c] : true;
			}
			else
				$cities[$code.'_0'] = !$ignoreFixed && isset($fixed['cities'][$code][0])
					? $fixed['cities'][$code][0] : true;
		}
		
		// building array and saving data into the cache
		$locations = array($countries,$states,$cities);
		if(!$preset)
			$this->cacheSet($cacheKey,$locations);
		return $locations;
	}
	
	/**
	 * @return array locations as a one-dimension array
	 */
	public function taskLocationsAsList()
	{
		$list = array();
		
		$models = MLocationPreset::model()->with('loc')->findAll(array(
			'order' => 'loc.country,loc.state,loc.city'
		));
		
		$showCountry = !wm()->get('location.helper')->defaultCountry();
		
		foreach($models as $m)
		{
			$country = $showCountry?$this->country($m->loc->country):null;
			$state = $m->loc->state?$this->state($m->loc->country,$m->loc->state):null;
			
			if($country && $state)
				$list[$country][$state][$m->loc->id] = $m->loc->cityName;
			elseif($country)
				$list[$country][$m->loc->id] = $m->loc->cityName;
			elseif($state)
				$list[$state][$m->loc->id] = $m->loc->cityName;
			else
				$list[$m->loc->id] = $m->loc->cityName;
		}
		
		return $list;
	}
	
	/**
	 * @param MLocation location model
	 * @return string location as string
	 */
	public function taskLocationAsText($model,$address=false,$zipCode=false,$newLine='<br />')
	{
		$addressPart = '';
		if($address)
			$addressPart.= $address.$newLine;
			
		$cityPart = $model->cityName;
		if($model->state != '0')
			$cityPart.= ', '.$this->state($model->country,$model->state);
		if($zipCode)
			$cityPart.= ' '.$zipCode;
		$cityPart.= $newLine . $this->country($model->country);
		return $addressPart.$cityPart;
	}
	
	/**
	 * @param array location data
	 * @param boolean whether to auto-add new location if it doesn't exist
	 * @return integer location ID
	 */
	public function taskDataToLocation($data,$addMissing=true)
	{
		static $locations = array();
		
		if(!is_array($data))
			return;
		
		$key = serialize($data);
		if(!isset($locations[$key]))		
		{
			if(!$this->loadStates($data['country']))
				$data['state'] = '0';
			$location = MLocation::model()->findByAttributes($data);
			if(!$location && $addMissing)
			{
				$location = new MLocation;
				$location->attributes = $data;
				$location->save();
				
				$cityASCII = $this->cityToASCII($location->city);
				if(!trim($cityASCII))
					$cityASCII = $location->id;
				$location->cityASCII = $cityASCII;
				$location->save();
			}
			$locations[$key] = $location?$location->id:false;
		}
		return $locations[$key];
	}
	
	/**
	 * @param integer location ID
	 * @return array location data
	 */
	public function taskLocationToData($location, $asModel=false)
	{
		static $locations = array();
		
		if(!$location)
			return;
		
		if(!isset($locations[$location.'-'.$asModel]))
		{		
			$model = MLocation::model()->findByPk($location);
			$locations[$location] = $model ? $model : null;
		}
		return $asModel
			? $locations[$location]
			: ($locations[$location] !== null ? $locations[$location]->attributes : array());
	}
	
	/**
	 * @return integer default country or false if it is not set
	 */
	public function taskDefaultCountry()
	{
		if($this->param('defaultCountry')!=='*')
			return $this->param('defaultCountry');
		return false;
	}
	
	/**
	 * It will first try to extract location from IP, but if it will fail it will return
	 * default location which was set in admin.
	 * @return integer default location or false if it is not set
	 */
	public function taskDefaultLocation()
	{
		$location = $this->locationFromIp();
		if(!$location && $this->param('location')!=='*')
			$location = $this->param('location');
		return $location;
	}
	
	/**
	 * Connects to IPInfoDB.com API to extract location from IP.
	 * @return integer location ID or false if location couldn't be extracted
	 */
	public function taskLocationFromIp()
	{
		$ip = app()->request->getUserHostAddress();
		$api = 'http://api.ipinfodb.com/v2/ip_query.php?key='.$this->param('ipinfodbApiKey')
			. '&ip='.$ip.'&output=json&timezone=false';
			
		$curl = Yii::createComponent(array('class'=>'uniprogy.extensions.curl.CCurl'));
		$curl->addSession($api, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_TIMEOUT => 10,
			CURLOPT_FAILONERROR => true,
		));
		$result = $curl->exec();
		$curl->clear();
		
		if($result)
		{
			$json = CJSON::decode($result);
			if(isset($json['Status']) && $json['Status'] == 'OK' && ($json['City'] || $json['Latitude']))
			{
				$location = null;
				if($json['City'])
				{
					$data = array(
						'country' => $json['CountryCode'],
						'state' => $this->ipStateToNormal($json['CountryCode'],$json['RegionName']),
						'cityASCII' => $this->cityToASCII($json['City']),
					);
					$location = $this->dataToLocation($data,false);
					if(!$this->validLocation($location))
						$location = null;
				}
				
				if(!$location && isset($json['Latitude']) && isset($json['Longitude']))
					$location = $this->closestCity($json['Latitude'], $json['Longitude']);
				
				if($location && $this->validLocation($location))
					return $location;
			}
		}
		return false;
	}
	
	public function taskClosestCity($lat, $lon)
	{
		if($lat && $lon)
		{
			$sql = "SELECT ((ACOS(SIN($lat * PI() / 180) * SIN(lat * PI() / 180) + COS($lat * PI() / 180) * COS(lat * PI() / 180) * COS(($lon - lon) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)
				AS `distance`, `location` FROM `{{LocationPreset}}` WHERE lon IS NOT NULL AND lon <> 0 AND lat IS NOT NULL AND lat <> 0 ORDER BY `distance` ASC LIMIT 0,1";
			$row = app()->db->createCommand($sql)->queryRow();
			if($row)
				return $row['location'];
		}
	}
	
	public function taskIPStateToNormal($country,$state)
	{
		$states = $this->loadStates($country);
		if($states)
		{
			if(isset($states[$state]))
				return $state;
			if(($key = array_search($state,$states))!==false)
				return $key;
		}
		return '0';
	}
	
	/**
	 * @param string country ID
	 * @return string country name
	 */
	public function taskCountry($id)
	{
		$countries = $this->loadCountries();
		return isset($countries[$id])?$countries[$id]['name']:null;
	}
	
	/**
	 * @param string country ID
	 * @param string state ID
	 * @return string state name
	 */
	public function taskState($country,$id)
	{
		$states = $this->loadStates($country);
		return isset($states[$id])?$states[$id]:null;
	}
	
	/**
	 * @param integer location ID
	 * @return array country/city params that can be used by URL creation function
	 */
	public function taskUrlParams($location)
	{
		$url = $location instanceOf MLocation
			? $location->preset->url
			: $location->url;
		
		return array($url => '');
	}
	
	/**
	 * @param integer location ID
	 * @return boolean whether location exists
	 */
	public function taskValidLocation($location)
	{
		return MLocationPreset::model()->exists('location=?',array($location));
	}
	
	/**
	 * @param string city name
	 * @return string city name transformed into ASCII - special characters replaced with latin ones
	 */
	public function taskCityToASCII($city)
	{
		return @preg_replace('/[^A-Za-z| ]/','',
			iconv('ASCII','UTF-8',
				iconv('UTF-8','ASCII//TRANSLIT',$city))
		);
	}
	
	/**
	 * Adds fixed location to the module configuration.
	 * @param integer location ID
	 * @deprecated since 1.4.0
	 */
	public function taskAddFixedLocation($location)
	{
		$fixed = $this->param('fixed');
		$fixed[] = $location;
		$fixed = array_unique($fixed);
		$file = Yii::getPathOfAlias('application.config.public.modules').'.php';
		$config['modules']['location']['params']['fixed'] = null;
		UHelper::saveConfig($file,$config);
		$config['modules']['location']['params']['fixed'] = $fixed;
		UHelper::saveConfig($file,$config);
	}
	
	/**
	 * Loads countries list from a file.
	 * @return array countries list
	 */
	public function loadCountries()
	{
		$countriesFile = Yii::getPathOfAlias('application.data.countries') . '.php';
		return include(app()->findLocalizedFile($countriesFile));
	}
	
	/**
	 * Loads states list from a file.
	 * @param string country ID
	 * @return array states list
	 */
	public function loadStates($country)
	{
		$statesFile = Yii::getPathOfAlias('application.data.states.'.$country).'.php';
		if(file_exists($statesFile))
			return include(app()->findLocalizedFile($statesFile));
		else
			return false;
	}
	
	/**
	 * Brings URL to the lower-case only-latin-characters format.
	 * @param string un-normalized url
	 * @return string normalized url
	 */
	public function normalizeUrl($url)
	{
		return strtolower(preg_replace('/[^A-Za-z|0-9]/','-',$url));
	}
	
	public function taskUrlToLocation($url)
	{
		$m = MLocationPreset::model()->find('url=?', array($url));
		return $m ? $m->location : null;
	}
}