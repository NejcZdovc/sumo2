<?php 
$isLoginFile=821662345037610915243762;
require_once('../initialize.php');
if(is_dir('../../installation')) {
	echo 'folder';
	exit();	
}
$allow=$db->rows($db->query('SELECT cms_domains_ips.ID FROM cms_domains_ips, cms_domains WHERE type="0" AND domainID=cms_domains.ID AND cms_domains.name="'.str_replace("www.", "", $_SERVER["SERVER_NAME"]).'" AND cms_domains_ips.value="'.$_SERVER['REMOTE_ADDR'].'"'));
if($allow>0) {
	echo "ip";
	exit();
}
if($db->filter('token') == $session->getFormToken())
{
	$password = $db->filter('password');
	$username = $db->filter('username');
	if($valid->isUsername($username,1,60) && $valid->isLength($password,6,20))
	{
		$id = User::authenticate($username,$password);
		if($id=="domain") {
			echo 'domain';	
		} else {
			if($id != false) {
				$session->login($id);
				User::updateVisit($id);
				echo 'ok';	
			}
			else {
				echo 'match';	
			}
		}		
	}
	else {
		echo 'format';	
	}
}
else {
	echo 'token';	
}
?>