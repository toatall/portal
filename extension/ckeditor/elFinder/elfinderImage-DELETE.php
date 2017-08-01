<?php

session_start();

require './php/PathGenerator.php';
$pathGenerator = new PathGenerator();


$opts = array(

	// 'debug' => true,
	'roots' => array(
		array(
			'driver'        => 	'LocalFileSystem',           // driver for accessing file system (REQUIRED)
			'path'          =>  $pathGenerator->rootPath . $pathGenerator->pathFile,                 // path to files (REQUIRED)
			'URL'           =>  $pathGenerator->rootUrl . $pathGenerator->pathFile, // URL to files (REQUIRED)
			//'uploadDeny'    => 	array('text/x-php'),                // All Mimetypes not allowed to upload
			'uploadAllow'   => array('image'),// Mimetype `image` and `text/plain` allowed to upload
			//'uploadOrder'   => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
			'accessControl' => 	'access',                  // disable and hide dot starting files (OPTIONAL)
			/*'attributes' => array(
				'pattern' => '/\.(txt|html|php|py|pl|sh|xml|bat|cmd|exe|asp|aspx)$i',
				'read' => false,
				'write' => false,
				'locked' => true,
				'hidden' => true,
			),*/
		),
	),
);

require './php/connector.php';