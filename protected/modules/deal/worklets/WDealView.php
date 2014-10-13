<?php
class WDealView extends UWidgetWorklet
{
	public $deal;
	
	public function accessRules()
	{
		return array(array('allow','users'=>array('*')));
	}
	
	public function taskConfig()
	{
		$locs = array();
		if(isset($_GET['url']))
		{
			$deal = MDeal::model()->with('imageBin.files','stats')->find('url=?',array($_GET['url']));
            if(!empty($deal)){
                if($deal->active != 1
					&& !app()->user->checkAccess('citymanager')
					&& !app()->user->checkAccess('company.owner',$deal->company,false))
                {
					$this->accessDenied();
					return false;
                }
                $this->deal = $deal;
            }
            else{
                throw new CHttpException(404,'The requested page does not exist.');
            }
		}
			
		if(!$this->deal || $this->deal->locs[0]->location == 0)
			$locs[] = wm()->get('deal.helper')->location();
		else
			foreach($this->deal->locs as $l)
				$locs[] = $l->location;
			
		$gmtNow = UTimestamp::getNow();		
		
		$c = new CDbCriteria;
		$categoryC = new CDbCriteria;
		$c->with = array();
		$c->params = array();
		
		if($this->param('categories') >= 0)
		{
			$category = wm()->get('deal.category.helper')->userCategory();
			if($category)
			{
				$categoryC->with[] = 'categories';
				$categoryC->addCondition('categories.id=:category');
				$categoryC->params[':category'] = $category;
			}
		}
		if($this->param('categories') <= 0)
		{
			$c->with[] = 'locs';
			$c->addCondition('locs.location = 0 OR locs.location IN( '.implode(',',$locs).' )');
		}
		$c->compare('t.active','1');
		$c->compare('t.status','1');
		$c->compare('start','<='.$gmtNow);
		$c->compare('end','>='.$gmtNow);		
		$c->order = 'priority ASC';
		
		if($this->deal)
		{
			$c->mergeWith(new CDbCriteria(array(
				'condition' => 't.id <> :id',
				'params' => array(':id' => $this->deal->id),
			)));
		}
		
		$resultC = new CDbCriteria;
		$resultC->mergeWith($c);
		$resultC->mergeWith($categoryC);
		
		$deals = MDeal::model()->with('imageBin.files','stats')->findAll($resultC);
		
		if(!$this->deal && !count($deals))
		{
			$deals = MDeal::model()->with('imageBin.files','stats')->findAll($c);
			if(count($deals))
				wm()->get('base.helper')->saveToCookie('category',null);
		}
		
		if(!$this->deal && count($deals))
			$this->deal = array_shift($deals);
		
		if(!$this->deal)
		{
			wm()->addCurrent('deal.subscription',null,array('missingDealsLocation' => $locs[0]));
			$this->show = false;
		}
		else
		{
			$currentLocation = wm()->get('base.helper')->getFromCookie('location');
			$location = null;
			foreach($this->deal->locs as $loc)
			{
				if($loc->location == 0 || $loc->location == $currentLocation)
				{
					$location = null;
					break;
				}
				$location = $loc->location;
			}
			if($location)
				wm()->get('deal.helper')->setLocation($location);
				
			$viewed = wm()->get('base.helper')->getFromCookie('deal.viewed.' . $this->deal->id);
			if(!$viewed)
			{			
				$stats = wm()->get('deal.helper')->dealStats($this->deal->id);
				$stats->views+= 1;
				$stats->save();
				wm()->get('base.helper')->saveToCookie('deal.viewed.' . $this->deal->id, 1);
			}			
			wm()->add('deal.info', null, array('deal'=>$this->deal));
			if(count($deals))
				wm()->add('deal.side',null,array('deals' => $deals));
			
			if($this->param('categories')>=0)
				wm()->add('deal.category.select',null,array('forPage' => 'active'));
			
			if($this->deal->imageBin && !wm()->get('base.helper')->isMobile())
				cs()->registerLinkTag('image_src',null,
					app()->storage->bin($this->deal->imageBin)->getFileUrl('original'));
                        
            if($this->deal->background)
                cs()->registerCss('custom.background','body {background: url('.app()->storage->bin($this->deal->background)->getFileUrl('original').') no-repeat top left fixed;}');
			
			if(wm()->get('base.helper')->isMobile())
			{
				if(count($this->deal->prices)>1)
					wm()->add('deal.price.dialog', null, array('dealId' => $this->deal->id));
			}
			else
				wm()->add('base.dialog');
		}
	}
	
	public function taskRenderOutput()
	{
		// we have to pre-publish list view css and js because of cache, side deals and multiple pricing options conflict
		$url=Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('zii.widgets.assets')).'/listview';
		cs()->registerCssFile($url.'/styles.css');
		cs()->registerScriptFile($url.'/jquery.yiilistview.js',CClientScript::POS_END);
		
		$script = count($this->deal->prices)>1
			? (!wm()->get('base.helper')->isMobile()
				? '$.uniprogy.dialog("'.url('/deal/price/dialog', array('id' => $this->deal->id)).'");'
				: '$("#priceDialog").click();')
			: 'window.location = "'.url('/deal/purchase', array('id'=>$this->deal->mainPrice->id)).'";';
		
		cs()->registerScript(__CLASS__.'#'.$this->deal->id,
			'$("#'.$this->getDOMId().' .buyButton").click(function(e){
				if(!$(this).hasClass("unavailable"))
				{
					'.$script.'
					e.preventDefault();
				}
			});');
		$this->render('deal', array('deal' => $this->deal));
	}
	
	public function meta()
	{
		$md = parent::meta();
		if($this->deal)
		{
			$md['title'] = $this->deal->name;
			$md['keywords'] = $this->deal->metaKeywords;
			$md['description'] = $this->deal->metaDescription;
		}
		return $md;
	}
}