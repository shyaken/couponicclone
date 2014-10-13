<?php
class MDealAllFilterForm extends UFormModel
{
	public $location;
	public $category;
	
	public function rules()
	{
		return array(
			array('location, category', 'safe'),
		);
	}
}