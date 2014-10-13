<?php
class WPaymentCart extends UListWorklet
{
	public $addCheckBoxColumn=false;
	public $addButtonColumn=false;
	public $addMassButton=false;
	
	public function accessRules()
	{
		return array(
			array('allow', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('Your Purchase');
	}
	
	public function form()
	{
		return 'payment.checkout';
	}
	
	public function beforeAccess()
	{
		if(!app()->user->isGuest || ((m('user')->param('emailVerification')=='0' || m('user')->param('unverifiedAccess')=='0')
			&& (m('user')->param('approveNewAccounts')=='0' || m('user')->param('unapprovedAccess')=='0')))
				return;
		$this->accessDenied();
		return false;
	}
	
	public function taskConfig()
	{
		$this->options = array(
			'selectableRows' => 0,
			'rowCssClassExpression' => '$row==count($this->dataProvider->data)-1
				? "cartTotal" : ($data["module"] == "payment"?"credits ":"").$this->rowCssClass[$row%count($this->rowCssClass)]',
			'template' => '{items}',
		);
		
		$data = array_values($this->session);
		$paymentOptions = array();
		foreach($data as $k=>$v)
			if(isset($v['extra']['paymentOptions']))
				$paymentOptions = count($paymentOptions)
					? array_intersect($paymentOptions,explode(':',$v['extra']['paymentOptions']))
					: explode(':',$v['extra']['paymentOptions']);
					
		if(count($paymentOptions))
			wm()->get($this->form())->paymentTypes = array_flip($paymentOptions);
		
		wm()->add("base.dialog");
		return parent::taskConfig();
	}
	
	/**
	 * Puts an item into the cart.
	 * @param string module name
	 * @param string item id
	 * @param string item description
	 * @param integer quantity
	 * @param integer price
	 */
	public function taskPut($module,$id,$description,$quantity,$price,$extra=array())
	{
		$session = $this->session;
		
		$key = $module.$id;
		$session[$key] = array(
			'id' => $id,
			'module' => $module,
			'description' => $description,
			'quantity' => $quantity,
			'price' => $price,
			'extra' => $extra
		);
		$this->session = $session;
	}
	
	/**
	 * Removes item from the cart.
	 * @param string module name
	 * @param string item ID
	 */
	public function taskRemove($module,$id)
	{
		$session = $this->session;
		if(isset($session[$module.$id]))
			$session[$module.$id] = null;
		$this->setSession($session);
	}
	
	/**
	 * Removes all items from the cart.
	 */
	public function taskEmpty()
	{
		$this->setSession(null);
	}
	
	public function taskQuantityField($data)
	{
		$class = $data['module'] == 'payment' ? 'allowDecimal negative' : '';
		$str =  CHtml::textField('items['.$data['module'].']['.$data['id'].']', $data['quantity'],
			array('class'=>$class.' quantityField'));
		if(!in_array($data['module'],array('payment','module')))
			$str.= '<br />'.CHtml::link($this->t('remove'),url('/payment/remove',array(
				'module' => $data['module'],
				'id' => $data['id'],
			)), array('class' => 'removeLink'));
		echo $str;
	}
	
	public function columns()
	{
		return array(
			array('header' => $this->t('Description'), 'name' => 'description', 'type' => 'raw'),
			array('header' => $this->t('Quantity'),
				'value' => '$data["quantity"]!==null && $data["price"]>=0
					? wm()->get("payment.cart")->quantityField($data)
					: NULL',
				'type' => 'raw'),
			array('header' => $this->t('Price'), 'value' => '$data["quantity"]!==null && $data["price"]>=0
					? wm()->get("payment.cart")->format($data["price"],"price")
					: NULL',
				'type' => 'raw'),
			array('header' => $this->t('Total'),
				'value' => '$data["price"]<0
					? wm()->get("payment.cart")->quantityField($data)
					: wm()->get("payment.cart")->format((isset($data["total"])?$data["total"]:$data["quantity"]*$data["price"]),"total")',
				'type' => 'raw'),
		);
	}
	
	public function format($amount,$class)
	{
		$format = preg_replace('/([\.|,|0-9|#]+)/',
			'<span class=\''.$class.'\'>\\1</span>',
			app()->locale->getCurrencyFormat());
		return app()->numberFormatter->format($format,$amount,$this->param('cSymbol'));
	}
	
	public function taskItems(){
		return array_values($this->session);
	}
	
	public function dataProvider()
	{
		$data = $this->items();
		
		$total = 0;
		$useCredits = true;
		foreach($data as $k=>$d)
		{
			$total+= (isset ($d['total'])&&$d['total']>0)?$d['total']:$d['price']*$d['quantity'];
			// updating description in case user has changed language
			$data[$k]['description'] = wm()->get($d['module'].'.order')->descriptionInCart($d);
			if($d['module'] == 'deal')
			{
				$deal = MDealPrice::model()->findByPk($d['id'])->deal;
				if(!$deal->useCredits)
					$useCredits = false;
			}
		}

		$credit = wm()->get('payment.helper')->credit();
		if($credit && $useCredits && !$this->param('creditsOnly'))
			$data[] = array(
				'id' => '0',
				'module' => 'payment',
				'description' => $this->t('Use my {site} credit: {credit}',
					array('{site}'=>app()->name,'{credit}' => m('payment')->format($credit))),
				'quantity' => 0,
				'price' => -1
			);
		
		$data[] = array(
			'id' => 'total',
			'module' => 'payment',
			'description' => $this->t('My Price'),
			'quantity' => null,
			'price' => null,
			'total' => $total,
		);
		
		return new CArrayDataProvider($data);
	}
	
	public function taskRenderOutput()
	{
		cs()->registerScript(__CLASS__,'jQuery("#'.$this->getDOMId().'").uPaymentCart("'.url('/payment/calculate').'",{
			decimal: "'.app()->locale->getNumberSymbol('decimal').'",
			group: "'.app()->locale->getNumberSymbol('group').'",
			minusSign: "'.app()->locale->getNumberSymbol('minusSign').'"
		});');
		parent::taskRenderOutput();
	}
}