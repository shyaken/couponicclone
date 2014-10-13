<?php
class UApp extends UWebApplication
{
	public function getVersion()
	{
		return '1.5.4';
	}
	
	public function getTitle()
	{
		return 'UniProgy Couponic';
	}
	
	public function getAppModules()
	{
		return array(
			'admin',
			'agent',
			'api',
			'base',
			'company',
			'deal',
			'location',
			'payment',
			'payment.paypal',
			'payment.ccdirect',
			'payment.wire',
			'user',
			'subscription',
			'customize',
		);
	}
	
	public function getVersionHistory()
	{
		return array(
			'1.0.0',
			'1.0.1',
			'1.0.2',
			'1.1.0',
			'1.1.1',
			'1.1.2',
			'1.1.3',
			'1.2.0',
			'1.2.1',
			'1.2.2',
			'1.2.3',
			'1.3.0',
			'1.3.1',
			'1.3.2',
			'1.3.3',
			'1.3.4',
			'1.4.0',
			'1.4.1',
			'1.4.2',
			'1.4.3',
			'1.4.4',
			'1.5.0',
			'1.5.1',
			'1.5.2',
			'1.5.3',
			'1.5.4'
		);
	}
}