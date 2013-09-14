<?php
if( !defined( '_VALID_MIX' ) && !defined( '_VALID_ETT' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}

class Session {	
    private $logedInBack = false;
	private $logedInFront = false;

	function __construct() {			
	    global $crypt;
	    global $cookie;
	    session_name($crypt->hash('session'.$cookie->agent));
	    if(!$cookie->isCSC('session')) {
	        session_id(hash('sha512',uniqid(microtime(),true)));
	    }
	    session_start();
	    $this->checkUserBack();
		$this->checkUserFront();
	}
	
	private function checkUserBack() {
	    global $cookie;
	    if($cookie->isCSC('user')) {
	        $this->logedInBack = true;
	        $_SESSION['user'] = $cookie->getCSC('user');
	    } else if($this->isSession('user')) {
	        $this->logedInBack = true;
	    }
		else
			$this->logedInBack = false;
	}
	
	private function checkUserFront() {
	    global $cookie;
	    if($cookie->isCSC('userFront')) {
	        $this->logedInFront = true;
	        $_SESSION['userFront'] = $cookie->getCSC('userFront');
	    } else if($this->isSession('userFront')) {
	        $this->logedInFront = true;
	    }
		else
			$this->logedInFront = false;
	}
	
	public function isSession($key) {
	    if(isset($_SESSION[$key])) {
	        return true;
	    } else {
	        return false;
	    }
	}
	
	public function login($id) {
	    global $cookie;
	    $_SESSION['userFront'] = $id;
	    $cookie->setCSC('userFront',$id.'',1,"/");
	    $this->logedIn = true;
	}
	
	public function isLogedInFront() {
		$this->checkUserFront();
	    return $this->logedInFront;
	}
	
	public function isLogedInBack() {
		$this->checkUserBack();
	    return $this->logedInBack;
	}
	
	public function getIdFront() {
		return $_SESSION['userFront'];
	}
	
	public function getIdBack() {
		return $_SESSION['user'];
	}
}
$session = new Session();
?>