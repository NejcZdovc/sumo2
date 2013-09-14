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
		global $database;
		$id=mysql_escape_string($id);
		$this->id = $id;
		$query = $database->query("SELECT username,email,GroupID,name FROM cms_user WHERE ID='".$this->id."'");
		$results = $database->get($query);
		$this->username = $results['username'];
		$this->email = $results['email'];
		$this->permission = $results['GroupID'];
		$permQuery = $database->query("SELECT access FROM cms_user_groups WHERE ID='".$this->permission."'");
		$permResult = $database->get($permQuery);
		$this->access = unserialize(urldecode($permResult['access']));
		$this->name = $results['name'];
		$query = $database->query("SELECT *  FROM cms_user_settings WHERE cms_user_settings.UserID='".$this->id."'");
		if($database->rows($query) > 0) {
			$results = $database->get($query);
			$i = 0;
			foreach($results as $key => $value) {
				$i++;
				if($i%2 == 0) {
					$this->{$key} = $value;
				}
			}
		}
		$query = $database->query("SELECT name AS domainName  FROM cms_domains WHERE  cms_domains.ID='".$this->domain."'");
		if($database->rows($query) > 0) {
			$results = $database->get($query);
			$i = 0;
			foreach($results as $key => $value) {
				$i++;
				if($i%2 == 0) {
					$this->{$key} = $value;
				}
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
		global $database;
		global $crypt;
		
		$password = $crypt->passwordHash($password,$username);
		$username = mysql_real_escape_string($username);
		
		$query = $database->query("SELECT ID,GroupID FROM cms_user WHERE username='".$username."' AND pass ='".$password."' AND status='N' AND enabled='1' AND GroupID!='3'");
		$results = $database->get($query);
		if($results)
		{
			$new_query = $database->query("SELECT ID FROM cms_user_groups WHERE ID='".$results['GroupID']."' AND enabled='1'");
			$new_results = $database->get($new_query);
			if($new_results) {
				$domainID=$database->get($database->query("SELECT ID, parentID FROM cms_domains WHERE name='".str_replace("www.", "", getDomain())."'"));
				if($domainID['parentID']!="-1") {
					$domainID=$database->get($database->query("SELECT ID, parentID FROM cms_domains WHERE ID='".$domainID['parentID']."'"));					
				}
				$num=$database->rows($database->query("SELECT ID FROM cms_domains_ids WHERE type='group' AND domainID='".$domainID['ID']."' AND elementID='".$results['GroupID']."'"));			
				if($num>0) {
					return $results['ID'];	
				} else {
					$num=$database->rows($database->query("SELECT ID FROM cms_domains WHERE alias='0'"));			
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
		global $database;
		$id = mysql_real_escape_string($id);
		$query = $database->query("UPDATE cms_user SET visit='".date("Y-m-d H:i:s")."' WHERE ID='".$id."'");
	}
	
	public function langshort($id) {
		global $database;
		$id = mysql_real_escape_string($id);
		$query = $database->get($database->query("SELECT short FROM cms_language_front WHERE ID='".$id."'"));
		return $query['short'];
	}
}

if($session->isLogedIn()) {
	$user = new User($session->getId()); 
}
?>