<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class Globals
{	
	function __construct() {
		global $db, $user;
		if(isset($user->domain)) {
			$results = $db->get($db->query("SELECT * FROM cms_global_settings WHERE domain='".$user->domain."'"));
			if($results) {
				foreach($results as $key => $value) {
					$this->{$key} = $value;
				}
			}
		}
		$results = $db->get($db->query("SELECT version, FTP_user, FTP_pass, FTP_url, FTP_port, welcome FROM cms_sumo_settings WHERE ID='1'"));
		if($results) {
			foreach($results as $key => $value) {
				$this->{$key} = $value;
			}
		}
	}
}

$globals = new Globals();
?>