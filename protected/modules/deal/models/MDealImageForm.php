<?php
class MDealImageForm extends UFormModel
{
	public $image;
	
	public static function module()
	{
		return 'deal';
	}
	
	public function rules()
	{
		return array(
			array('image', 'file',
				'types'=>m('deal')->params['fileTypes'],
				'maxSize'=>m('deal')->params['fileSizeLimit'] * 1024 * 1024,
			),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'image' => $this->t('Select Image(s) to Upload'),
		);
	}
}