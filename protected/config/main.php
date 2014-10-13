<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',

	// preloading 'log' component
	'preload'=>array('log', 'file'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),
	'defaultController' => 'base',

	// application components
	'components'=>array(
		'db'=>array (
			'emulatePrepare' => true,
			'charset' => 'utf8',
			'enableParamLogging' => true,      
			'schemaCachingDuration' => 3600,
		),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'class'=>'UWebUser',
			'loginUrl'=>'/',
		),
		'errorHandler'=>array(
			// use 'system/error' action to display errors
            'errorAction'=>'system/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, notice',
				),
				// uncomment the following to show log messages on web pages
				/**
				array(
					'class'=>'CWebLogRoute',
				),
				//*/
			),
		),
		'themeManager'=>array(
			'class' => 'UThemeManager',
		),
    	'authManager' => array(
    		'class' => 'UAuthManager',
    		'defaultRoles' => array('guest'),
    		'authFile' => dirname(__FILE__).DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'auth.php',
    	),
    	'file' => array(
    		'class' => 'uniprogy.extensions.file.CFile'
    	),
    	'workletManager' => array(
    		'class' => 'UWorkletManager'
    	),
    	'coreMessages' => array(
    		'class' => 'UPhpMessageSource',
    		'forceTranslation' => true,
    	),
    	'messages' => array(
    		'class' => 'UPhpMessageSource',
    		'forceTranslation' => true,
    	),
    	'storage' => array(
    		'class' => 'UStorage',
    	),
    	'request' => array(
    		'class' => 'UHttpRequest'
    	),
    	'clientScript' => array(
    		'class' => 'UClientScript'
    	),
    	'cache' => array(
    		'class' => 'system.caching.CDummyCache'
			//'class'=>'system.caching.CMemCache'
    	),
    	'mailer' => array(
    		'class' => 'UMailer'
    	),
	),
);