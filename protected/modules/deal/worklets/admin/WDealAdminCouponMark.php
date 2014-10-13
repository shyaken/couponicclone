<?php
class WDealAdminCouponMark extends UFormWorklet
{
	public $modelClassName = 'UDummyModel';
	public $space = 'inside';

	public function title()
	{
		return $this->t('Mark Coupon as Used');
	}
    
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function taskConfig()
	{
		if(app()->user->checkAccess('administrator'))
		{
			$this->mark();
			return $this->show = false;
		}
		parent::taskConfig();
	}
	
	public function beforeAccess()
	{
		if(!$this->coupon()
			|| (!app()->user->checkAccess('administrator')
				&& !app()->user->checkAccess('company.coupon.access',$this->coupon())))
		{
			$this->accessDenied();
				return false;
		}
	}
	
	public function taskCoupon()
	{
		static $coupon;
		if(!isset($coupon))
			$coupon = isset($_GET['id'])?MDealCoupon::model()->with('order')->findByPk($_GET['id']):null;
		return $coupon;
	}
	
	public function taskSave()
	{
		if($this->model->attribute == $this->coupon()->redemptionCode)
			$this->mark();
		else
			$this->model->addError('attribute',$this->t('Invalid redemption code!'));
	}
	
	public function taskMark()
	{
		$this->coupon()->status = 2;
		$this->coupon()->save();
	}
        
    public function properties() {
        return array(
            'elements' => array(
                'attribute' => array('type'=>'text', 'label'=>$this->t('Redemption Code'))
            ),
            'buttons' => array(
                'submit' => array('type' => 'submit',
                        'label' => $this->t('Submit'))
            ),
            'model' => $this->model
        );
    }
    
    public function ajaxSuccess() {
		$list = wm()->get('deal.admin.coupon');
		wm()->get('base.init')->addToJson(array(
			'content' => array(
				'append' => CHtml::script('$.fn.yiiGridView.update("' .$list->getDOMId(). '-grid");
					$.uniprogy.dialogClose();'),
			)
		));
    }
}