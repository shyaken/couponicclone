<?php
class WDealMediaDelete extends UDeleteWorklet
{	
	public $modelClassName = 'MDealMedia';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company.editor')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function beforeDelete($id)
	{
		if(!wm()->get('deal.edit.helper')->authorize($this->deal($id)))
		{
			$this->accessDenied();
			return false;
		}
	}
	
	public function taskDeal($id)
	{
		static $deals=array();
		
		if(!isset($deals[$id]))
			$deals[$id] = MDealMedia::model()->findByPk($id)->deal;
		
		return $deals[$id];
	}
	
	public function taskDelete($id)
	{
		$m = MDealMedia::model()->findByPk($id);
		if($m->type == 1)
			$this->deleteImage($m);
		$query = "UPDATE {{DealMedia}} SET `order`=`order`-1 WHERE `order` > ? AND dealId=?";
		app()->db->createCommand($query)->execute(array($m->order,$m->dealId));
		parent::taskDelete($id);
	}
	
	public function taskDeleteImage($model)
	{
		$deal = $this->deal($model->id);
		$bin = app()->storage->bin($deal->image);
		if($bin)
		{
			$bin->delete($model->data);
			if($bin->getFilePath($model->data.'_t'))
				$bin->delete($model->data.'_t');
			if($model->data == 'original')
			{
				$nextMedia = MDealMedia::model()->find('type=? AND dealId=? AND id<>?', array(
					1, $deal->id, $model->id
				));
				if($nextMedia)
				{
					$file = $bin->get($nextMedia->data);
					if($file)
					{
						$file->name = 'original';
						$file->save();

						$file = $bin->get($nextMedia->data.'_t');
						if($file)
						{
							$file->name = 'original_t';
							$file->save();
						}

						$nextMedia->data = 'original';
						$nextMedia->save();
					}
				}
				else
				{
					$bin->purge();
					$deal->image = NULL;
					$deal->save();
				}
			}
		}
	}
}