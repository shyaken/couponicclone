<?php
class WCustomInstallInstall extends UInstallWorklet
{
	/**
	 * @return array filters which this module attaches to other modules
	 * <pre>
	 * return array(
	 *     'base' => 'custom.main'
	 * );
	 * </pre>
	 * This means that 'custom.main' will be attached to 'base' module.
	 */
	public function taskModuleFilters()
	{
		return array(
		);
	}
	
	/**
	 * @return array module authentification roles as described in 
	 * {@link http://www.yiiframework.com/doc/guide/topics.auth Yii RBAC}.
	 */
	public function taskModuleAuth()
	{
		return array(
			'items' => array(
				// ex.:
				// 'custom.user' => array(1,NULL,'return app()->user->isGuest;',NULL),
			),
			'children' => array(
				// ex.:
				// 'user' => array('custom.user'),
			),
		);
	}
	
	
	public function taskSuccess()
	{
		// any additional things to do after successful installation?
		parent::taskSuccess();
	}
}