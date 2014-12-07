<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class FTP
{
	private $username;
	private $pass;
	private $url;
	private $connection;

	function __construct() {
		global $globals, $crypt;
		if(isset($globals->FTP_pass)) {
			$this->pass=$crypt->decrypt($globals->FTP_pass);
		}
		if(isset($globals->username)) {
			$this->username=$crypt->decrypt($globals->FTP_user);
		}
		$this->url=$globals->FTP_url;
	}

	public function Connect() {
		$this->connection = ftp_connect($this->url) or die("Couldn't connect to $ftp_server");
		if (@ftp_login($this->connection, $this->username, $this->pass)) {
			return true;
		} else {
			error_log('[FTP] Could not connect to server');
			return false;
		}
	}

	public function Close() {
		if (ftp_close($this->connection) == false) {
			error_log('[FTP] Could not close ftp connection');
		}
	}

	public function ChangePermission($path, $permition) {
		if (ftp_chmod($this->connection, $permition, $path) == false) {
			error_log('[FTP] Could not chmod file: '.$path.' with '.$permition.'');
		}
	}

	public function CreateDir($path, $permition) {
		if (ftp_mkdir($this->connection, $path) == false) {
			error_log('[FTP] Could not create directory '.$path.'');
		}
		else {
			$this->ChangePermission($path, $permition);
		}
	}

	public function SaveFileUrl($url, $file){
		copy($url, $file);
	}
}

$FTP = new FTP();

?>
