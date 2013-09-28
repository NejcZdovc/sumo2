<?php
if( !defined( '_VALID_MIX' ) && !defined( '_VALID_ETT' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}

class User
{	
	public $IP;
	
	function __construct($id) {
		global $db;
		$this->{"developer"}="0";
		$this->IP=$this->getRealIP();
		if($id=="-1") {
			return;
		}
		
		$id=$db->filterVar($id);
		$query=$db->get($db->query('SELECT name, email, ID, visit FROM cms_user WHERE ID="'.$id.'"'));
		$this->{'name'}=$query['name'];
		$this->{'email'}=$query['email'];
		$this->{'ID'}=$query['ID'];
		$this->{'visit'}=$query['visit'];
		$query=$db->query('SELECT developer FROM cms_user_settings WHERE UserID="'.$id.'"');
		if($db->rows($query) > 0) {
			$results = $db->get($query);
			$this->{"developer"}=$results['developer'];
		}
		
		$query = $db->query("SELECT * FROM cms_user_aditional WHERE UserID='".$id."'");
		if($db->rows($query) > 0) {
			$results = $db->get($query);
			$i=0;
			foreach($results as $key => $value) {
				$i++;
				if($i%2==0 && $key!="ID" && $key!="userID" && $key!="email" && $key!="name" && $key!="visit") {
					$this->{$key} = $value;
				}
			}
		}
	}
	
	 public function getRealIP() {
        $ipaddress = '';
        if(isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ipaddress =  $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ipaddress = $_SERVER['HTTP_X_REAL_IP'];
        }
        else if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }
	
	public function authenticate($username = '', $password = '')
	{
		global $db,$crypt, $session;
		
		$password = $crypt->passwordHash($password,$username);
		$username = $db->filterVar($username);
		
		$results = $db->get($db->query("SELECT ID,GroupID FROM cms_user WHERE email='".$username."' AND pass ='".$password."' AND status='N' AND enabled='1' AND GroupID='3'"));
		if($results)
		{
			$new_results = $db->get($db->query("SELECT ID FROM cms_user_groups WHERE ID='".$results['GroupID']."' AND enabled='1'"));
			if($new_results) {
				$domainID=$db->get($db->query("SELECT ID, parentID FROM cms_domains WHERE name='".str_replace("www.", "", $_SERVER['HTTP_HOST'])."'"));
				if($domainID['parentID']!="-1") {
					$domainID=$db->get($db->query("SELECT ID, parentID FROM cms_domains WHERE ID='".$domainID['parentID']."'"));					
				}
				$num=$db->rows($db->query("SELECT ID FROM cms_domains_ids WHERE type='group' AND domainID='".$domainID['ID']."' AND elementID='".$results['GroupID']."'"));			
				if($num>0) {
					$this->updateVisit($results['ID']);
					$session->login($results['ID']);
					return $results['ID'];	
				} else {
					$num=$db->rows($db->query("SELECT ID FROM cms_domains WHERE alias='0'"));			
					if($num==0) {
						$this->updateVisit($results['ID']);
						$session->login($results['ID']);
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
	
	private function updateVisit($id) {
		global $db;
		$id = $db->filterVar($id);
		$query = $db->query("UPDATE cms_user SET visit='".date("Y-m-d H:i:s")."' WHERE ID='".$id."'");
	}
	
	public function logout() {
	    global $cookie;
	    unset($_SESSION['userFront']);
	    if($cookie->isCSC('userFront')) {
	        $cookie->remCSC('userFront', "/");
	    }
	}
}

if($session->isLogedInBack() && !$session->isLogedInFront()) {
	$user = new User($session->getIdBack()); 
} else if($session->isLogedInBack() && $session->isLogedInFront()) {
	$userBack = new User($session->getIdBack()); 
	$user = new User($session->getIdFront());
	$user->developer= $userBack->developer;
} else if($session->isLogedInFront()) {
	$user = new User($session->getIdFront());	
} else {
	$user = new User("-1"); 
}
?>
