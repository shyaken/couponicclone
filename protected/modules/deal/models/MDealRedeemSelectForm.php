<?php
class MDealRedeemSelectForm extends MDeal
{
	public function rules()
	{
		return array(
			array('requireRedeemLoc', 'safe'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'requireRedeemLoc' => $this->t('Require buyers to choose redeem location'),
		);
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}