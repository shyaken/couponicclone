<?php
class WDealEditBackground extends UFormWorklet
{
	public $modelClassName = 'MDealBackgroundForm';
	public $primaryKey = 'id';
	
	public function title()
	{
		return $this->t('Custom Background');
	}
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('company.editor')),
			array('deny', 'users'=>array('?'))
		);
	}
	
	public function taskDeal()
	{
		return MDeal::model()->findByPk($_GET['id']);
	}
	
	public function beforeAccess()
	{
		if(isset($_GET['id']) && wm()->get('deal.edit.helper')->authorize($_GET['id']))
			return true;
		$this->accessDenied();
		return false;
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
						$this->t('Delete') => url('/deal/edit/backgroundImage', array('id' => $this->model->id, 'delete'=>1))
					),
				), true);
			}
		}
		
		return array(
			'elements' => array(
				'background' => array('type' => 'UUploadField', 'attributes' => array(
					'content' => $background, 
					'label' => $this->t('Upload'),
					'url' => url('/deal/edit/backgroundImage',
						array(
							'id' => $this->model->id,
							'binField'=>CHtml::getIdByName(CHtml::activeName($this->model,'background')),
						)),
				), 'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}"),
			),
			'buttons' => array(
			),
			'model' => $this->model
		);
	}
	
	public function taskBreadCrumbs()
	{
		$r = array();
		if(!app()->user->checkAccess('citymanager'))
			$r[$this->t('Company Admin')] = url('/company/admin');
		$r[$this->t('Deals')] = url('/deal/admin/list');
		$r[] = $this->deal()->name;
		$r[] = $this->t('Custom Background');
		return $r;
	}
	
	public function afterBuild()
	{
		wm()->add('deal.edit.menu', null, array('deal' => $this->deal()));
		wm()->add('base.dialog');
	}
}