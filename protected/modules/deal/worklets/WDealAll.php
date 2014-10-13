<?php
class WDealAll extends UListWorklet
{
	public $modelClassName = 'MDealAllListModel';
	public $type = 'list';
	
	public function title()
	{
		return $this->typeSetting() == 'active'
			? $this->t('Current Deals')
			: $this->t('Upcoming Deals');
	}
	
	public function taskConfig()
	{
		parent::taskConfig();
		$session = wm()->get('deal.allFilter')->session;
		$settings = isset($session['settings']) ? unserialize($session['settings']) : $this->defaultSettings();
			
		foreach($settings as $k=>$v)
			$this->model->$k = $v;
			
		$this->model->type = $this->typeSetting();
		
		$addTitle = $this->typeSetting() == 'active'
			? CHtml::link($this->t('Upcoming Deals'),url('/deal/all',array('type' => 'upcoming')),array('class' => 'switchLink'))
			: CHtml::link($this->t('Current Deals'),url('/deal/all',array('type' => 'active')),array('class' => 'switchLink'));
		
		if($this->param('upcoming'))
			$this->title.= ' | '.$addTitle;
		
		if ($this->typeSetting() == 'active')
			$this->options = array('afterAjaxUpdate' => 'js: function(){$.uniprogy.updTimers()}',);
		
		if(isset($_GET['ajax']))
			$this->layout = false;
	}
	
	public function form()
	{
		return false;
	}
	
	public function taskTypeSetting()
	{
		if(!$this->param('upcoming'))
			return 'active';
		
		$session = $this->session;
		if(!isset($session['type']))
			$session['type'] = 'active';
		$session['type'] = isset($_GET['type']) ? $_GET['type'] : $session['type'];
		
		$this->session = $session;
		return $session['type'];
	}
	
	public function itemView()
	{
		return 'deal';
	}
	
	public function afterBuild()
	{
		if(!app()->request->isAjaxRequest)
			wm()->add('deal.allFilter', null, array('position' => array('before' => $this->id)));
	}
	
	public function taskDefaultSettings()
	{
		return array(
			'location' => wm()->get('deal.helper')->location()
		);
	}
	
	public function taskRenderOutput()
	{
		$this->beginContent('list');
		parent::taskRenderOutput();
		$this->endContent();
		
		$script = 
		'$("#'.$this->getDOMId().' .switchLink").click(function(e){
			e.preventDefault();
			$.ajax({
				url: $(this).attr("href"),
				success: function(data) {
					$("#'.$this->getDOMId().'").after(data).remove();
				}
			});
		});';
		
		if ($this->typeSetting() == 'active')
			$script	.= '$.uniprogy.updTimers();';
				
		cs()->registerScript(__CLASS__, $script);
		
		wm()->get('deal.timeLeft')->js();
	}
}