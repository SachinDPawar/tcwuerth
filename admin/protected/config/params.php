<?php
//include(dirname(__FILE__)."/admin/config.php");
$config=require( '../admin/config.php');

// This is the database connection configuration.
return array(


	'base-url'=>$config['baseurl'],
	'imgurl'=>$config['baseurl'].'images/',
		'appname'=>$config['appname'],		
 
			
);
