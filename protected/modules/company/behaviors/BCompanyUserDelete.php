<?php
class BCompanyUserDelete extends UWorkletBehavior
{
	public function afterDelete($id)
	{
		$models = MCompany::model()->findAll('userId=?',array($id));
		foreach($models as $m)
			wm()->get('company.admin.delete')->delete($m->id);
	}
}