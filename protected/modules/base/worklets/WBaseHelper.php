<?php
class WBaseHelper extends USystemWorklet
{
	private $_keyPrefix;
	
	/**
	 * Saves data into an application cookie.
	 * @param string item name
	 * @param mixed item value
	 */
	public function taskSaveToCookie($name,$value)
	{
		if($name == 'location' && !$value)
			return;
		
		$cookie=app()->getRequest()->getCookies()->itemAt($this->getStateKeyPrefix());
		if(!$cookie)
		{
			$cookie = new CHttpCookie($this->getStateKeyPrefix(),'');
			$data = array();
		}
		else
			$data = unserialize($cookie->value);
		$cookie->expire = time()+31536000;
		$data[$name] = $value;
		$cookie->value = serialize($data);
		app()->getRequest()->getCookies()->add($cookie->name,$cookie);
	}
	
	/**
	 * Retrieves data item from the application cookie.
	 * @param string item name
	 * @return mixed item value
	 */
	public function taskGetFromCookie($name)
	{
		$cookie=app()->getRequest()->getCookies()->itemAt($this->getStateKeyPrefix());
		if($cookie)
		{
			$data = unserialize($cookie->value);
			return isset($data[$name])?$data[$name]:null;
		}
		return null;
	}
	
	public function taskIsMobile($ignoreSetting=false)
	{
		if(!$ignoreSetting)
		{
			$setting = $this->getFromCookie('ignoreMobile');
			if($setting)
				return false;
		}
		return app()->request->isMobile;
	}
	
	public function getStateKeyPrefix()
	{
		if($this->_keyPrefix!==null)
			return $this->_keyPrefix;
		else
			return $this->_keyPrefix=md5('UniProgy.'.get_class($this).'.'.Yii::app()->getId());
	}
	
	public function setStateKeyPrefix($value)
	{
		$this->_keyPrefix=$value;
	}
	
	public function taskTranslations($modelName,$model,$attribute,$purify=false)
	{
		// we need to save all fields translations
		$purifier = new CHtmlPurifier;
		$purifier->options = array(
			'Attr.AllowedFrameTargets' => array('_blank','_self','_parent','_top'),
			'HTML.SafeObject' => true,
			'HTML.SafeEmbed' => true,
			'Output.FlashCompat' => true,
		);
		
		$id = $model instanceof UActiveRecord
			? $model->primaryKey
			: $model->id;
		
		MI18N::model()->deleteAll('model=? AND relatedId=? AND name=?',array(
			$modelName,$id,$attribute
		));
		
		foreach($model->$attribute as $lang=>$text)
			if($text)
			{
				if($purify)
					$text = $purifier->purify($text);

				$m = MI18N::model()->find('model=? AND language=? AND relatedId=? AND name=?',array(
					$modelName,$lang,$id,$attribute
				));
				if(!$m)
				{
					$m = new MI18N;
					$m->relatedId = $id;
					$m->language = $lang;
					$m->name = $attribute;
					$m->model = $modelName;
				}
				$m->value = $text;
				$m->save();
			}
	}
	
	public function taskDefaultConfig($name, $trace=null)
	{
		$cfg = require(app()->basePath.DS.'config'.DS.'config.php');
		if(!$trace)
			return isset($cfg[$name])? $cfg[$name] : null;
		eval('$value = isset('.$cfg.$trace.'['.$name.'])?'.$cfg.$trace.'['.$name.']:null;');
		return $value;
	}
}