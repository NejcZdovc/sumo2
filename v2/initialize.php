<?php
define( '_VALID_MOS', 1 );
define( '_VALID_EXT', 1 );
define('code_hidden_full', 'ted_wu9ruxeh2chesp2g*mAmu=u6Hazaq=fuf6ud+&*h_dequ$5p=ajaketr!wru');
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
require_once('configs'.DIRECTORY_SEPARATOR.'settings.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'includes'.DS.'errors.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'security.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'xml.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'aes.chris.veness.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'functions.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'validation.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'firewall.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'cryptography.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'cookie.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'session.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'database.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'language.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'user.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'shield.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'globals.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'ftp.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'update.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'phpmailer.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'pop3.class.php');
require_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'essentials'.DS.'pagging.class.php');

$setLocaleLang['hr']='';
$setLocaleLang['en-gb']='en_UK';
$setLocaleLang['en-us']='en_US';
$setLocaleLang['en']='english';
$setLocaleLang['de']='de_DE';
$setLocaleLang['de-lu']='de_AT';
$setLocaleLang['it']='';
$setLocaleLang['ru']='ru_RU';
$setLocaleLang['sr']='';
$setLocaleLang['sh']='';
$setLocaleLang['sl']='sl_SI';
$setLocaleLang['es']='es-ES';
 
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
	
	if(!$login) {
		if(isset($setLocaleLang[$user->langShortBack($user->lang)])) {
			setlocale(LC_ALL, $setLocaleLang[$user->langShortBack($user->lang)].'.UTF-8');
		} else {
			setlocale(LC_ALL, $setLocaleLang['en'].'.UTF-8');
		}
		
	}
}
?>