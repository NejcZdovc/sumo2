<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class User
{	
	public $id;
	public $username;
	public $mail;
	public $permission;
	public $name;
	private $access;
	
	function __construct($id) {
		global $db;
		$id=$db->filterVar($id);
		$this->id = $id;
		$results = $db->get($db->query("SELECT username,email,GroupID,name FROM cms_user WHERE ID='".$this->id."'"));
		$this->username = $results['username'];
		$this->email = $results['email'];
		$this->permission = $results['GroupID'];
		$permResult = $db->get($db->query("SELECT access FROM cms_user_groups WHERE ID='".$this->permission."'"));
		$this->access = unserialize(urldecode($permResult['access']));
		$this->name = $results['name'];
		$this->groupID = $results['GroupID'];
		$results = $db->get($db->query("SELECT * FROM cms_user_settings WHERE cms_user_settings.UserID='".$this->id."'"));
		if($results) {
			foreach($results as $key => $value) {
				$this->{$key} = $value;
			}
		}
		
		$results = $db->get($db->query("SELECT name AS domainName FROM cms_domains WHERE cms_domains.ID='".$this->domain."'"));
		if($results) {
			foreach($results as $key => $value) {
				$this->{$key} = $value;
			}
		}
	}
	
	public function checkLang() {
		global $db;
		$result = $db->get($db->query("SELECT * FROM cms_language_front WHERE enabled='1'"));
		if($result) {
			$result = $db->get($db->query("SELECT cms_user_settings.ID FROM cms_domains, cms_user_settings WHERE cms_user_settings.userID='".$this->id."' AND cms_domains.ID=cms_user_settings.domain"));
			if($result) {
				return "ok";
			} else {
				return "domain";
			}
		} else {
			return "lang";
		}
	}

	public function isAuth($id,$level = 666) {
		if(array_key_exists($id,$this->access)) {
			if($level === 666) {
				return true;	
			} else {
				if($this->access[$id] === $level) {
					return true;	
				} else {
					return false;	
				}
			}
		} else {
			return false;	
		}
	}
	
	public function getAuth($id) {
		if(array_key_exists($id,$this->access)) {
			return $this->access[$id];
		} else {
			return 0;	
		}
	}
	
	public static function authenticate($username = '', $password = '')
	{
		global $db;
		global $crypt;
		
		$password = $crypt->passwordHash($password,$username);
		$username = $db->filterVar($username);
		
		$results = $db->get($db->query("SELECT ID,GroupID FROM cms_user WHERE username='".$username."' AND pass ='".$password."' AND status='N' AND enabled='1' AND GroupID!='3'"));
		if($results)
		{
			$new_results = $db->get($db->query("SELECT ID FROM cms_user_groups WHERE ID='".$results['GroupID']."' AND enabled='1'"));
			if($new_results) {
				$domainID=$db->get($db->query("SELECT ID, parentID FROM cms_domains WHERE name='".str_replace("www.", "", getDomain())."'"));
				if($domainID['parentID']!="-1") {
					$domainID=$db->get($db->query("SELECT ID, parentID FROM cms_domains WHERE ID='".$domainID['parentID']."'"));					
				}
				$num=$db->rows($db->query("SELECT ID FROM cms_domains_ids WHERE type='group' AND domainID='".$domainID['ID']."' AND elementID='".$results['GroupID']."'"));			
				if($num>0) {
					return $results['ID'];	
				} else {
					$num=$db->rows($db->query("SELECT ID FROM cms_domains WHERE alias='0'"));			
					if($num==0 && $results['GroupID']=='1') {
						return $results['ID'];	
					} else {						
						return false;
					}
					return false;
				}
			} else {
				return false;
			}
		}
		else
		{
			return false;	
		}
	}
	
	public static function updateVisit($id) {
		global $db;
		$id = $db->filterVar($id);
		$query = $db->query("UPDATE cms_user SET visit='".date("Y-m-d H:i:s")."' WHERE ID='".$id."'");
	}
	
	public function langshort($id) {
		global $db;
		$id = $db->filterVar($id);
		$query = $db->get($db->query("SELECT short FROM cms_language_front WHERE ID='".$id."'"));
		return $query['short'];
	}
	
	public function langShortBack($id) {
		global $db;
		$id = $db->filterVar($id);
		$query = $db->get($db->query("SELECT short FROM cms_language WHERE ID='".$id."'"));
		return $query['short'];
	}
}

if($session->isLogedIn()) {
	$user = new User($session->getId()); 
}
?>