<?php
$isLoginFile=821662345037610915243762;
require_once('../initialize.php');
$time=base64_decode($db->filter('token'));
if(ob_get_length()>0) {ob_end_clean();}
$allow=$db->rows($db->query('SELECT cms_domains_ips.ID FROM cms_domains_ips, cms_domains WHERE type="0" AND domainID=cms_domains.ID AND cms_domains.name="'.str_replace("www.", "", $_SERVER["SERVER_NAME"]).'" AND cms_domains_ips.value="'.$_SERVER['REMOTE_ADDR'].'"'));
if($allow>0) {
	echo "ip";
	exit();
}
if((time()-$time)<600)
{
	$password = $db->filter('password');
	$username = $db->filter('username');
	$oldUserID = $db->filter('oldUserID');
	$hold = $db->filter('remember');
	if(ob_get_length()>0) {ob_end_clean();}
	if($valid->isUsername($username,1,20) && $valid->isLength($password,6,20))
	{
		$id = User::authenticate($username,$password, $oldUserID);
		if($id=="token") {
			echo 'token';
		} else if($id=="domain") {
			echo 'domain';
		} else if($id=="refresh") {
			echo 'refresh';
		} else if($id != false) {
			$session->login($id,$hold);
			User::updateVisit($id);
			echo 'ok';
		}
		else {
			echo 'match';
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