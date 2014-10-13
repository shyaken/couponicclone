<?php
class WCompanyInstallUpgrade_1_3_0 extends UInstallWorklet
{
	public $fromVersion = '1.2.3';
	public $toVersion = '1.3.0';
	
	public function taskSuccess()
	{
		// updating company user account roles
		$models = MCompany::model()->findAll();
		foreach($models as $m)
		{
			if(strpos($m->userAccess,':df:')!==false && $m->user)
			{
				$m->user->role = 'company.editor';
				$m->user->save();
			}
		}
		app()->db->createCommand("ALTER TABLE `{{Company}}` DROP `userAccess`")->execute();
		
		// removing original auth roles and associations
		$am = app()->authManager;
		$rolesToRemove = array(
			'company',
			'company.admin',
			'company.editor',
			'company.edit.info',
			'company.edit.deal.basic',
			'company.edit.deal.full',
			'company.editor.info',
			'company.editor.deal.basic',
			'company.editor.deal.full'
		);
		foreach($rolesToRemove as $role)
			$am->removeAuthItem($role);
		
		parent::taskSuccess();
	}
	
	public function taskModuleAuth()
	{
		return array(
			'items' => array(
				'company' => array(2,'Company Account (Viewer)',NULL,NULL),
				'company.editor' => array(2,'Company Account (Editor)',NULL,NULL),
				'company.owner' => array(1,'company owner','return $params->userId == app()->user->id;',NULL),
				'company.edit' => array(0,'edit deal or company info',NULL,NULL),
			),
			'children' => array(
				'administrator' => array('company','company.editor','company.edit'),
				'company' => array('user','company.coupon.access'),
				'company.editor' => array('user','company','company.coupon.access','company.owner'),
				'company.owner' => array('company.edit'),
			),
		);
	}
}