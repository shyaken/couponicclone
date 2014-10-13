<?php
class WLocationAdminUpdate extends UFormWorklet
{	
	public $modelClassName = 'MLocationPresetForm';
	public $primaryKey='id';
	public $space = 'inside';
	
	public function title()
	{
		return $this->isNewRecord
			? $this->t('Add New Location')
			: $this->t('Edit Location');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function properties()
	{		
		$background = null;
		if($this->model->background)
		{
			$bin = app()->storage->bin($this->model->background);
			if($bin)
			{
				$background = $this->render('imageWithControls', array(
					'src' => $bin->getFileUrl('original').'?_r='.time(),
					'controls' => array(
						$this->t('Delete') => url('/location/admin/backgroundImage', array('id' => $this->model->primaryKey, 'delete'=>1))
					),
				), true);
			}
		}
			
		return array(
			'elements' => array(
				'cityName' => array(
					'type' => 'UI18NField', 'attributes' => array(
						'type' => 'text',
						'languages' => wm()->get('base.language')->languages(),
					), 'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
				),
				'url' => array('type' => 'text', 'hint' => aUrl('/') . '/', 'layout' => "{label}\n<span class='hintInlineBefore'>{hint}\n{input}</span>"),
				'lon' => array('type' => 'text', 'class' => 'short'),
				'lat' => array('type' => 'text', 'class' => 'short'),
				'background' => array('type' => 'UUploadField', 'attributes' => array(
					'content' => $background, 
					'label' => $this->t('Upload'),
					'url' => url('/location/admin/backgroundImage',
						array(
							'id' => $this->model->primaryKey,
							'binField'=>CHtml::getIdByName(CHtml::activeName($this->model,'background')),
						)),
				), 'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}"),
			),
			'buttons' => array(
				'cancel' => app()->request->isAjaxRequest
					? array('type' => 'UJsButton', 'attributes' => array(
						'label' => $this->t('Close'),
						'callback' => '$(this).closest(".worklet-pushed-content").remove()'))
					: null,
				'submit' => array('type' => 'submit',
					'label' => $this->isNewRecord?$this->t('Add'):$this->t('Save')),
			),
			'model' => $this->model
		);
	}
	
	public function beforeBuild()
	{
		$b = $this->attachBehavior('location.form','location.form');
		$b->ignoreFixed = true;
	}
	
	public function afterConfig()
	{
		if(isset($_GET['ajax']))
			$this->layout = false;
	}
	
	public function afterSave()
	{
		wm()->get('base.helper')->translations('Location',$this->model,'cityName');
	}
	
	public function ajaxSuccess()
	{
		$listWorklet = wm()->get('location.admin.list');
		$message = $this->isNewRecord
			? $this->t('Location has been successfully added.')
			: $this->t('Location has been successfully updated.');
		$json = array(
			'info' => array(
				'replace' => $message,
				'fade' => 'target',
				'focus' => true,
			),
			'content' => array(
				'append' => CHtml::script('$.fn.yiiGridView.update("' .$listWorklet->getDOMId(). '-grid")')
			),
		);
		if($this->isNewRecord)
			$json['load'] = url('/location/admin/update', array('ajax'=>1,'location' => $this->model->location));
			
		wm()->get('base.init')->addToJson($json);
	}
}