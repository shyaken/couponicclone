<?php
class MLocationBackgroundImageForm extends MDeal {
	
    public function rules()
    {
		return array(
			array('background', 'file',
				'types'=>m('deal')->params['fileTypes']),
		);
    }

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}