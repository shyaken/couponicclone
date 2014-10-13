<?php
class WDealPriceUpdate extends UFormWorklet
{	
	public $modelClassName = 'MDealPriceForm';
	public $primaryKey='id';
	public $space = 'inside';
	
	public function title()
	{
		return $this->isNewRecord
			? $this->t('Add Price Option')
			: $this->t('Edit Price Option');
	}
	
	public function taskDeal()
	{
		if(isset($_GET['id']))
			return $this->model()?$this->model()->deal:null;
		if(isset($_GET['dealId']))
			return MDeal::model()->findByPk($_GET['dealId']);
		return null;
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company.editor')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function beforeAccess()
	{
		if($this->deal() && wm()->get('deal.edit.helper')->authorize($this->deal()->id))
			return true;
		$this->accessDenied();
		return false;
	}
	
	public function properties()
	{
		return array(
			'elements' => array(
				'name' => array(
					'type' => 'UI18NField', 'attributes' => array(
						'type' => 'text',
						'languages' => wm()->get('base.language')->languages(),
					), 'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
				),
				'price' => array('type' => 'text', 'class' => 'short',
					'hint' => m('payment')->param('cSymbol'), 'layout' => "{label}\n<span class='hintInlineBefore'>{hint}\n{input}</span>"),
				'priceInfo' => '<div class="row"><div class="hint">'.$this->t('This is how much users will need to pay to buy this coupon.').'</div></div>',
				'value' => array('type' => 'text', 'class' => 'short',
					'hint' => m('payment')->param('cSymbol'), 'layout' => "{label}\n<span class='hintInlineBefore'>{hint}\n{input}</span>"),
				'valueInfo' => '<div class="row"><div class="hint">'.$this->t('This is how much product/service originally costs.').'</div></div>',
				'couponPrice' => array('type' => 'text', 'class' => 'short',
					'hint' => m('payment')->param('cSymbol'), 'layout' => "{label}\n<span class='hintInlineBefore'>{hint}\n{input}</span>"),
				'couponPriceInfo' => '<div class="row"><div class="hint">'.$this->t('If you want to sell coupons that give a discount rather then service or product itself - use the field above to specify special price of the product/service.').'</div></div>',
				'mainLine' => '<hr />',
				'main' => array('type' => 'checkbox', 'label' => $this->t('Set this option as main')),
			),
			'buttons' => array(
				'cancel' => app()->request->isAjaxRequest
					? array('type' => 'UJsButton', 'attributes' => array(
						'label' => $this->t('Close'),
						'callback' => '$(this).closest(".worklet-pushed-content").remove()'))
					: null,
				'submit' => array('type' => 'submit',
					'label' => $this->isNewRecord?$this->t('Add'):$this->t('Update')),
			),
			'model' => $this->model
		);
	}
	
	public function afterConfig()
	{
		if(isset($_GET['ajax']))
			$this->layout = false;
		if($this->model->main)
			unset($this->properties['elements']['main']);
		wm()->get('base.init')->setState('admin',true);
	}
	
	public function beforeSave()
	{
		$this->model->dealId = $this->deal()->id;
	}
	
	public function afterSave()
	{
		wm()->get('base.helper')->translations('DealPrice', $this->model, 'name');
		if($this->model->main)
		{
			MDealPrice::model()->updateAll(array('main'=>'0'), 'dealId=? AND id<>?', array($this->model->dealId, $this->model->id));
			wm()->get('deal.edit.helper')->dealName($this->model->dealId, $this->model->name);
		}
	}
	
	public function ajaxSuccess()
	{
		$listWorklet = wm()->get('deal.price.list');
		$message = $this->isNewRecord
			? $this->t('Price option has been successfully added.')
			: $this->t('Price option has been successfully updated.');
		$json = array(
			'info' => array(
				'replace' => $message,
				'fade' => 'target',
				'focus' => true,
			),
			'content' => array(
				'append' => CHtml::script('$.fn.yiiGridView.update("' .$listWorklet->getDOMId(). '-grid")')
			),
		);
		if($this->isNewRecord)
			$json['load'] = url('/deal/price/update', array('ajax'=>1,'id' => $this->model->id));
			
		wm()->get('base.init')->addToJson($json);
	}
}