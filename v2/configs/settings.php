<?php
	date_default_timezone_set('Europe/Ljubljana');
	//Global
	define('DS',DIRECTORY_SEPARATOR);
	define('SITE_ROOT',$_SERVER['DOCUMENT_ROOT']);
	define('__ENCODING__', 'latin');

	//Database
    define('__DB_SERVER__','localhost');
    define('__DB_USER__','root');
    define('__DB_PASSWORD__','');
    define('__DB_DATABASE__','3zsistemi_rc');
	
	//FTP permissions
	define('PER_FILE', 0644);
	define('PER_FOLDER', 0755);
	
	//Site tree
	define('ADMIN_ADDR','v2');
	$root=$_SERVER['DOCUMENT_ROOT'];
	if(DS=="\\") {
		$root=str_replace('/', '\\', $root);
	}
	$folder=str_replace($root, '', __DIR__);
	$folder=str_replace(DS.'v2'.DS.'configs', '', $folder);
	
	define('SITE_FOLDER',$folder);
?>