<?php
class WApiShare extends UApiWorklet
{
	public function afterConfig()
	{
		if (app()->user->isGuest)
			$this->errorMessage = 'Authentication required';
		
		$email = $this->getRequiredParam('email');
		$id = $this->getRequiredParam('id');
		$language = $this->getRequiredParam('language');
		$langs = wm()->get('base.language')->languages();
		if(!isset($langs[$language]))
			$this->errorMessage = 'No such language.';
		
		$deal = MDeal::model()->findByPk($id);
		if(!$deal)
			$this->errorMessage = 'No such deal.';
			
		$validator = new CEmailValidator;
		if(!$validator->validateValue($email))
			$this->errorMessage = $this->t('Invalid Email.');
			
		app()->mailer->send(array('from'=>array('name'=>app()->user->model()->getName(true), 'email'=>app()->user->model()->email), 'to' => $email),
			'shareEmail', array('deal' => $deal, 'user' => app()->user->model()));
	}
}