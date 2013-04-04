<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

	'import'=>array(
			'application.models.*',
			'application.components.*',
			'application.extensions.*',
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
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=ices',
			'emulatePrepare' => true,
			'username' => 'martin',
			'password' => 'martinadi',
			'charset' => 'utf8',
		),
		
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
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