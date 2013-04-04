<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Ozip Auto DJ',
		
	'theme'=>'bootstrap',
	'defaultController' => 'nowplaying',	
	

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123456',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
	),

	// application components
	'components'=>array(
		'geoip' => array(
				'class' => 'application.extensions.geoip.CGeoIP',
				// specify filename location for the corresponding database
				'filename' => '/home/martin/public_html/ozip-ices/protected/extensions/geoip/GeoLiteCity.dat',
				// Choose MEMORY_CACHE or STANDARD mode
				'mode' => 'STANDARD',
		),
		'request'=>array(
				'enableCsrfValidation'=>true,
				'csrfTokenName' => 'OZIBOX_CSRF'
		),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'bootstrap'=>array(
				'class'=>'bootstrap.components.Bootstrap',
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
		),
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=ices',
			'emulatePrepare' => true,
			'username' => 'martin',
			'password' => 'martinadi',
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
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'upload_tmp' => '/home/martin/public_html/ozip-ices/upload_tmp/',
		'music_dir' => '/home/martin/public_html/ozip-ices/musics/',
		'nowplaying' => '/home/martin/public_html/ozip-ices/musics/nowplaying.txt',
		'playlist_file' => '/home/martin/public_html/ozip-ices/musics/playlist.txt',
		
		'stat_url' 		=> 'http://admin:martinadi@stream.hosting.ozip.co.id:8000/admin/stats.xml',
		'listener_url' 	=> 'http://admin:martinadi@stream.hosting.ozip.co.id:8000/admin/listclients?mount=/dompetdhuafa',
	),
);