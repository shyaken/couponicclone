<?php
class WApiDeal extends UApiWorklet
{
	public function afterConfig()
	{
		$location = $this->getRequiredParam('location');
		$language = $this->getRequiredParam('language');
		$categories = app()->request->getParam('categories',null);
		$id = app()->request->getParam('id',null);

		$langs = wm()->get('base.language')->languages();
		
		if(!isset($langs[$language]))
			$this->errorMessage = 'No such language.';
		
		app()->language = $language;
		
		$gmtNow = UTimestamp::getNow();		
		
		$c = new CDbCriteria;
		$c->with = array();
		$c->params = array();
		
		if(!is_null($id))
            $c->compare('t.id',$id);
        else
		{
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
        }
		
		$deal = MDeal::model()->with('imageBin.files','stats')->find($c);
		
        if(!$deal)
           $this->errorMessage = 'No active deal.';
        
		$data = array();
		$data['id'] = $deal['id'];
		$data['url'] = aUrl('/deal/view',array('url'=>$deal['url']));
		$data['title'] = $deal->name;
		foreach ($deal->locs as $value) 
			$data['cities'][$value->id]['city'] = $value->id;
		$k = 0;
		foreach($deal->media as $m)
		{
			if($m->type == 1)
			{
                if($deal->image) {
                    $img = app()->storage->bin($deal->image)->getFileUrl($m->data.'_t');
                    if (!$img)
                        $img = app()->storage->bin($deal->image)->getFileUrl($m->data);
                    $data['slideshow'][$k]['slide']['resource'] = $img;
                }
			}
			$k++;
		}
		
		$data['company'][$deal->companyId]['id'] = $deal->companyId;
		$data['company'][$deal->companyId]['name'] = $deal->company->name;
		$data['company'][$deal->companyId]['address'] = $deal->company->address;
		$data['company'][$deal->companyId]['website'] = $deal->company->website;
		
		$dealStats = array('active','tipped','unavailable','closed');
		$data['dealStatus'] = $dealStats[$deal->status-1];
		
		$data['start'] = date('Y-m-d h:m:s',$deal->start);
		$data['end'] = date('Y-m-d h:m:s',$deal->end);
        $date = getdate();
        $data['time_left'] = $deal->end - $date[0];

		
		$data['tipped'] = $deal->stats&&$deal->stats->bought>=$deal->purchaseMin?'true':'false';
		$data['tipping_point'] = $deal->purchaseMin;
		$data['purchase_max'] = $deal->purchaseMax;
		$data['tipped_time'] = date('Y-m-d h:m:s',$deal->cacheValue('tippedTime'));
		
		$data['sold'] = $deal->stats?$deal->stats->bought:0;
		
		foreach($deal->prices as $p)
		{
			$price = array('id' => $p->id, 'name' => $p->name, 'main' => $p->main,'price'=>$p->price, 'value'=>$p->value, 'couponPrice'=>$p->couponPrice);
			$data['pricing'][]['option'] = $price;
		}
		
		$data['info'][]['fine_print'] = $deal->fineprint;
		$data['info'][]['highlights'] = $deal->highlights;
        $descr = strip_tags($deal->description);
        $descr = preg_replace("/[\r|\n]+/", "\n", $descr);
		$data['info'][]['description'] = $descr;
		
		foreach ($deal->reviews as $k=>$value){
			$data['info']['reviews'][$k]['review']['author'] = $value->name;
			$data['info']['reviews'][$k]['review']['website'] = $value->website;
			$data['info']['reviews'][$k]['review']['text'] = $value->review;
		}
		
		foreach ($deal->redeemLocs as $k=>$value){
			$data['info']['redeem'][$k]['loc']['address'] = wm()->get("deal.location.redeemList")->loc($value);
			$data['info']['redeem'][$k]['loc']['lon'] = $value->lon;
			$data['info']['redeem'][$k]['loc']['lat'] = $value->lat;
		}		
		$d['deal'] = $data;
		$this->data = $d;
	}
}