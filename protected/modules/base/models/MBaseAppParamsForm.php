<?php
class MBaseAppParamsForm extends UFormModel
{
	public $adminUrl;
	public $name;
	public $publicAccess;
	public $maxCacheDuration;
	public $systemEmail;
	public $contactEmail;
	public $newsletterEmail;
	public $htmlEmails;
	public $keywords;
	public $description;
	public $poweredBy;
	
	public $mailPriority;
	public $mailCharSet;
	public $mailEncoding;
	public $mailMailer;
	public $mailSendmail;
	public $mailHost;
	public $mailPort;
	public $mailSMTPSecure;
	public $mailSMTPAuth;
	public $mailUsername;
	public $mailPassword;
	public $mailTimeout;
	
	public $cronSecret;
	public $timeZone;
	
	public $uploadWidget;
	
	public static function module()
	{
		return 'base';
	}
	
	public function rules()
	{
		return array(
			array(implode(',',array_keys(get_object_vars($this))),'safe')
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'name' => $this->t('Site Name'),
			'adminUrl' => $this->t('Admin Panel URL'),
			'cronSecret' => $this->t('Cron Secret Word'),
			'timeZone' => $this->t('Default Time Zone'),
			'publicAccess' => $this->t('Visitor Access'),
			'maxCacheDuration' => $this->t('Maximum Cache Duration'),
			'systemEmail' => $this->t('Site "no-reply" Email'),	
			'contactEmail' => $this->t('Site "Contact Us" Email'),	
			'newsletterEmail' => $this->t('Newsletter Email'),	
			'htmlEmails' => $this->t('Send HTML Emails'),	
			'keywords' => $this->t('Keywords (meta tag)'),
			'description' => $this->t('Description (meta tag)'),
			'poweredBy' => $this->t('"Powered By" Notice'),
			'uploadWidget' => $this->t('Use Upload Widget'),
			
			'mailPriority' => $this->t('Email Priority'),
		    'mailCharSet' => $this->t('CharSet of the Message'),
		    'mailEncoding' => $this->t('Encoding of the Message'),
		    'mailMailer' => $this->t('Method to Send Mail'),
		    'mailSendmail' => $this->t('Path of the Sendmail Program'),
		    'mailHost' => $this->t('SMTP Hosts'),
		    'mailPort' => $this->t('SMTP Server Port'),
		    'mailSMTPSecure' => $this->t('SMTP Connection Prefix'),
		    'mailSMTPAuth' => $this->t('SMTP Authentication'),
		    'mailUsername' => $this->t('SMTP Username'),
		    'mailPassword' => $this->t('SMTP Password'),
		    'mailTimeout' => $this->t('SMTP Server Timeout'),
		);
	}
}