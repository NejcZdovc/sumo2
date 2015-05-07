<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}

class Session {
    private $logedIn = false;

	function __construct() {
	    global $crypt;
	    global $cookie;
	    session_name($crypt->hash('session'.$cookie->agent));
	    if(!$cookie->isCSC('session')) {
	        session_id(hash('sha512',uniqid(microtime(),true)));
	    }
	    session_start();
	    $this->checkUser();
	}

	private function checkUser() {
	    global $cookie;
	    if($cookie->isCSC('user') && $this->isSession('user')) {
	        $this->logedIn = true;
	        $_SESSION['user'] = $cookie->getCSC('user');
	    } else {
			$this->logedIn = false;
        }
	}

	public function isSession($key) {
	    if(isset($_SESSION[$key])) {
	        return true;
	    } else {
	        return false;
	    }
	}

	public function isLogedIn() {
		$this->checkUser();
	    return $this->logedIn;
	}

	public function login($id) {
	    global $cookie;
	    $_SESSION['user'] = $id;
	    $cookie->setCSC('user',$id.'',1,"/");
	    $this->logedIn = true;
	}

	public function updateLogin($id) {
		global $cookie;
		if($cookie->isCSC('user')) {
			$cookie->setCSC('user',$id.'',1,"/");
			return true;
		} else {
			unset($_SESSION['user']);
			return false;
		}
	}

	public function logout() {
	    global $cookie;
	    unset($_SESSION['user']);
	    if($cookie->isCSC('user')) {
	        $cookie->remCSC('user',DS);
	    }
	}

	public function getId() {
		return $_SESSION['user'];
	}

	public function setFormToken() {
	    $token = uniqid(microtime(),true);
	    $_SESSION['formToken'] = $token;
	    return $token;
	}

	public function getFormToken() {
		if(isset($_SESSION['formToken'])) {
	    	return $_SESSION['formToken'];
        } else {
			return "notfound";
        }
	}
}
$session = new Session();
?>