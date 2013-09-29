<?php 
if( !defined( '_VALID_MIX' ) && !defined( '_VALID_ETT' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}

class Shield {	
	function __construct() {		
		if(isset($_GET['shield'])) {
			$encData = $this->findPlus($_GET['shield']);
			$data = AESDecryptCtr($encData, "h39oyMN9cXzKT7loxCzYIUgD4uyHt9Fvccigc39GXpTjlAfkAlPegh3lnAIqJRDnAmJwc91WtwPHSs", 256);
			$params = explode("&",$data);
			foreach($params as $param) {
				$value = explode("=",$param);
				$_REQUEST[$value[0]] = $value[1];
				$_GET[$value[0]] = $value[1];
			}
			unset($_GET['shield']);
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
	
	public function checkIP() {	
		global $user, $db, $globals;	
		$allow=$db->rows($db->query('SELECT cms_domains_ips.ID FROM cms_domains_ips, cms_domains WHERE type="0" AND domainID=cms_domains.ID AND cms_domains.name="'.str_replace("www.", "", $_SERVER["SERVER_NAME"]).'" AND cms_domains_ips.value="'.$_SERVER['REMOTE_ADDR'].'"'));
		if($allow>0) {
			header("Location: ".SITE_FOLDER."/block/");
			exit();
		}
		if($_SERVER["REQUEST_URI"]!="/" && $_SERVER["REQUEST_URI"]!="/index.php")
			return;
			
		$file = "./ip/".$user->IP;
		if(!file_exists($file)) {
			if(!file_exists($file)) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "http://api.codehelper.io/ips/?php&ip=".$user->IP);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HEADER, false);
				$json = curl_exec($ch);
				curl_close($ch);
				
				$data = json_decode($json);
			} else {
				$json = file_get_contents($file);
			}
			$f = fopen($file,"w+");
			fwrite($f,$json);
			fclose($f);
		} else {
			$json = file_get_contents($file);
		}
		$json = json_decode($json);
		$CountryName="";
		if(isset($json->CountryName))
			$CountryName=$json->CountryName;
		else
			$CountryName=$json->countryName;
		if($globals->domainParent=="-1") {
			$found=$db->get($db->query('SELECT cms_domains.name FROM cms_domains_countries, cms_domains WHERE (cms_domains.parentID="'.$globals->domainID.'" OR cms_domains.ID="'.$globals->domainID.'") AND cms_domains_countries.domainID=cms_domains.ID AND cms_domains_countries.value="'.$CountryName.'"'));
		} else {			
			$found=$db->get($db->query('SELECT cms_domains.name FROM cms_domains_countries, cms_domains WHERE (cms_domains.parentID="'.$globals->domainParent.'" OR cms_domains.ID="'.$globals->domainParent.'") AND cms_domains_countries.domainID=cms_domains.ID AND cms_domains_countries.value="'.$CountryName.'"'));
		}	
		if(isset($found['name']) && $found['name']!=$globals->domainName)
			header("Location: http://".$found['name']);
	}
}

$shield = new Shield();
?>