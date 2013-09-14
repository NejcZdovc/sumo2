<?
	define( '_VALID_MIX', 1 );
	define( '_VALID_ETT', 1 );
	require_once(__DIR__.'/../v2/configs/settings.php');
	ini_set('log_errors',1);
	ini_set('error_log',__DIR__.'/../v2/logs/errorFront.log');
	require_once(__DIR__.'/aes.chris.veness.class.php');	
	require_once(__DIR__.'/xml.class.php');
	require_once(__DIR__.'/database.class.php');
	require_once(__DIR__.'/globals.class.php');
	require_once(__DIR__.'/cryptography.class.php');
	require_once(__DIR__.'/cookie.class.php');
	require_once(__DIR__.'/session.class.php');
	require_once(__DIR__.'/user.class.php');
	require_once(__DIR__.'/shield.class.php');
	require_once(__DIR__.'/template.class.php');
	require_once(__DIR__.'/modules.class.php');
	require_once(__DIR__.'/functions.class.php');
	require_once(__DIR__.'/language.class.php');
	require_once(__DIR__.'/phpmailer.class.php');
	require_once(__DIR__.'/pop3.class.php');
	require_once(__DIR__.'/smtp.class.php');
	require_once(__DIR__.'/fields.class.php');
	if($user->developer=="1") {
		ini_set('display_errors',1);
		ini_set('error_reporting',E_ALL);		
	} else {
		ini_set('display_errors',0);		
	}	
	$shield->checkIP();	
?>