<?php
class WLocationAdminBackgroundImage extends UUploadWorklet
{
	public $modelClassName = 'MLocationBackgroundImageForm';
	public $space = 'inside';
	
	public function title()
	{
		return $this->t('Upload Image');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function taskLocation()
	{
		static $loc;		
		if(!isset($loc))
			$loc = MLocationPreset::model()->findByPk(app()->request->getParam('id',null));
		return $loc;
	}
	
	public function properties()
	{
		return array(
			'action' => url('/location/admin/backgroundImage', array('id' => app()->request->getParam('id',null))),
			'activeForm' => array(
				'class' => 'UActiveForm',
				'ajax' => false,
			),
			'enctype' => 'multipart/form-data',
			'elements' => array(
				CHtml::hiddenField('field','background'),
				CHtml::hiddenField('bin',$this->location()->background),
				'background' => app()->param('uploadWidget')
				? array(
					'type' => 'UUploadify',
					'options' => array(
						'script'=>url('/location/admin/backgroundImage',array('id' => app()->request->getParam('id',null))),
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
	
	public function afterSave()
	{	
		if($this->bin)
		{
			$this->location()->background = $this->bin->id;
			$this->location()->save();
			$this->bin->makePermanent();
		}
	}
	
	public function afterDelete()
	{
		$this->location()->background = NULL;
		$this->location()->save();
	}
	
	public function ajaxSuccess()
	{
		parent::ajaxSuccess();
		$id = CHtml::getIdByName(CHtml::activeName($this->model,$_POST['field']));
		$binField = CHtml::getIdByName(CHtml::activeName($this->model,$_POST['field'].'-bin'));
		$content = $this->render('imageWithControls', array(
			'src' => $this->bin->getFileUrl('original').'?_r='.time(),
			'controls' => array(
				$this->t('Delete') => url('/location/admin/backgroundImage', array('id' => app()->request->getParam('id',null), 'delete'=>1))
			),
		), true);
		wm()->get('base.init')->addToJson(array('content' => $content, 'close' => true));
	}
	
	public function successUrl()
	{
		return url('/location/admin/list');
	}
}