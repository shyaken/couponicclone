<?php
class WUserProfileHelper extends USystemWorklet
{
	public function taskTypes()
	{
		return array(
			'text' => $this->t('Text Field'),
			'dropdownlist' => $this->t('Dropdown'),
			'radiolist' => $this->t('Radio Buttons'),
			'textarea' => $this->t('Text Area'),
		);
	}
	
	public function taskType($type)
	{
		$types = $this->types();
		return isset($types[$type])
			? $types[$type]
			: $this->t('Unknown');
	}
	
	public function taskForm($user=null)
	{
		$model = new UDynamicModel;
		
		$params = MUserProfileSetting::model()->findAll();
		
		$fields = array('profileSeparator' => '<hr />');
		$rules  = array('required' => array(), 'safe' => array());
		$elements = array();
		$labels = array();
		
		foreach ($params as $param)
		{
			$field = 'field_'.$param->id;
			$fields[] = $field;			
			$labels[$field] = $param->label;
			$elements[$field] = $this->field($param);
			
			if($param->rules == '1')
				$rules['required'][] = $field;
			else
				$rules['safe'][] = $field;
		}
		
		$model->import($fields, array(
			array(implode(',', $rules['required']), 'required'),
			array(implode(',', $rules['safe']), 'safe'),
		), $labels);
		
		if(!$user && !app()->user->isGuest)
			$user = app()->user->model();
		
		if($user && $user->id)
		{
			$values = MUserProfile::model()->findAll('userId=?', array($user->id));
			foreach($values as $v)
			{
				$key = 'field_'.$v->settingId;
				if(in_array($key, $fields))
					$model->$key = $v->value;
			}
		}
		
		return array(
			'type' => 'UForm',
			'elements' => $elements,
			'model' => $model
		);
	}
	
	public function taskField($setting)
	{
		$field = array();
		switch($setting->type)
		{
			case 'text':
				$field['type'] = 'text';
				break;
			case 'dropdownlist':
				$field['type'] = 'dropdownlist';
				$field['items'] = explode("\n", $setting->items);
				break;
			case 'radiolist':
				$field['type'] = 'radiolist';
				$field['items'] = explode("\n", $setting->items);
				$field['layout'] = "{label}\n<fieldset>{input}</fieldset>\n{hint}";
				break;
			case 'textarea':
				$field['type'] = 'textarea';
				break;
		}
		
		$field['label'] = $this->t($setting->label);
		return $field;
	}
}