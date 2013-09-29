<?php
	date_default_timezone_set('Europe/Ljubljana');
	//Global
	define('DS',DIRECTORY_SEPARATOR);
	define('SITE_ROOT',$_SERVER['DOCUMENT_ROOT']);
	define('__ENCODING__', 'latin');

	//Database
    define('__DB_SERVER__','localhost');
    define('__DB_USER__','rcuser');
    define('__DB_PASSWORD__','48uH_spu');
    define('__DB_DATABASE__','3zsistemi_rc');
	
	//FTP permissions
	define('PER_FILE', 0644);
	define('PER_FOLDER', 0755);
	
	//Site tree
	define('ADMIN_ADDR','v2');
	$folder=__FILE__;
	$folder=str_replace($_SERVER['DOCUMENT_ROOT'], '', $folder);
	$folder=str_replace('/v2/configs/settings.php', '', $folder);
	define('SITE_FOLDER',$folder);
?>