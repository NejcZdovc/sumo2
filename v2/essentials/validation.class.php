<?php 
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class Validation {
	public function isLength($string,$min=0,$max=50) {
		$len = strlen($string);
		if($min <= $len && $len <= $max) {
			return true;	
		}
		return false;
	}
	
	public function isWord($string) {
		if(preg_match("/^([a-zA-Z ])+$/", $string)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function isUsername($string,$min=0,$max=50) {
		if($this->isLength($string,$min,$max)) {
			if(preg_match("/^([a-zA-Z0-9._])+$/", $string)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function isEmail($data) { 
	   return filter_var($data,FILTER_VALIDATE_EMAIL);
	}
	
	public function changeToFile($data) {
		$data=str_replace('/', ' ', $data); 
		$data=str_replace('\\', ' ', $data); 
		$data=str_replace(':', ' ', $data); 
		$data=str_replace('?', ' ', $data);
		$data=str_replace('*', ' ', $data); 
		$data=str_replace('"', ' ', $data); 
		$data=str_replace('<', ' ', $data); 
		$data=str_replace('>', ' ', $data); 
		$data=str_replace('|', ' ', $data);
		$data=str_replace('%', ' ', $data); 
	   return $data;
	}
	
	public function isNumber($string) {
		return ctype_digit($string);
	}
	
	public function isUrl($data) {
		return filter_var($data,FILTER_VALIDATE_URL);
	}
}

$valid = new Validation();
?>
