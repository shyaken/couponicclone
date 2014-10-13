<?php
class WSubscriptionAdminImport extends UFormWorklet
{
	public $modelClassName = 'UDummyModel';

	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return txt()->format(MSubscriptionList::model()->findByPk($_GET['listId'])->title,':',' ',$this->t('Import Subscribers'));
	}
	
	public function properties()
	{
		return array(
			'elements' => array('info' => $this->render('info', array(), true)),
			'buttons' => array(
				'submit' => array('type' => 'submit', 'label' => $this->t('Import')),
			),
			'model' => $this->model
		);
	}
	
	public function taskSave()
	{
		set_time_limit(0);
		ignore_user_abort(1);
		
		$file = app()->getBasePath().DS.'runtime'.DS.'emails.txt';
		$validator = new CEmailValidator;
		if(is_file($file))
		{
			$handle = @fopen($file, "r");
			if($handle)
			{
				while(!feof ($handle))
				{
					$email = trim(fgets($handle, 4096));
					if($email && $validator->validateValue($email))
					{
						wm()->get('subscription.helper')->addEmailToList($email,$_GET['listId']);
						if(m('deal')->param('categories')>=0)
							wm()->get('subscription.helper')->addEmailToList($email,array('type' => 2, 'relatedId' => 0));
					}
				}
				fclose($handle); 
				return true;
			}
			else
				$this->model->addError('emails',$this->t('Import file cannot be opened. Please check permissions.'));
		}
		else
			$this->model->addError('emails',$this->t('Import file does not exist.'));
		return false;
	}
	
	public function ajaxSuccess()
	{
		$list = wm()->get('subscription.admin.emails');
		wm()->get('base.init')->addToJson(array(
			'content' => array(
				'append' => CHtml::script('$.fn.yiiGridView.update("' .$list->getDOMId(). '-grid");
					$.uniprogy.dialogClose();'),
			)
		));
	}
}