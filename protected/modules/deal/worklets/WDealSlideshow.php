<?php
class WDealSlideshow extends UWidgetWorklet
{
	public $deal;
	public $description = array();
	public $slides=array();
	public $controls=array();
	public $dims=array();
	public $scaleTo=false;
	public $layout = 'customWorklet';
	public static $count=0;
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
		
	public function taskConfig()
	{
		if(app()->request->isAjaxRequest)
			$this->layout = false;
		elseif(app()->controller->getRouteEased() == 'deal/media/list')
			$this->layout = 'worklet';
		
		if(!$this->deal && isset($_GET['dealId']))
			$this->deal = MDeal::model()->findByPk($_GET['dealId']);
		
		if(!$this->deal)
			return $this->show = false;
			
		$this->slides = array();
		$this->controls = array();
		
		$original = $this->deal->image
			? app()->storage->bin($this->deal->image)->getFilePath('original')
			: null;
			
		if($original)
			list($w,$h) = getimagesize($original);
		else
			list($w,$h) = UHelper::dims($this->module->param('fileResize'));
			
		if($this->scaleTo)
		{
			$ratio = $this->scaleTo/$w;
			$w = $this->scaleTo;
			$h = round($h*$ratio);
		}
			
		$this->dims = array($w,$h);
		$mob = app()->request->isMobile;
		
		foreach($this->deal->media as $k=>$m)
		{
			$k++;
			if($m->type == 1)
			{
				if(!$mob || $m->data == 'original')
				{
					$this->slides[$k] = CHtml::image(
						app()->storage->bin($this->deal->image)->getFileUrl($m->data),
						'', array('width' => $w, 'height' => $h)
					);
					$this->controls[$k] = 1;
					$this->description[$k] = $m->description;
				}
				
			}
			elseif(!$mob && $m->type == 2)
			{
				$video = preg_replace('/width=(["|\'|0-9]+)/','width="'.$w.'"',$m->data);
				$video = preg_replace('/height=(["|\'|0-9]+)/','height="'.$h.'"',$video);
				$this->slides[$k] = $video;
				$this->controls[$k] = 2;
				$this->description[$k] = $m->description;
			}
		}
	}
	
	public function taskRenderOutput()
	{
		self::$count++;
		
		if(!count($this->slides))
		{
			echo '';
			return;
		}		
		if(count($this->slides) == 1)
		{
			echo current($this->slides);
			return;
		}
		
		list($w,$h) = $this->dims;
		$assets = asma()->publish(Yii::getPathOfAlias('application.modules.deal.js.anythingslider'));
		cs()->registerScriptFile($assets.'/js/jquery.easing.1.2.js');
		cs()->registerScriptFile($assets.'/js/swfobject.js');
		cs()->registerScriptFile($assets.'/js/jquery.anythingslider.min.js');
		cs()->registerCssFile($assets.'/css/anythingslider.css');
		$this->render('slideshow');
		cs()->registerScript(__CLASS__.'#'.self::$count,
			'jQuery("#'.$this->getDOMId().' .slideshow").uSlideshow({width: "'.$w.'px", height: "'.($h+20).'px"});');
	}
	
	public function getDOMId()
	{
		return parent::getDOMId().'-'.self::$count;
	}
}