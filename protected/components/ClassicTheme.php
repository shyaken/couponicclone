<?php
class ClassicTheme extends UTheme
{	
	public function spaces()
	{
		return array(
			'main' => array(
				'inside',
				'outside',
				'header',
				'menu',
				'content',
				'sidebar',
				'footer',
				'default' => 'content'
			),
			'splash' => array(
				'content',
				'default' => 'content',
			),
			'email' => array(
				'content'
			),
			'print' => array(
				'content'
			),
		);
	}
	
	public function routes()
	{
		return array();
	}
	
	public function worklets()
	{
		return array(
			'base.menu' => array('space' => 'menu'),
			'base.dialog' => array('space' => 'outside'),
		);
	}
	
	public static function getThemeName()
	{
		return 'Classic';
	}
}