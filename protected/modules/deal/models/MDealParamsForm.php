<?php
class MDealParamsForm extends UFormModel
{
	public $fileTypes;
	public $fileSizeLimit;
	public $fileResize;	
	public $commission;
	public $rssChannelDescription;
	public $delimiter;
	public $requireSubscribe;
	public $categories;
	public $upcoming;
	public $homepage;
	public $subscriptionDelete;
	public $payoutMode;
	
	public static function module()
	{
		return 'deal';
	}
	
	public function rules()
	{
		return array(
			array(implode(',',array_keys(get_object_vars($this))),'safe')
		);
	}
}