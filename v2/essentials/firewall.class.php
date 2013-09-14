<?php 
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class Firewall {
	function __construct() {
		$fileHandle = @fopen(SITE_ROOT.DS.ADMIN_ADDR.DS."logs/ip.log","r");
		$clientIp = $this->getIP();
		if($fileHandle) {
			while(!feof($fileHandle)) {
				$fileEntry = fgets($fileHandle,1024);
				$ip = explode("]",$fileEntry);
				if(count($ip) == 2) {
					if($clientIp == trim($ip[1])) {
						exit;
					}
				}
			}
			fclose($fileHandle);
		} else {
			error_log('File not found.');
		}
	}
	
	public function addBlockedIp($ip) {
		$fileHandle = @fopen(SITE_ROOT.DS.ADMIN_ADDR.DS."logs/ip.log","a");
		if($fileHandle) {
			date_default_timezone_set("GMT");
			fwrite($fileHandle,"[".date(DATE_RFC822)."] ".$ip."\n");
			fclose($fileHandle);
		} else {
			error_log('File not found.');
		}
	}
	
	public function getIP() {
		if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		} else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}
}

$firewall = new Firewall();
?>