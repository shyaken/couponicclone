<?php
class WDealImage extends UUploadWorklet
{
	public $modelClassName = 'MDealImageForm';
	public $fileLabel = 'temp';
	public $space = 'inside';
	
	public function title()
	{
		return $this->t('Upload Image');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company.editor')),
			array('deny', 'users'=>array('?'))
		);
	}
	
	public function properties()
	{
		$listWorklet = wm()->get('deal.media.list');
		
		return array(
			'action' => url('/deal/image', array('id' => $this->deal()->id)),
			'activeForm' => array(
				'class' => 'UActiveForm',
				'ajax' => false,
			),
			'enctype' => 'multipart/form-data',
			'elements' => array(
				CHtml::hiddenField('field','image'),
				CHtml::hiddenField('bin',$this->deal()->image),
				'image' => app()->param('uploadWidget')
				? array(
					'type' => 'UUploadify',
					'options' => array(
						'script' => url('/deal/image', array('id' => app()->request->getParam('id',null))),
						'auto' => false,
						'multi' => true,
						'binField' => isset($_GET['binField']) ? $_GET['binField'] : '',
					),
					'callbacks' => array(
						'onAllComplete' => 'function(){
							$.fn.yiiListView.update("' .$listWorklet->getDOMId(). '-list");
							$("#wlt-DealSlideshow-1").uWorklet().load({url: "'.url('/deal/slideshow',array('dealId'=>$this->deal()->id)).'"});
							$.uniprogy.dialogClose();
						}',
					),
					'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
				)
				: array('type' => 'file'),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit', 'label' => $this->t('Upload'), 'id' => 'uploadButton'),
			),
			'model' => $this->model
		);
	}
	
	public function taskDeal()
	{
		static $deal;		
		if(!isset($deal))
			$deal = wm()->get('deal.helper')->deal(app()->request->getParam('id',null));
		return $deal;
	}
	
	public function taskCompany()
	{
		static $company;
		if(!isset($company))
		{
			$deal = $this->deal();
			$company = $deal
				? $deal->company
				: MCompany::model()->find('userId=?',array(app()->user->id));
		}
		return $company;
	}
	
	public function beforeAccess()
	{
		if($this->deal())
		{
			if(!wm()->get('deal.edit.helper')->authorize($this->deal()))
			{
				$this->accessDenied();
				return false;
			}
		}
		else
		{
			if(!app()->user->checkAccess('administrator') && (!$this->company()
				|| !app()->user->checkAccess('company.edit',$this->company())))
			{
				$this->accessDenied();
				return false;
			}
		}
	}
	
	public function afterSave()
	{	
		if($this->bin)
		{
			Yii::import('uniprogy.extensions.image.Image');
			
			// resize image
			$image = new Image($this->bin->getFilePath('temp'));
							
			list($width, $height) = UHelper::dims($this->param('fileResize'));
			if($image->width > $width || $image->height > $height)
				$image->resize($width, $height, Image::AUTO);
			
			// create thumbnail
			copy($this->bin->getFilePath('temp'), app()->getBasePath().'/runtime/'.basename($this->bin->getFilePath('temp')));
			$thumbnail = new Image(app()->getBasePath().'/runtime/'.basename($this->bin->getFilePath('temp')));
			$thumbnail->resize(150, 150);
			
			$files = $this->bin->getFiles();
			$fileLabel = is_array($files) && count($files) > 1 ? 'image'.count($files) : 'original';
			$this->bin->put($image, $fileLabel, 'UImageStorageFile');
			$this->bin->put($thumbnail, $fileLabel.'_t', 'UImageStorageFile');
			
			// deleting temp files
			unlink(app()->getBasePath().'/runtime/'.basename($this->bin->getFilePath('temp')));
			$this->bin->delete('temp');
			
			// is we're updating existing deal we should store it's new 'image' bin value
			if(($id=app()->request->getParam('id',null)))
			{
				$m = new MDealMedia;
				$m->dealId = $this->deal()->id;
				$m->type = 1;
				$m->data = $fileLabel;
				$m->save();
				
				$deal = $this->deal();
				if($deal)
				{
					$deal->image = $this->bin->id;
					$deal->save();
				}
				$this->bin->makePermanent($deal->company->userId);
			}
		}
	}
	
	public function afterBin($result)
	{
		if($result)
			$result->roles[] = 'citymanager';
	}
	
	public function taskDelete()
	{
		return;
	}
	
	public function taskRenderOutput()
	{
		parent::taskRenderOutput();
		if(app()->param('uploadWidget'))
			cs()->registerScript(__CLASS__,'jQuery("#uploadButton").click(function(){
				$("#'.CHtml::getIdByName(CHtml::activeName($this->model,'image')).'").uploadifyUpload();
				return false;
			});');
	}
	
	public function successUrl()
	{
		return url('/deal/media/list',array('dealId' => $this->deal()->id));
	}
}