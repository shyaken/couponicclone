<?php
class MBaseFollowForm extends UFormModel
{
	public $name;
	public $image;
	public $url;
	
	public static function module()
	{
		return 'base';
	}
	
	public function rules()
	{
		return array(
			array('name,image,url','safe')
		);
	}
}