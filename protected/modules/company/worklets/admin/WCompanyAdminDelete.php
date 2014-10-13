<?php
class WCompanyAdminDelete extends UDeleteWorklet
{
	public $modelClassName = 'MCompany';
	
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function taskDelete($id)
	{
		$model = MCompany::model()->findByPk($id);
		if($model->user)
		{
			$model->user->role = 'user';
			$model->user->save();
		}
			
		$models = MDeal::model()->findAll('companyId=?',array($id));
		foreach($models as $m)
			wm()->get('deal.admin.delete')->delete($m->id);
			
		parent::taskDelete($id);
	}
}