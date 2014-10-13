<?php
class WDealStatus extends UWidgetWorklet
{
	public $deal;
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function taskConfig()
	{
		if(!$this->deal && isset($_GET['id']))
			$this->deal = MDeal::model()->findByPk($_GET['id']);
		if(!$this->deal)
			return $this->show = 0;	
		if(app()->request->isAjaxRequest)
			$this->layout = false;
	}
	
	public function taskRenderOutput()
	{
		if(!app()->request->isAjaxRequest)
		{
			cs()->registerScriptFile(cs()->getCoreScriptUrl().'/jui/js/jquery-ui.min.js');
			if(!app()->request->isMobile)
			{
				cs()->registerScriptFile(asma()->publish($this->module->basePath.DS.'js'.DS.'jquery.timers.js'));
				cs()->registerScript(__CLASS__.'#'.$this->deal->id,'$("#'.$this->getDOMId().'").uDealStatusPing("'.url('/deal/status',array('id'=>$this->deal->id)).'");');
			}
		}

		switch(wm()->get('deal.helper')->dealStatus($this->deal, true))
		{
			case 'active':
				$data = array(
					'bought' => $this->bought(),
					'required' => (int)$this->deal->purchaseMin
				);
				$this->render('active',$data);
				break;
			case 'tipped':
				$this->render('tipped',array('bought'=>$this->bought(),
					'tippedTime' => $this->deal->cacheValue('tippedTime')?$this->deal->cacheValue('tippedTime'):time(),
					'tippedAmount' => $this->deal->cacheValue('tippedAmount')?$this->deal->cacheValue('tippedAmount'):$this->deal->purchaseMin));
				break;
			case 'closed':
				$this->render('closed',array('bought'=>$this->bought()));
				break;
		}
	}
	
	public function taskBought()
	{
		$bought = $this->deal->stats?(int)$this->deal->stats->bought:0;
		if($this->deal->statsAdjust)
			$bought+= $this->deal->statsAdjust;
		return $bought;
	}
}