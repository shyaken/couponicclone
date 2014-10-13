<?php
class WLocationAdminDelete extends UDeleteWorklet
{	
	public $modelClassName = 'MLocationPreset';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function beforeDelete($id)
	{
		$m = MLocationPreset::model()->findByPk($id);
		if($m->location == wm()->get('location.helper')->defaultLocation())
			throw new CHttpException(403, $this->t('You can\'t delete default location.'));
	}
	
	public function afterDelete($id)
	{
		MI18N::model()->deleteAll('model=? AND relatedId=?', array('Location', $id));
	}
}