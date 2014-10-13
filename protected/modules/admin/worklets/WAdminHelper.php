<?php
class WAdminHelper extends USystemWorklet
{
	public function taskLayout($role='administrator')
	{
		return app()->user->checkAccess($role) && (
			app()->controller->id == 'admin'
			|| (app()->controller->module && app()->controller->module->name == 'admin')
			|| wm()->get('base.init')->states['admin']
		);
	}
	
	public function taskHomeLink()
	{
		return app()->user->checkAccess('administrator')
			? url('/admin')
			: url('/');
	}
}