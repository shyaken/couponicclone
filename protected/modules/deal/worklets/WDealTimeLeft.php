<?php
class WDealTimeLeft extends UWidgetWorklet
{
	public $start;
	public $end;
	public $timeZone;
	public $counterLayoutFull = 'countdownFull';
	public $counterLayoutShort = 'countdownShort';
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function taskRenderOutput()
	{
		$this->js();
			
		$layoutView = $this->end-time() > 86400
			? $this->counterLayoutFull
			: $this->counterLayoutShort;
			
		$layout = $this->render($layoutView,null,true);

		cs()->registerScript(__CLASS__, 'var untilDate = new Date('.($this->end * 1000).');
		$("#'.$this->getDOMId().' .timer").countdown({until: untilDate,
		layout: "' . $layout . '", onTick: function(periods){
			$("#'.$this->getDOMId().'").uTimeLeftTick(periods,'.($this->end-$this->start).');
		}});');
		
		$this->render('timeLeft');
	}
	
	public function taskJs()
	{
		cs()->registerScriptFile(
			asma()->publish($this->module->basePath.DS.'js'.DS.'countdown'.DS.'jquery.countdown.js'));
		if(app()->language!=app()->sourceLanguage)
		{
			$files = array($this->module->basePath.DS.'js'.DS.'countdown'.DS.'jquery.countdown-'
				. str_replace('_','-',app()->language).'.js');
			if(($pos = strpos(app()->language,'_'))!==false)
				$files[] = $this->module->basePath.DS.'js'.DS.'countdown'.DS.'jquery.countdown-'
					. substr(app()->language,0,$pos).'.js';
			
			foreach($files as $file)
				if(file_exists($file))
				{
					cs()->registerScriptFile(asma()->publish($file));
					break;
				}
		}
	}
}