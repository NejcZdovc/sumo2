<? 
	define( '_VALID_MIX', 1 );
	define( '_VALID_ETT', 1 );
	require_once('../v2/configs/settings.php');
	ini_set('log_errors',1);
	ini_set('error_log',SITE_ROOT.DS.ADMIN_ADDR.DS.'logs/errorFront.log');
	include_once('../includes/database.class.php');
	include_once('../includes/globals.class.php');
	
	if(is_file($globals->domainName.'/index.php')) {
		include($globals->domainName.'/index.php');
	} else {
		include('default/index.php');		
	}
?>