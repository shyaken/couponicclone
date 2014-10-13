<?php
class FUserMain extends UWorkletFilter
{
	public function filters()
	{
		return array(
			'base.init' => array('behaviors' => array('user.changePassword')),
			'base.auth.webUser' => array('replace' => 'authWebUser'),
			'base.auth.userIdentity' => array('replace' => 'authUserIdentity'),
			'base.auth.access' => array('replace' => array('user.auth.access')),
			'base.settings' => array('behaviors' => array('user.language')),
			'admin.menu' => array('behaviors' => array('user.adminMenu')),
			'user.admin.create' => array('replace' => array('user.admin.update')),
		);
	}
	
	public function authWebUser()
	{
		return array('user.auth.webUser');
	}
	
	public function authUserIdentity()
	{
		return array('user.auth.userIdentity');
	}
}