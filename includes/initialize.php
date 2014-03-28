<?php
	define( '_VALID_MIX', 1 );
	define( '_VALID_ETT', 1 );
	require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'v2'.DIRECTORY_SEPARATOR.'configs'.DIRECTORY_SEPARATOR.'settings.php');
	ini_set('log_errors',1);
	ini_set('error_log',__DIR__.DS.'..'.DS.'v2'.DS.'logs'.DS.'errorFront_'.str_replace("www.", "", $_SERVER['HTTP_HOST']).'.log');
	require_once(__DIR__.DS.'aes.chris.veness.class.php');	
	require_once(__DIR__.DS.'xml.class.php');
	require_once(__DIR__.DS.'database.class.php');
	require_once(__DIR__.DS.'globals.class.php');
	require_once(__DIR__.DS.'cryptography.class.php');
	require_once(__DIR__.DS.'cookie.class.php');
	require_once(__DIR__.DS.'session.class.php');
	require_once(__DIR__.DS.'user.class.php');
	require_once(__DIR__.DS.'shield.class.php');
	require_once(__DIR__.DS.'template.class.php');
	require_once(__DIR__.DS.'modules.class.php');
	require_once(__DIR__.DS.'functions.class.php');
	require_once(__DIR__.DS.'language.class.php');
	require_once(__DIR__.DS.'phpmailer.class.php');
	require_once(__DIR__.DS.'pop3.class.php');
	require_once(__DIR__.DS.'smtp.class.php');
	require_once(__DIR__.DS.'fields.class.php');
	require_once(__DIR__.DS.'..'.DS.'v2'.DS.'essentials.'.DS.'device.detect.class.php');
	if($user->developer=="1") {
		ini_set('display_errors',1);
		ini_set('error_reporting',E_ALL);		
	} else {
		ini_set('display_errors',0);		
	}	
	if(!is_object($shield)) {
		$shield = new Shield();
	}		
	$shield->checkIP();
?>