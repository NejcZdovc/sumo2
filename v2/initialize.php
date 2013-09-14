<?php
define( '_VALID_MOS', 1 );
define( '_VALID_EXT', 1 );
define('code_hidden_full', 'ted_wu9ruxeh2chesp2g*mAmu=u6Hazaq=fuf6ud+&*h_dequ$5p=ajaketr!wru');
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
require_once('configs/settings.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'includes/errors.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials/security.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials/xml.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials/aes.chris.veness.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials/functions.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials/validation.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials/firewall.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials/cryptography.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials/cookie.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials/session.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials/database.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials/language.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials/user.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials/shield.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials/globals.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials/ftp.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials/update.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials/phpmailer.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials/pop3.class.php'); 
if(!isset($isLoginFile)) {
	$login=false;
	if(!$session->isLogedIn()) {
		 $login=true;
	} 
	
	if(!isset($user)) {
		$login=true;
	}else if(!isset($isNoUpdateFile) && !$session->updateLogin($user->id)) {
		$login=true;
	}
	if($login && !isset($isNoUpdateFile)) {
		echo '<div id="javascriptLoginBox">sumo2.dialog.NewDialog(\'d_relogin\', null, true);</div>';
	}
}
?>