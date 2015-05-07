<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class Cryptography {
	
	private $salt = "5^tA_?Jx5je\)+6q&_k(W_Ft|_2s43__3-C6/3/8H\_iC_-+Z";	
	private $hash_salt = "=2-Sc6:=Fe-W:492-77_t^9+-'3Â¤3-{-Klm<5_>f3*h\B4_-_-";	
	private $url_salt = "t-PB0@_}n'_/_I-_._OQ-~4PmT¤f§E`_`oY--n5_/Z__y20tM4";	
	private $url_array = array(2,3,5,7,11,13,17,19,23,29,31,37,41,43,47,53,59,61,67,71,73,79,83,89,97,101,103,107,109,113,127,131,137,139,149,151,157,163,167,173,179,181,191,193,197,199,211,223,227,229,233,239,241,251,257,263,269,271,277,281,283,293,307,311,313,317,331,337,347,349,353,359,367,373,379,383,389,397,401,409,419,421,431,433,439,443,449,457,461,463,467,479,487,491,499,503,509,521,523,541);	
	private $url_shift = array('y','a','q','1','Y','x','s','w','2','c','d','A','Q','X','S','W','C','D','E','e','3','v','f','r','4','V','F','R','B','G','T','N','b','g','t','5','H','Z','M','J','U','K','I','L','O','n','h','z','6','m','j','u','P','7','k','i','8','l','o','9','p','0');	
	private $table = array('');	
	private $length = array('MD'=>32);
	
	public function encrypt($string) {
		if(strlen($string) == 0)
			return '';
		$enc_salt = md5($this->salt);
		$xord = $this->urlsafe_base64encode($this->simple($string,$enc_salt));
		$encrypted = "";
		$xor_len = strlen($xord);
		$xord = str_split($xord);
		$pos = rand()%$xor_len;
		for($i=0;$i<$xor_len;$i++) {
			$encrypted .= $xord[$i];
			if($i == $pos ) {
				$encrypted .= substr($enc_salt,0,$this->length['MD']/2);	
			}
		}
		return $encrypted;
	}
	
	public function decrypt($string) {
		if(strlen($string) == 0)
			return '';
		$enc_salt = md5($this->salt);
		$string = str_replace(substr($enc_salt,0,$this->length['MD']/2),"",$string);
		$string = $this->urlsafe_base64decode($string);
		$plain = $this->simple($string,$enc_salt);
		return $plain;
	}
	
	function urlsafe_base64encode($string) {
		$data = base64_encode($string);
		$data = str_replace(array('+','/','='),array('-','_','.'),$data);
		return $data;
	}
	
	function urlsafe_base64decode($string) {
		$data = str_replace(array('-','_', '.'),array('+','/','='),$string);
		return base64_decode($data);
	}
	
	public function simple($string, $key)  {  
		$string_len = strlen($string);  
		$key_length = strlen($key);  
		for( $i = 0; $i < $string_len; $i++)  
		{  
			$position = $i % $key_length;  
			$replace = ord(substr($string,$i,1)) ^ ord(substr($key,$position,1));  
			$string[$i] = chr($replace);  
		}  
		return $string;
    }
    
    public function hash($string) {
        return substr(hash('sha512',$string.$this->hash_salt),0,156);
    }
    
    public function passwordHash($string1, $string2) {
        return hash("sha512", $string1).hash("ripemd256",$string1).hash("ripemd320",$string2).hash("sha256",$string1);
    }
    
    public function encodeParams($params) {
    	
    }
}
$crypt = new Cryptography();
?>