<?php
class WUserAvatar extends UUploadWorklet
{
	public $modelClassName = 'MUserAvatarForm';
	public $space = 'inside';
	
	public function accessRules()
	{
		return array(
			array('deny', 'users'=>array('?'))
		);
	}
	
	public function title()
	{
		return $this->t('Upload Image');
	}
	
	public function properties()
	{
		$user = MUser::model()->findByPk($this->userId());
		return array(
			'action' => url('/user/avatar', array('id' => app()->request->getParam('id',null))),
			'activeForm' => array(
				'class' => 'UActiveForm',
				'ajax' => false,
			),
			'enctype' => 'multipart/form-data',
			'elements' => array(
				CHtml::hiddenField('field','avatar'),
				CHtml::hiddenField('bin',$user->avatar),
				'avatar' => app()->param('uploadWidget')
				? array(
					'type' => 'UUploadify',
					'options' => array(
						'script'=>url('/user/avatar',array('id' => app()->request->getParam('id',null))),
						'auto'=>true,
						'multi'=>false,
						'binField' => isset($_GET['binField']) ? $_GET['binField'] : '',
					),
					'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
				)
				: array('type' => 'file'),
			),
			'buttons' => !app()->param('uploadWidget')
			? array(
				'submit' => array('type' => 'submit', 'label' => $this->t('Upload'), 'id' => 'uploadButton'),
			)
			: array(),
			'model' => $this->model
		);
	}
	
	public function beforeAccess()
	{
		$id = app()->request->getParam('id',null);
		if($id && $id != app()->user->id && !app()->user->checkAccess('administrator'))
		{
			$this->accessDenied(app()->user);
			return false;
		}
	}
	
	public function afterSave()
	{	
		if($this->bin)
		{
			Yii::import('uniprogy.extensions.image.Image');
			// resize image
			$image = new Image($this->bin->getFilePath('original'));			
			list($width, $height) = UHelper::dims($this->param('fileResize'));
			if($image->width > $width || $image->height > $height)
				$image->resize($width, $height, Image::AUTO);
			
			$this->bin->put($image, 'original', 'UImageStorageFile');
			
			$user = MUser::model()->findByPk($this->userId());
			$user->avatar = $this->bin->id;
			$user->save();
			$this->bin->makePermanent();
		}
	}
	
	public function afterDelete()
	{
		$user = MUser::model()->findByPk($this->userId());
		if($user)
		{
			$user->avatar = 0;
			$user->save();
		}
	}
	
	public function ajaxSuccess()
	{
		parent::ajaxSuccess();
		$id = CHtml::getIdByName(CHtml::activeName($this->model,$_POST['field']));
		$binField = CHtml::getIdByName(CHtml::activeName($this->model,$_POST['field'].'-bin'));
		$content = $this->render('imageWithControls', array(
			'src' => $this->bin->getFileUrl('original').'?_r='.time(),
			'controls' => array(
				$this->t('Delete') => url('/user/avatar', array('id' => app()->request->getParam('id',null), 'delete'=>1))
			),
		), true);
		wm()->get('base.init')->addToJson(array('content' => $content, 'close' => true));
	}
	
	public function userId()
	{
		static $id;
		if(!isset($id))
		{
			$id=app()->request->getParam('id',null);
			if(!$id || !app()->user->checkAccess('administrator'))
				$id = app()->user->id;
		}
		return $id;
	}
	
	public function successUrl()
	{
		$url = app()->request->urlReferrer;
		if(strpos($url,'deal/coupons')!==false)
			return url('/user/account');
		return $url;
	}
}