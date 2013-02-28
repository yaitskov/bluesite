<?php

$params = parse_ini_file("/etc/bluesite.ini");
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'My Console Application',
    	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'components'=>array(
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=blue',
			'emulatePrepare' => true,
			'username' => $params['dbuser'],
			'password' => $params['dbpass'],
			'charset' => 'utf8',
			'tablePrefix' => 'tbl_',
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
    'params'=>array_merge(
        require(dirname(__FILE__).'/params.php'),        
        $params),
);

?>