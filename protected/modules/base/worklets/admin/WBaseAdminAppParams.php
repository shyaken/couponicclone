<?php
class WBaseAdminAppParams extends UParamsWorklet
{
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return $this->t('General Settings');
	}
	
	public function properties()
	{
		return array(
			'elements' => array(
				'name' => array('type' => 'text'),
				'poweredBy' => array('type' => 'text', 'class' => 'large',
					'hint' => $this->t('Please note that you can remove "Powered by Couponic" only if you have ordered "branding removal" from us.')),
				'adminUrl' => array('type' => 'text', 'hint' => aUrl('/') . '/', 'layout' => "{label}\n<span class='hintInlineBefore'>{hint}\n{input}</span>"),
				'cronSecret' => array('type' => 'text', 'hint' => $this->t('Cron command: {command}', array(
					'{command}' => CHtml::tag('span',array('id' => 'cronCommand'), $this->cronCommand()),
				))),
				'timeZone' => array('type' => 'dropdownlist',
					'items' => include(app()->basePath.DS.'data'.DS.'timezones.php')),
				'publicAccess' => array('type' => 'radiolist', 'items' => array(
					1 => $this->t('Allow visitors to browse the whole site'),
					0 => $this->t('Allow visitors to access only home and registration pages')
				), 'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>"),
				'uploadWidget' => array('type' => 'radiolist', 'items' => array(
					1 => $this->t('Yes'),
					0 => $this->t('No')
				), 'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
				   'hint' => $this->t('Whether the script should activate ajax/flash widget to handle file uploads across the site')),
				'maxCacheDuration' => array('type' => 'text', 'hint' => $this->t('seconds'),
					'class' => 'short', 'layout' => "{label}\n<span class='hintInlineAfter'>{input}\n{hint}</span>"),
				'systemEmail' => array('type' => 'text'),
				'contactEmail' => array('type' => 'text'),
				'newsletterEmail' => array('type' => 'text'),
				'htmlEmails' => array('type' => 'radiolist', 'items' => array(
					1 => $this->t('Send HTML emails'),
					0 => $this->t('Send plain text emails only')
				), 'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>"),
				'keywords' => array('type' => 'textarea'),
				'description' => array('type' => 'textarea'),
				'<h4>'.$this->t('Mail Settings').'</h4>',
				'mailPriority' => array(
					'type' => 'radiolist', 'items' => array(
						1 => $this->t('High'), 
						3 => $this->t('Normal'),
						5 => $this->t('Low'),
					),
					'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>",
				),
			    'mailCharSet' => array('type' => 'text'),
			    'mailEncoding' => array('type' => 'text'),
			    'mailMailer' => array(
			    	'type' => 'radiolist', 'items' => array(
			    		'mail' => $this->t('PHP Mail Function'),
			    		'sendmail' => $this->t('Sendmail'),
			    		'smtp' => $this->t('SMTP'),
			    	),
			    	'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>",
			    ),
			    '<div class="sendmailSettings">',
			    'mailSendmail' => array('type' => 'text'),
			    '</div><div class="smtpSettings">',
			    'mailHost' => array('type' => 'text', 'hint' => 'e.g. "smtp1.example.com:25;smtp2.example.com"'),
			    'mailPort' => array('type' => 'text'),
			    'mailSMTPSecure' => array('type' => 'text'),
			    'mailSMTPAuth' => array(
			    	'type' => 'radiolist', 'items' => array(
			    		1 => $this->t('Enable'),
			    		0 => $this->t('Disable'),
			    	),
			    	'layout' => "{label}\n<fieldset>{input}\n{hint}</fieldset>",
			    ),			    
			    'mailUsername' => array('type' => 'text'),
			    'mailPassword' => array('type' => 'text'),
			    'mailTimeout' => array('type' => 'text'),
			    '</div>',
			),
			'buttons' => array(
				'submit' => array('type' => 'submit', 'label' => $this->t('Save'))
			),
			'model' => $this->model
		);
	}
	
	public function taskConfig()
	{
		$modelClassName = 'MBaseAppParamsForm';
		$this->model = new $modelClassName;
		$config = require($GLOBALS['config']);
		$this->model->attributes = $config['params'];
		$this->model->name = $config['name'];
		parent::taskConfig();
	}
	
	public function taskSave()
	{
		$models = $this->form->getModels();
		foreach($models as $model)
		{
			$params = $model->attributes;
			$config=array();
			if(isset($params['name'])) {
				$config['name'] = $params['name'];
				unset($params['name']);
			}
			$config['params'] = $params;
			UHelper::saveConfig(app()->basePath .DS. 'config' .DS. 'public' .DS. 'modules.php',$config);
		}
	}
	
	public function taskRenderOutput()
	{
		$att = 'mailMailer';
		$name = CHtml::resolveName($this->model,$att);
		cs()->registerScript(__CLASS__,'jQuery("#'.$this->getDOMId().' input[name=\''.$name.'\']:radio").change(function(){
			$(".sendmailSettings").hide();
			$(".smtpSettings").hide();
			if($(this).is(":checked"))
			{
				if($(this).val() == "sendmail")
					$(".sendmailSettings").show();
				else if($(this).val() == "smtp")
					$(".smtpSettings").show();
			}
		});jQuery("#'.$this->getDOMId().' input[name=\''.$name.'\']:radio").change();');
		parent::taskRenderOutput();
	}
	
	public function ajaxSuccess()
	{
		parent::ajaxSuccess();
		wm()->get('base.init')->addToJson(array(
			'content' => array('append' => CHtml::script('jQuery("#cronCommand").html("'.$this->cronCommand().'")')),
		));
	}
	
	public function cronCommand()
	{
		return 'wget --quiet --delete-after '.aUrl('/admin/cron',array('s' => $this->model->cronSecret));
	}
}