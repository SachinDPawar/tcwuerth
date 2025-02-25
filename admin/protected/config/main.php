<?php
date_default_timezone_set('Asia/Calcutta');

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'RFI SYSTEM',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.controllers.*', 
		'ext.PDFMerger.*',
			'ext.YiiMailer.YiiMailer',
		'ext.*',
		
		//'application.modules.auditTrail.models.AuditTrail',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		//'auditTrail'=>array(),
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'sachin',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		
	),

	// application components
	'components'=>array(
'qrcode' => array(
    'class' => 'ext.qrcode.QRCode',
),


'JWT' => array(
        'class' => 'ext.jwt.JWT',
        'key' => 'TCLIMSSACHIN2023',
    ),
	
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'class' => 'EWebUser',
		),

		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
			
			
				    //this 3 line code for using directly path localhost/apptiapi/gii
			'gii'=>'gii',
			'gii/<controller:\w+>'=>'gii<controller>',
			'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
			
				'post/<id:\d+>/<title:.*?>'=>'post/view',
        'posts/<tag:.*?>'=>'post/index',
        // REST patterns
		/*--used---*/
		array('api/list', 'pattern'=>'api/<model:\w+>/<pl:\w+>/<pn:\w+>', 'verb'=>'GET'),
        array('api/list', 'pattern'=>'api/<model:\w+>', 'verb'=>'GET'),
        array('api/view', 'pattern'=>'api/<model:\w+>/<id:\w+>', 'verb'=>'GET'),
		
        array('api/update', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
        array('api/delete', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
        array('api/create', 'pattern'=>'api/<model:\w+>', 'verb'=>'POST'),
	    array('api/login', 'pattern'=>'api/login/<model:\w+>', 'verb'=>'POST'),//for login 
			//array('api/email', 'pattern'=>'api/email/<model:\w+>', 'verb'=>'POST'),//for email
			
		/*--not used---*/	
		array('adminapi/list', 'pattern'=>'adminapi/<model:\w+>', 'verb'=>'GET'),
        array('adminapi/view', 'pattern'=>'adminapi/<model:\w+>/<id:\w+>', 'verb'=>'GET'),
        array('adminapi/update', 'pattern'=>'adminapi/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
        array('adminapi/delete', 'pattern'=>'adminapi/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
        array('adminapi/create', 'pattern'=>'adminapi/<model:\w+>', 'verb'=>'POST'),	
		/*----coat---*/
		
			array('coatapi/list', 'pattern'=>'coatapi/<model:\w+>/<pl:\w+>/<pn:\w+>', 'verb'=>'GET'),
		array('coatapi/list', 'pattern'=>'coatapi/<model:\w+>', 'verb'=>'GET'),
        array('coatapi/view', 'pattern'=>'coatapi/<model:\w+>/<id:\w+>', 'verb'=>'GET'),
        array('coatapi/update', 'pattern'=>'coatapi/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
        array('coatapi/delete', 'pattern'=>'coatapi/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
        array('coatapi/create', 'pattern'=>'coatapi/<model:\w+>', 'verb'=>'POST'),
		
		/*--used---*/
		array('certapi/list', 'pattern'=>'certapi/<model:\w+>/<pl:\w+>/<pn:\w+>', 'verb'=>'GET'),
		array('certapi/list', 'pattern'=>'certapi/<model:\w+>', 'verb'=>'GET'),
        array('certapi/view', 'pattern'=>'certapi/<model:\w+>/<id:\w+>', 'verb'=>'GET'),
        array('certapi/update', 'pattern'=>'certapi/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
        array('certapi/delete', 'pattern'=>'certapi/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
        array('certapi/create', 'pattern'=>'certapi/<model:\w+>', 'verb'=>'POST'),
		
		 array('certpdf/view', 'pattern'=>'certpdf/<model:\w+>/<id:\w+>', 'verb'=>'GET'),

		/*--used---*/
		array('settingapi/list', 'pattern'=>'settingapi/<model:\w+>/<pl:\w+>/<pn:\w+>', 'verb'=>'GET'),
		array('settingapi/list', 'pattern'=>'settingapi/<model:\w+>', 'verb'=>'GET'),
        array('settingapi/view', 'pattern'=>'settingapi/<model:\w+>/<id:\w+>', 'verb'=>'GET'),
        array('settingapi/update', 'pattern'=>'settingapi/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
        array('settingapi/delete', 'pattern'=>'settingapi/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
        array('settingapi/create', 'pattern'=>'settingapi/<model:\w+>', 'verb'=>'POST'),	
		/*--not used---*/
		// array('testapi/list', 'pattern'=>'testapi/<model:\w+>', 'verb'=>'GET'),
        // array('testapi/view', 'pattern'=>'testapi/<model:\w+>/<id:\w+>', 'verb'=>'GET'),
        // array('testapi/update', 'pattern'=>'testapi/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
        // array('testapi/delete', 'pattern'=>'testapi/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
        // array('testapi/create', 'pattern'=>'testapi/<model:\w+>', 'verb'=>'POST'),	
		/*--used---*/
		
		array('importapi/list', 'pattern'=>'importapi/<model:\w+>', 'verb'=>'GET'),
        array('importapi/view', 'pattern'=>'importapi/<model:\w+>/<id:\w+>', 'verb'=>'GET'),
        array('importapi/update', 'pattern'=>'importapi/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
        array('importapi/delete', 'pattern'=>'importapi/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
        array('importapi/create', 'pattern'=>'importapi/<model:\w+>', 'verb'=>'POST'),
		
		
		array('newapi/list', 'pattern'=>'newapi/<model:\w+>/<pl:\w+>/<pn:\w+>', 'verb'=>'GET'),
        array('newapi/list', 'pattern'=>'newapi/<model:\w+>', 'verb'=>'GET'),
        array('newapi/view', 'pattern'=>'newapi/<model:\w+>/<id:\w+>', 'verb'=>'GET'),
		
        array('newapi/update', 'pattern'=>'newapi/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
        array('newapi/delete', 'pattern'=>'newapi/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
        array('newapi/create', 'pattern'=>'newapi/<model:\w+>', 'verb'=>'POST'),
			
        // Other controllers
        '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		

		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/database.php'),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),

	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);
