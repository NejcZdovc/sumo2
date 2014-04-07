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
include_once('..'.DIRECTORY_SEPARATOR.'v2'.DIRECTORY_SEPARATOR.'configs'.DIRECTORY_SEPARATOR.'settings.php');
include_once('..'.DS.'v2'.DS.'essentials'.DS.'device.detect.class.php');
include_once('..'.DS.'includes'.DS.'database.class.php');
include_once('..'.DS.'includes'.DS.'globals.class.php');
include_once('..'.DS.'includes'.DS.'xml.class.php');
include_once('..'.DS.'includes'.DS.'cryptography.class.php');
include_once('..'.DS.'includes'.DS.'functions.class.php');


ini_set('log_errors',1);
ini_set('error_log','..'.DS.'v2'.DS.'logs'.DS.'errorFront_'.str_replace("www.", "", $_SERVER['HTTP_HOST']).'.log');

function getJs() {
	global $db,$globals,$xml,$function;
	$files = array();
	$files[] = '//includes/converter.js';
	
	$device=$function->detectDevice();
	//template
	if($db->is('a')) {
		$name=$db->filter('a');
		$xmlParse = $xml->getSpecialArray(SITE_ROOT.SITE_FOLDER.DS.'templates'.DS.$globals->domainName.DS.$name.DS.'settings.xml');
		foreach($xmlParse as $element) {
			if($element['tag'] == 'js') {
				if(file_exists("..".DS."templates".DS.$globals->domainName.DS.$name.DS.$element['value'])) {	
					if(isset($element['attributes']['device'])) {
						if($element['attributes']['device']==$device) {
							$files[] = "//".SITE_FOLDER."templates".DS.$globals->domainName.DS.$name.DS.$element['value'];
						} else if($element['attributes']['device']=="mobile" && ($device=="phone" || $device=="tablet")) {
							$files[] = "//".SITE_FOLDER."templates".DS.$globals->domainName.DS.$name.DS.$element['value'];
						}
					} else {
						$files[] = "//".SITE_FOLDER."templates".DS.$globals->domainName.DS.$name.DS.$element['value'];
					}	
				} else {
					error_log("File not found: //".SITE_FOLDER."templates".DS.$globals->domainName.DS.$name.DS.$element['value'].' / min/groupsConfig.php (37)');
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
		if(file_exists('..'.DS.'modules'.DS.$globals->domainName.DS.$result['link'])) {
			$files[] = '//'.SITE_FOLDER.'modules'.DS.$globals->domainName.DS.$result['link'];	
		} else {
			error_log("File not found: //".SITE_FOLDER."modules".DS.$globals->domainName.DS.$result['link'].' / min/groupsConfig.php (49)');
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
		if(file_exists('..'.DS.'modules'.DS.$globals->domainName.DS.$result['link'])) {
			$files[] = '//'.SITE_FOLDER.'modules'.DS.$globals->domainName.DS.$result['link'];
		} else {
			error_log("File not found: //".SITE_FOLDER."modules".DS.$globals->domainName.DS.$result['link'].' / min/groupsConfig.php (59)');
		}
	}
	
	return $files;
}

function getCss() {
	global $db,$globals,$xml,$function;
	$files = array();
	
	$device=$function->detectDevice();
	//template
	if($db->is('a')) {
		$name=$db->filter('a');
		$xmlParse = $xml->getSpecialArray(SITE_ROOT.SITE_FOLDER.DS.'templates/'.$globals->domainName.'/'.$name.'/settings.xml');
		foreach($xmlParse as $element) {
			if($element['tag'] == 'style') {
				if(file_exists("..".DS."templates".DS.$globals->domainName.DS.$name.DS.$element['value'])) {	
					if(isset($element['attributes']['device'])) {
						if($element['attributes']['device']==$device) {
							$files[] = "//".SITE_FOLDER."templates".DS.$globals->domainName.DS.$name.DS.$element['value'];
						} else if($element['attributes']['device']=="mobile" && ($device=="phone" || $device=="tablet")) {
							$files[] = "//".SITE_FOLDER."templates".DS.$globals->domainName.DS.$name.DS.$element['value'];
						}
					} else {
						$files[] = "//".SITE_FOLDER."templates".DS.$globals->domainName.DS.$name.DS.$element['value'];
					}	
				} else {
					error_log("File not found: //".SITE_FOLDER."templates".DS.$globals->domainName.DS.$name.DS.$element['value'].' / min/groupsConfig.php (37)');
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
		if(file_exists('..'.DS.'modules'.DS.$globals->domainName.DS.$result['link'])) {
			$files[] = '//'.SITE_FOLDER.'modules'.DS.$globals->domainName.DS.$result['link'];
		}else {
			error_log("File not found: //".SITE_FOLDER."modules".DS.$globals->domainName.DS.$result['link'].' / min/groupsConfig.php (90)');
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
		if(file_exists('..'.DS.'modules'.DS.$globals->domainName.DS.$result['link'])) {
			$files[] = '//'.SITE_FOLDER.'modules'.DS.$globals->domainName.DS.$result['link'];
		}else {
			error_log("File not found: //".SITE_FOLDER."modules".DS.$globals->domainName.DS.$result['link']." / min/groupsConfig.php (78)");
		}
	}
	
	return $files;
}

return array(
	'js' => getJs(),
	'css' => getCss()
);