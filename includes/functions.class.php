<?php
if( !defined( '_VALID_MIX' ) && !defined( '_VALID_ETT' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}

class Func
{	
	private $url="";
	private $urlArray="";
	private $lang=0;
	public $langCode="";
	public $priLang=false;
	private $paramArray=array();
	
	function __construct() {
		$this->url=$this->getUrl();
	}
	
	public function getUrl() {
		global $db;
		if(strlen($this->url)>4)
			return $this->url;
		else {		
			$this->url=rawurldecode($db->filterVar($_SERVER['REQUEST_URI']));
			return $this->url;
		}
	}
	
	public function refreshPage($full=true){
		global $db;
		$pageURL = 'http';
		 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $uri="";
		 $pageURL .= "://";
		 if($full)
		 	$uri=$_SERVER["REQUEST_URI"];
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$uri;
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$uri;
		 }
		 header('Location: '.rawurldecode($db->filterVar($pageURL).''));
	}
	
	public function getUrlArray() {
		$this->getUrl();
		$url=explode('?',$this->url);
		$this->url=$url[0];
		$this->urlArray=explode('/', $this->url);
		array_shift($this->urlArray);
		if(count($this->urlArray)>1 && $this->urlArray[count($this->urlArray)-1]=="") {				
			unset($this->urlArray[count($this->urlArray)-1]);
		}
		$this->urlArray=$this->urlArray;
		
		return $this->urlArray;
	}
	
	public function getLang() {
		global $db, $globals;
		if($this->lang!=0) {
			return 	$this->lang;
		}
		
		$this->lang=0;
		$this->getUrlArray();
		$langResult = $db->get($db->query("SELECT ID, short FROM cms_language_front WHERE short='".$db->filterVar($this->urlArray[0])."'"));
		if($langResult['ID'] > 0) {
			$this->priLang=true;
			$this->lang=$langResult['ID'];
			$this->langCode=$langResult['short'];
		} else {
			$langResult = $db->get($db->query("SELECT front_lang FROM cms_global_settings WHERE domain='".$globals->domainID."'"));
			if($langResult['front_lang'] > 0) {
				$langResult = $db->get($db->query("SELECT ID, short FROM cms_language_front WHERE ID='".$langResult['front_lang']."'"));
				$this->lang=$langResult['ID'];
				$this->langCode=$langResult['short'];
			} else {
				$langResult = $db->get($db->query("SELECT value FROM cms_domains_ids WHERE domainID='".$globals->domainID."' AND type='lang' ORDER BY ID"));
				$langResult = $db->get($db->query("SELECT ID,short FROM cms_language_front WHERE short='".$langResult['value']."'"));
				$this->lang=$langResult['ID'];
				$this->langCode=$langResult['short'];
			}
		}	
		return $this->lang;	
	}	
	
	public function getParamArray() {
		global $db;
		$this->paramArray=array();
		if(!$db->is('modulParam'))
			return $this->paramArray;
			
		$params=explode('/', $db->filter('modulParam'));		
		foreach($params as $param) {
			$array=array();
			$temp=explode('-', $param);
			if(count($temp)>1) {
				$array['parameter']=$temp[0];
				if(count($temp)>2) {
					unset($temp[0]);
					$array['value']=implode('-', $temp);
				} else {
					$array['value']=$temp[1];					
				}
			} else {
				$array['value']=$temp[0];
			}
			array_push($this->paramArray, $array);
		}
		
		return $this->paramArray;		
	}
	
	public function getParamFromArray($needle, $array=null) {
		if($array==null) {
			if($this->paramArray==null)
				$array=$this->getParamArray();
			else
				$array=$this->paramArray;
		}
		foreach ($array as $key=>$value) {
		  if (isset($value['parameter']) && $value['parameter']==$needle) {
			 return($array[$key]);
			 break;
		  }
		}
		return null;
	}
	
	public function setParamToUrl($needle, $value, $keep=true) {
		if($this->paramArray==null)
			$array=$this->getParamArray();
		else
			$array=$this->paramArray;
		
		$url="";	
		if(is_array($needle) && is_array($value) && count($needle)==count($value)) {	
			if(count($array)>0) {		
				foreach ($array as $item) {
					if(isset($item['parameter'])) {
						$key=array_search($item['parameter'], $needle);
						if ($key!==false) {
							$url.=$item['parameter']."-".$value[$key].'/';
							array_splice($value, $key, 1);
							array_splice($needle, $key, 1);
						} else if($keep) {
							if(isset($item['parameter']))
								$url.=$item['parameter']."-".$item['value'].'/';
							else
								$url.=$item['value'].'/';
						}
					}
				}
				for($i=0; $i<count($needle); $i++) {
					$url.=$needle[$i]."-".$value[$i].'/';
				}
			} else {
				for($i=0; $i<count($needle); $i++) {
					$url.=$needle[$i]."-".$value[$i].'/';
				}
			}
		} else if(is_string($needle) && (is_string($value) || is_numeric($value))) {			
			$add=false;
			if(count($array)>0) {				
				foreach ($array as $item) {
					if (isset($item['parameter']) && $item['parameter']==$needle) {
						$url.=$item['parameter']."-".$value.'/';
						$add=true;
					} else if($keep) {
						if(isset($item['parameter']))
							$url.=$item['parameter']."-".$item['value'].'/';
						else
							$url.=$item['value'].'/';
					}
				}
				if(!$add)
					$url.=$needle."-".$value.'/';
			} else {
				$url.=$needle."-".$value.'/';
			}
		}	
		return $url;
	}
	
	function validateEmail($email)
	{
	   $isValid = true;
	   $atIndex = strrpos($email, "@");
	   if (is_bool($atIndex) && !$atIndex)
	   {
		  $isValid = false;
	   }
	   else
	   {
		  $domain = substr($email, $atIndex+1);
		  $local = substr($email, 0, $atIndex);
		  $localLen = strlen($local);
		  $domainLen = strlen($domain);
		  if ($localLen < 1 || $localLen > 64)
		  {
			 $isValid = false;
		  }
		  else if ($domainLen < 1 || $domainLen > 255)
		  {
			 // domain part length exceeded
			 $isValid = false;
		  }
		  else if ($local[0] == '.' || $local[$localLen-1] == '.')
		  {
			 // local part starts or ends with '.'
			 $isValid = false;
		  }
		  else if (preg_match('/\\.\\./', $local))
		  {
			 // local part has two consecutive dots
			 $isValid = false;
		  }
		  else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
		  {
			 // character not valid in domain part
			 $isValid = false;
		  }
		  else if (preg_match('/\\.\\./', $domain))
		  {
			 // domain part has two consecutive dots
			 $isValid = false;
		  }
		  else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
		  {
			 // character not valid in local part unless 
			 // local part is quoted
			 if (!preg_match('/^"(\\\\"|[^"])+"$/',
				 str_replace("\\\\","",$local)))
			 {
				$isValid = false;
			 }
		  }
		  if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
		  {
			 // domain not found in DNS
			 $isValid = false;
		  }
	   }
	   return $isValid;
	}
}

$function = new Func();
?>