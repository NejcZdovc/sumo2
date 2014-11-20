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

	public function checkFull($file=false) {
		global $session, $lang, $db, $user;

		$return=$session->isLogedIn();
		if($return) {
			$return=$this->checkURL();
			if($return) {
				$return=$this->checkFile($file);
			}
			if(!$return) {
				$db->query('UPDATE cms_state SET state="error" WHERE userID="'.$user->ID.'"');
				echo $lang->MOD_261;
				exit();
			}
		} else {
			redirect_to('../login/');
			echo $lang->MOD_261;
			exit();
		}

	}

	public function checkMin() {
		global $session, $lang, $db, $user;
		$return=$session->isLogedIn();
		if($return) {
			$return=$this->checkURL();
			if(!$return) {
				$db->query('UPDATE cms_state SET state="error" WHERE userID="'.$user->ID.'"');
				echo $lang->MOD_261;
				exit();
			}
		} else {
			redirect_to('../login/');
			echo $lang->MOD_261;
			exit();
		}
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

	public function checkFile($file=false) {
		global $db, $user, $lang;
		$return=false;
		if(!$file) {
            $included_files=get_included_files();
			$fileArray=explode("v2".DS, $included_files[0]);
			if(isset($fileArray[1])) {
				$file=$fileArray[1];
			}
		}

		$file=str_replace(array("\\", "/"), "@", $file);

		$result=$db->get($db->query('SELECT permission, enabled FROM cms_user_groups_permissions WHERE file="'.$db->filterVar($file).'" '));
		if($result) {
			if($result['enabled']=="1" && (int)$result['permission']>0) {
				return true;
			}
		}
		return false;

	}
}

$security = new Security(false);
$sec =& $security;
?>