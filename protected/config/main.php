<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Востокгеофизика :: Красноярск',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'geo1',
            /*'generatorPaths'=>array(
                'ext.gtc',   // Gii Template Collection
            ),*/
			'ipFilters'=>array('127.0.0.1','::1'),
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			'allowAutoLogin'=>true,
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,
			'rules'=>array(
				'' => 'site/index',
                '<action:(contact|login|logout|vacancies|sitemap)>' => 'site/<action>',
                // Правила для генератора Gii
                'gii'=>'gii',
                'gii/<controller:\w+>'=>'gii/<controller>',
                'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
                // Правила для редактирования новостей
                'news'=>'news',
                'news/<controller:\w+>'=>'news/<controller>',
                'news/<controller:\w+>/<action:\w+>'=>'news/<controller>/<action>',
                // Правила для редактирования фотографий
                'photo'=>'photoCategory',
                'photo/<controller:\w+>'=>'photoCategory/<controller>',
                'photo/<controller:\w+>/<action:\w+>'=>'photoCategory/<controller>/<action>',
                // Правила для редактирования файлов фотографий
                'file'=>'photo',
                'file/<controller:\w+>'=>'photo/<controller>',
                'file/<controller:\w+>/<action:\w+>'=>'photo/<controller>/<action>',
                // Все остальные страницы
                '<page:\w+>' => 'site/index',
                //'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				//'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				//'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=geofiz',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
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
        'clientScript'=>array(
            'scriptMap'=>array(
                'jquery.js'=>false,
            ),
        ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail' => 'webmaster@example.com',
        'uploadPath' => 'C:/Web/xampp/htdocs/geofiz/uploads',
	),
);