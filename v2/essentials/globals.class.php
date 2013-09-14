<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class Globals
{	
	function __construct() {
		global $database, $user;
		if(isset($user->domain)) {
			$query = $database->query("SELECT * FROM cms_global_settings WHERE domain='".$user->domain."'");
			if($database->rows($query) > 0) {
				$results = $database->get($query);
				$i = 0;
				foreach($results as $key => $value) {
					$i++;
					if($i%2 == 0) {
						$this->{$key} = $value;
					}
				}
			}
		}
		$query = $database->query("SELECT version, FTP_user, FTP_pass, FTP_url, FTP_port, welcome FROM cms_sumo_settings WHERE ID='1'");
		if($database->rows($query) > 0) {
			$results = $database->get($query);
			$i = 0;
			foreach($results as $key => $value) {
				$i++;
				if($i%2 == 0) {
					$this->{$key} = $value;
				}
			}
		}
	}
}

$globals = new Globals();
?>