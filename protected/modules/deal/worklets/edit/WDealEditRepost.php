<?php
class WDealEditRepost extends UConfirmWorklet {
	
	public function title()
	{
		$this->t('Re-Post a Deal');
	}
	
	public function taskDescription()
	{
		return $this->t('Create a copy of this deal?');
	}
	
	public function taskYes() {
		$m = new MDeal;
		$old = MDeal::model()->findByPk($_GET['id']);
		foreach ($old->attributes as $k=>$v)
			$m->$k = $v;
		$m->primaryKey = null;
		$m->active = 0;
		$m->status = 1;
		$m->url = null;
		$m->start = null;
		$m->end = null;
		$m->save();
		
		$tables = array('MDealLocation','MDealRedeemLocation','MDealReview', 'MDealCategoryAssoc', 'MDealMedia');
		foreach($tables as $t)
			$this->copy($t, $old, $m);
		
		// deal translations
		$trs = MI18N::model()->findAll('model=? AND relatedId=?', array('Deal',$old->id));
		$this->copyTrs($trs, $m->id);		
		
		// deal prices translations
		$options = MDealPrice::model()->findAll('dealId=?', array($old->id));
		foreach($options as $o)
		{
			$newo = new MDealPrice;
			$newo->attributes = $o->attributes;
			$newo->dealId = $m->id;
			$newo->save();
			$trs = MI18N::model()->findAll('model=? AND relatedId=?', array('DealPrice',$o->id));
			$this->copyTrs($trs, $newo->id);
		}
		
		$files = array('background', 'image');
		foreach($files as $f)
			$this->copyFiles($f, $old, $m);
			
		$this->successUrl = url('/deal/edit/general', array('id' => $m->id));
	}
    
    public function taskCopy($className, $oldM, $newM) {
    	$src = CActiveRecord::model($className)->findAll('dealId=?', array($oldM->id));
    	foreach($src as $m)
    	{
    		$n = new $className;
    		foreach($m->attributes as $k=>$v)
    			$n->$k = $v;
    		$n->primaryKey = null;
    		$n->dealId = $newM->id;
			$n->save();
    	}
    }
	
	public function taskCopyTrs($trs, $to)
	{
		foreach($trs as $t)
		{
			$nt = new MI18N;
			$nt->attributes = $t->attributes;
			$nt->relatedId = $to;
			$nt->save();
		}
	}
    
    public function taskCopyFiles($attribute, $oldM, $newM)
    {
    	if($oldM->$attribute)
    	{
    		$bin = app()->storage->bin($oldM->$attribute);
    		if($bin)
    		{
    			$newBin = app()->storage->bin();
    			foreach($bin->getFiles() as $file)
    			{
    				$src = $bin->getFilePath($file->name);
    				$dst = app()->runtimePath.DS.basename($src);
    				copy($src, $dst);
    				$newBin->put($dst, $file->name);
    			}
    			$newBin->makePermanent($newM->company->userId);
    			$newM->$attribute = $newBin->id;
    			$newM->save();
    		}
    	}
    }
}