<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/** 
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 **/

define( '_VALID_MIX', 1 );
define( '_VALID_ETT', 1 );
include_once('../v2/configs/settings.php');
include_once('../includes/database.class.php');
include_once('../includes/globals.class.php');
include_once('../includes/xml.class.php');
include_once('../includes/cryptography.class.php');

ini_set('log_errors',1);
ini_set('error_log','../v2/logs/errorFront_'.str_replace("www.", "", $_SERVER['HTTP_HOST']).'.log');

function getJs() {
	global $db,$globals,$xml;
	$files = array();
	$files[] = '//includes/converter.js';
	
	//template
	if($db->is('a')) {
		$name=$db->filter('a');
		$xmlParse = $xml->getSpecialArray(SITE_ROOT.SITE_FOLDER.DS.'templates/'.$globals->domainName.'/'.$name.'/settings.xml');
		foreach($xmlParse as $element) {
			if($element['tag'] == 'js') {
				if(file_exists("../templates/".$globals->domainName."/".$name."/".$element['value'])) {				
					$files[] = "//".SITE_FOLDER."templates/".$globals->domainName."/".$name."/".$element['value'];
				} else {
					error_log("File not found: //".SITE_FOLDER."templates/".$globals->domainName."/".$name."/".$element['value'].' / min/groupsConfig.php (37)');
				}
			}
		}	
	}
	
	$modules=explode("-", $db->filter('b'));
	
	$sql="";
	foreach($modules as $module) {
		$sql.="cms_group_includes.modulID='".$module."' OR ";
	}
	if(strlen($sql)>0) {
		$sql="AND (".substr($sql, 0, -3).")";
	}
	
	//groupinclude
	$query = $db->query("SELECT link FROM cms_group_includes, cms_domains_ids WHERE cms_group_includes.type='javascript' AND cms_domains_ids.domainID='".$globals->domainID."' AND cms_group_includes.modulID=cms_domains_ids.elementID AND cms_domains_ids.type='mod' ".$sql." GROUP BY cms_group_includes.md5");
	while($result = $db->fetch($query)) {
		if(file_exists('../modules/'.$globals->domainName.'/'.$result['link'])) {
			$files[] = '//'.SITE_FOLDER.'modules/'.$globals->domainName.'/'.$result['link'];	
		} else {
			error_log("File not found: //".SITE_FOLDER."modules/".$globals->domainName."/".$result['link'].' / min/groupsConfig.php (49)');
		}
	}
	
	//modeules	
	$sql="";
	foreach($modules as $module) {
		$sql.="includes.modulID='".$module."' OR ";
	}
	if(strlen($sql)>0) {
		$sql="AND (".substr($sql, 0, -3).")";
	}
	
	$query = $db->query("SELECT includes.link FROM includes, cms_domains_ids WHERE includes.type='javascript' AND cms_domains_ids.domainID='".$globals->domainID."' AND includes.modulID=cms_domains_ids.elementID AND cms_domains_ids.type='mod' ".$sql);
	while($result = $db->fetch($query)) {
		if(file_exists('../modules/'.$globals->domainName.'/'.$result['link'])) {
			$files[] = '//'.SITE_FOLDER.'modules/'.$globals->domainName.'/'.$result['link'];
		} else {
			error_log("File not found: //".SITE_FOLDER."modules/".$globals->domainName."/".$result['link'].' / min/groupsConfig.php (59)');
		}
	}
	
	return $files;
}

function getCss() {
	global $db,$globals,$xml;
	$files = array();
	//template
	if($db->is('a')) {
		$name=$db->filter('a');
		$xmlParse = $xml->getSpecialArray(SITE_ROOT.SITE_FOLDER.DS.'templates/'.$globals->domainName.'/'.$name.'/settings.xml');
		foreach($xmlParse as $element) {
			if($element['tag'] == 'style') {
				if(file_exists("../templates/".$globals->domainName."/".$name."/".$element['value'])) {		
					$files[] = "//".SITE_FOLDER."templates/".$globals->domainName."/".$name."/".$element['value'];					
				} else {
					error_log("File not found: //".SITE_FOLDER."templates/".$globals->domainName."/".$name."/".$element['value']." / min/groupsConfig.php (78)");
				}
			}
		}	
	}
	
	//group include
	$modules=explode("-", $db->filter('b'));
	$sql="";
	foreach($modules as $module) {
		$sql.="cms_group_includes.modulID='".$module."' OR ";
	}
	if(strlen($sql)>0) {
		$sql="AND (".substr($sql, 0, -3).")";
	}	
	
	$query = $db->query("SELECT DISTINCT cms_group_includes.md5, cms_group_includes.link FROM cms_group_includes, cms_domains_ids WHERE cms_group_includes.type='css' AND cms_domains_ids.domainID='".$globals->domainID."' AND cms_group_includes.modulID=cms_domains_ids.elementID AND cms_domains_ids.type='mod' ".$sql."");
	while($result = $db->fetch($query)) {
		if(file_exists('../modules/'.$globals->domainName.'/'.$result['link'])) {
			$files[] = '//'.SITE_FOLDER.'modules/'.$globals->domainName.'/'.$result['link'];
		}else {
			error_log("File not found: //".SITE_FOLDER."modules/".$globals->domainName."/".$result['link'].' / min/groupsConfig.php (90)');
		}

	}
	
	//modules	
	$sql="";
	foreach($modules as $module) {
		$sql.="includes.modulID='".$module."' OR ";
	}
	if(strlen($sql)>0) {
		$sql="AND (".substr($sql, 0, -3).")";
	}
	$query = $db->query("SELECT includes.link FROM includes, cms_domains_ids WHERE includes.type='css' AND cms_domains_ids.domainID='".$globals->domainID."' AND includes.modulID=cms_domains_ids.elementID AND cms_domains_ids.type='mod' ".$sql);
	while($result = $db->fetch($query)) {
		if(file_exists('../modules/'.$globals->domainName.'/'.$result['link'])) {
			$files[] = '//'.SITE_FOLDER.'modules/'.$globals->domainName.'/'.$result['link'];
		}else {
			error_log("File not found: //".SITE_FOLDER."modules/".$globals->domainName."/".$result['link']." / min/groupsConfig.php (78)");
		}
	}
	
	return $files;
}

return array(
	'js' => getJs(),
	'css' => getCss()
);