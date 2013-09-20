<?php
	date_default_timezone_set('Europe/Ljubljana');
	//Global
	define('DS',DIRECTORY_SEPARATOR);
	define('SITE_ROOT',$_SERVER['DOCUMENT_ROOT']);

	//Database
    define('DB_SERVER','localhost');
    define('DB_USER','rcuser');
    define('DB_PASSWORD','48uH_spu');
    define('DB_DATABASE','3zsistemi_rc');
	
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