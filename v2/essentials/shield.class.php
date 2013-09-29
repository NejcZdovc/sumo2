<?php 
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class Shield {
	function __construct() {
		global $crypt, $db;
		if($db->is('shield')) {
			$encData = $this->findPlus($_GET['shield']);
			$data = AESDecryptCtr($encData, "h39oyMN9cXzKT7loxCzYIUgD4uyHt9Fvccigc39GXpTjlAfkAlPegh3lnAIqJRDnAmJwc91WtwPHSs", 256);
			$params = explode("&",$data);
			foreach($params as $param) {
				$value = explode("=",$param);
				$_POST[$value[0]] = $value[1];
				$_REQUEST[$value[0]] = $value[1];
			}
			unset($_POST['shield']);
		}
	}
	
	private function removePlus($data) {
		return str_replace("+","!PLUS!",$data);	
	}
	
	private function findPlus($data) {
		return str_replace("!PLUS!","+",$data);	
	}
	
	public function protect($params) {
		return "shield=".$this->removePlus(AESEncryptCtr($params, "h39oyMN9cXzKT7loxCzYIUgD4uyHt9Fvccigc39GXpTjlAfkAlPegh3lnAIqJRDnAmJwc91WtwPHSs", 256));
	}
}

$shield = new Shield();
?>
