<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class Security {
	function __construct() {
		ini_set('display_errors',0);
		ini_set('error_reporting',E_ALL);
		ini_set('log_errors',1);
		ini_set('error_log',SITE_ROOT.DS.ADMIN_ADDR.DS.'logs/error.log');
		ini_set('allow_url_fopen',0);
		ini_set('allow_url_include',0);
		ini_set('session.cookie_httponly',true);
	}
	
	public function checkURL() {
		if(IS_AJAX) {
			return true;
		} else if($_SERVER['REQUEST_URI']=='/v2/' || $_SERVER['REQUEST_URI']=='/v2/#' || $_SERVER['REQUEST_URI']=='/v2/index.php' || $_SERVER['REQUEST_URI']=='/v2/index.php#' || $_SERVER['REQUEST_URI']=='/v2/index.php##') {
			return true;
		}
		else {
			error_log("Permission denied (AJAX): ".$_SERVER['REQUEST_URI']);
			header( 'HTTP/1.0 404 Not Found');
			header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/v2/');
			return false;
		}
	}	
}

$security = new Security(false);
$sec =& $security;
?>