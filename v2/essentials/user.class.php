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

	function __construct($id) {
		global $db;
		$id=$db->filterVar($id);
		$this->id = $id;
		$this->ID = $id;
		$results = $db->get($db->query("SELECT cu.username, cu.email, cu.GroupID, cu.name, cug.cache, cug.errorLog, cug.dataLog, cug.login FROM cms_user as cu LEFT JOIN cms_user_groups as cug ON cu.GroupID=cug.ID WHERE cu.ID='".$this->id."'"));
		$this->username = $results['username'];
		$this->email = $results['email'];
		$this->permission = $results['GroupID'];
		$this->name = $results['name'];
		$this->groupID = $results['GroupID'];
		$this->cache = $results['cache'];
		$this->errorLog = $results['errorLog'];
		$this->dataLog = $results['dataLog'];
		$this->login = $results['login'];
		$results = $db->get($db->query("SELECT * FROM cms_user_settings WHERE cms_user_settings.UserID='".$this->id."'"));
		if($results) {
			foreach($results as $key => $value) {
				if($key!="ID" && $key!="userID") {
					$this->{$key} = $value;
				}
			}
		}

		$results = $db->get($db->query("SELECT name AS domainName FROM cms_domains WHERE cms_domains.ID='".$this->domain."'"));
		if($results) {
			$this->{'domainName'} = $results['domainName'];
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

	public function isAuth($id, $level=1) {
		global $db;
		$result=$db->get($db->query('SELECT enabled, permission FROM cms_user_groups_permissions WHERE groupID="'.$this->groupID.'" AND objectID="'.$db->filterVar($id).'"'));
		if($result && $result['enabled']=="1") {
			if($result['permission']>=$level) {
				return true;
			}
		}
		return false;
	}

	public function getAuth($id) {
		global $db;
		$result=$db->get($db->query('SELECT enabled, permission FROM cms_user_groups_permissions WHERE groupID="'.$this->groupID.'" AND objectID="'.$db->filterVar($id).'"'));
		if($result && $result['enabled']=="1") {
			return $result['permission'];
		}
		return 0;
	}

	public static function authenticate($username = '', $password = '', $oldUser)
	{
		global $db, $crypt;

		$password = $crypt->passwordHash($password,$username);
		$username = $db->filterVar($username);
		$oldUserID=null;
		$new=false;
		$tempUser=explode("_", $crypt->decrypt($oldUser));
		if($tempUser[0]=="new") {
			$new=true;
			if(!isset($tempUser[1]) || (isset($tempUser[1]) && (time()-$tempUser[1])>600)) {
				return "token";
			}
		} else {
			if(!isset($tempUser[1]) || (isset($tempUser[1]) && (time()-$tempUser[1])>600)) {
				return "token";
			}
			$oldUserID=$tempUser[0];
		}

		$results = $db->get($db->query("SELECT ID, GroupID FROM cms_user WHERE username='".$username."' AND pass ='".$password."' AND status='N' AND enabled='1'"));
		if($results)
		{
			//get group
			$new_results = $db->get($db->query("SELECT ID FROM cms_user_groups WHERE ID='".$results['GroupID']."' AND enabled='1' AND login='1'"));
			if($new_results) {
				//get domain
				$domainID=$db->get($db->query("SELECT ID, parentID FROM cms_domains WHERE name='".str_replace("www.", "", getDomain())."'"));
				if($domainID['parentID']!="-1") {
					$domainID=$db->get($db->query("SELECT ID, parentID FROM cms_domains WHERE ID='".$domainID['parentID']."'"));
				}

				//validate group and domain
				$num=$db->rows($db->query("SELECT ID FROM cms_domains_ids WHERE type='group' AND domainID='".$domainID['ID']."' AND elementID='".$results['GroupID']."'"));
				if($num>0) {
					if(!$new && $oldUserID!=$results['ID']) {
						return "refresh";
					} else {
						return $results['ID'];
					}
				} else {
					$num=$db->rows($db->query("SELECT ID FROM cms_domains WHERE alias='0'"));
					if($num==0 && $results['GroupID']=='1') {
						if(!$new && $oldUserID!=$results['ID']) {
							return "refresh";
						} else {
							return $results['ID'];
						}
					} else {
						return "domain";
					}
					return "domain";
				}
			} else {
				return false;
			}
		} else {
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