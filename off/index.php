<?php 
	define( '_VALID_MIX', 1 );
	define( '_VALID_ETT', 1 );
	require_once('../v2/configs/settings.php');
	ini_set('log_errors',1);
	include_once('../includes/database.class.php');
	include_once('../includes/globals.class.php');
	
	if(is_file($globals->domainName.'/index.php')) {
		include($globals->domainName.'/index.php');
	} else {
		include('default/index.php');		
	}
?>