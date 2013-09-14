<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}

class Cookie {

	public $httpOnly = false;	
	public $agent = null;
	
	function __construct() {
	    $this->agent = empty($_SERVER['HTTP_USER_AGENT'])?'RABBITHOLE':$_SERVER['HTTP_USER_AGENT'];
	}
	
	public function setCookie($key,$value,$time = 2,$path = NULL) {
	    global $crypt;
		setcookie($crypt->hash($key),$crypt->encrypt($value),time()+60*60*$time,$path,NULL,false,$this->httpOnly);
	}
	
	public function getCookie($key) {
	    global $crypt;
		return $crypt->decrypt($_COOKIE[$crypt->hash($key)]);	
	}
	
	public function remCookie($key,$path = NULL) {
	    global $crypt;
		setcookie($crypt->hash($key),'',time()-3600,$path,NULL,false,$this->httpOnly);
	}
	
	public function setCSC($key,$value,$time = 30,$path = NULL) {
	    $this->setCookie($key.$this->agent,$value,$time,$path);
	}
	
	public function remCSC($key,$path = NULL) {
	    $this->remCookie($key.$this->agent,$path);
	}
	
	public function getCSC($key) {
	    return $this->getCookie($key.$this->agent);
	}
	
	public function isCookie($key) {
	    global $crypt;
	    if(isset($_COOKIE[$crypt->hash($key)])) {
	        return true;
	    } else {
	        return false;
	    }
	}
	
	public function isCSC($key) {
	    if($this->isCookie($key.$this->agent)) {
	        return true;
	    } else {
	        return false;
	    }
	}
	
	public function getName($key) {
		global $crypt;
		return $crypt->hash($key.$this->agent);
	}
	
}

$cookie = new Cookie();
?>