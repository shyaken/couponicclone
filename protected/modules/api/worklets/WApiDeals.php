<?php
class WApiDeals extends UApiWorklet
{
	public function afterConfig()
	{
		$location = $this->getRequiredParam('location');
		$language = $this->getRequiredParam('language');
		$categories = app()->request->getParam('categories',null);
					
		$langs = wm()->get('base.language')->languages();
		
		if(!isset($langs[$language]))
			$this->errorMessage = 'No such language.';
		
		app()->language = $language;
		
			
		$gmtNow = UTimestamp::getNow();		
		
		$c = new CDbCriteria;
		
		$c->with = array();
		$c->params = array();
		
		$c->with[] = 'locs';
		$c->addCondition('locs.location = 0 OR locs.location IN( '.$location.' )');
		
		if(!is_null($categories))
		{
			$c->with[] = 'categories';
			$c->addCondition('categories.id IN( '.implode(',',$categories).' )');
		}

		$c->compare('t.active','1');
		$c->compare('t.status','1');
		$c->compare('start','<='.$gmtNow);
		$c->compare('end','>='.$gmtNow);		
		$c->order = 'priority ASC';
		
		$resultC = new CDbCriteria;
		$resultC->mergeWith($c);
		
		
		$deals = MDeal::model()->with('imageBin.files','stats')->findAll($resultC);
		
		$data = array();
		foreach ($deals as $k=>$deal) {
			$data[$k]['deal']['id'] = $deal['id'];
            if($deal->image) {
                $img = app()->storage->bin($deal->image)->getFileUrl("original_t");
                if (!$img)
                    $img = app()->storage->bin($deal->image)->getFileUrl("original");
                $data[$k]['deal']['image'] = $img;
            }
			$data[$k]['deal']['url'] = aUrl('/deal/view',array('url'=>$deal['url']));
			$data[$k]['deal']['title'] = $deal->name;
            $date = getdate();
			$data[$k]['deal']['time_left'] = $deal->end - $date[0];
		}
		$d['deals'] = $data;
		$this->data = $d;
	}
	
}
