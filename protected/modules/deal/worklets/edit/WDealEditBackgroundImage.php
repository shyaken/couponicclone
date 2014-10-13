<?php
class WDealEditBackgroundImage extends UUploadWorklet
{
	public $modelClassName = 'MDealBackgroundImageForm';
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
	
	public function beforeAccess()
	{
		if(isset($_GET['id']) && wm()->get('deal.edit.helper')->authorize($_GET['id']))
			return true;
		$this->accessDenied();
		return false;
	}
	
	public function taskDeal()
	{
		static $deal;		
		if(!isset($deal))
			$deal = wm()->get('deal.helper')->deal(app()->request->getParam('id',null));
		return $deal;
	}
	
	public function properties()
	{
		return array(
			'action' => url('/deal/edit/backgroundImage', array('id' => app()->request->getParam('id',null))),
			'activeForm' => array(
				'class' => 'UActiveForm',
				'ajax' => false,
			),
			'enctype' => 'multipart/form-data',
			'elements' => array(
				CHtml::hiddenField('field','background'),
				CHtml::hiddenField('bin',$this->deal()->background),
				'background' => app()->param('uploadWidget')
				? array(
					'type' => 'UUploadify',
					'options' => array(
						'script'=>url('/deal/edit/backgroundImage',array('id' => app()->request->getParam('id',null))),
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
	
	public function afterBin($result)
	{
		if($result)
			$result->roles[] = 'citymanager';
	}
	
	public function afterSave()
	{	
		if($this->bin)
		{
			$this->deal()->background = $this->bin->id;
			$this->deal()->save();
			$this->bin->makePermanent($this->deal()->company->userId);
		}
	}
	
	public function afterDelete()
	{
		$this->deal()->background = NULL;
		$this->deal()->save();
	}
	
	public function ajaxSuccess()
	{
		parent::ajaxSuccess();
		$id = CHtml::getIdByName(CHtml::activeName($this->model,$_POST['field']));
		$binField = CHtml::getIdByName(CHtml::activeName($this->model,$_POST['field'].'-bin'));
		$content = $this->render('imageWithControls', array(
			'src' => $this->bin->getFileUrl('original').'?_r='.time(),
			'controls' => array(
				$this->t('Delete') => url('/deal/edit/backgroundImage', array('id' => app()->request->getParam('id',null), 'delete'=>1))
			),
		), true);
		wm()->get('base.init')->addToJson(array('content' => $content, 'close' => true));
	}
	
	public function successUrl()
	{
		return url('/deal/edit/background', array('id' => app()->request->getParam('id',null)));
	}
}