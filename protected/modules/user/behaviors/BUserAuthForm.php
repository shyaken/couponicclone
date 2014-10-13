<?php
class BUserAuthForm extends UWorkletBehavior
{
	public function afterConfig()
	{
		$w = wm()->get('user.signup');
		$w->init();
		$elements = array_merge(
			array(
				'signinBlock' => $w->render('signInBox',array(),true),
				'signupFormOpen' => '<div class="signupFormDiv">',
				'signupFormHeader' => '<h3>'.$this->t('Account Information').'</h3>',
			),
			$w->properties['elements'],
			array(
				'signupFormClose' => '</div>',
			)
		);
			
		$this->owner->insert('top', array('signupForm' => array(
			'type' => 'UForm',
			'elements' => $elements,
			'model' => $w->properties['model'],
		)));
	}
	
	public function beforeSave()
	{
		unset($this->owner->properties['elements']['signupForm']);
		$w = wm()->get('user.signup');
		$w->form->submit();
		$w->init();
	}
	
	public function valid($owner)
	{
		return (($this->module->param('emailVerification')=='0' || $this->module->param('unverifiedAccess')=='0')
			&& ($this->module->param('approveNewAccounts')=='0' || $this->module->param('unapprovedAccess')=='0'));
	}
}