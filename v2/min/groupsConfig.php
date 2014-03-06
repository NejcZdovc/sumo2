<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/** 
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 *
 * See http://code.google.com/p/minify/wiki/CustomSource for other ideas
 **/

include_once('../configs/settings.php');

ini_set('log_errors',1);
ini_set('error_log','../logs/error.log');
clearstatcache();

if(isset($_GET['a']) && $_GET['a']=="css") {
	$isNoUpdateFile=1;
	require_once('../initialize.php'); 	
	if(!$session->isLogedIn()) {
		exit;
	}
	if(file_exists("../modules/css.xml")) { 
		function src_css() {
			$content="";
			$xml = simplexml_load_file("../modules/css.xml","SimpleXMLElement", LIBXML_NOCDATA);
			header("Content-type: text/css;charset=utf-8"); 
			foreach($xml->item as $item) {
				$content.= $item."\n";	
			}
			return $content;
		}		
		$src_css = new Minify_Source(array(
			'id' => 'Modules CSS',
			'getContentFunc' => 'src_css',
			'contentType' => Minify::TYPE_CSS,    
			'lastModified' => filemtime("../modules/css.xml")
		));
	} else {
		error_log("ERROR: modules/javascript.xml file doesn\"t exsist.");	
	}
	return array('css' => array('//'.SITE_FOLDER.'v2/css/content.css', '//'.SITE_FOLDER.'v2/css/user.css', '//'.SITE_FOLDER.'v2/css/style.css', $src_css));
} else if(isset($_GET['a'])&& $_GET['a']=="js") {	
	$isNoUpdateFile=1;
	require_once('../initialize.php'); 	
	if(!$session->isLogedIn()) {
		exit;
	}
	if(file_exists("../modules/javascript.xml")) { 
		function src_js() {
			$content="";
			$xml_s = simplexml_load_file("../modules/javascript.xml","SimpleXMLElement", LIBXML_NOCDATA);
			header('Cache-Control: max-age=2592000');
			header('Expires-Active: On');
			header('Expires: Fri, 1 Jan 2500 01:01:01 GMT');
			header('Pragma:');
			header('Content-type: text/javascript; charset=utf-8');
			foreach($xml_s->item as $script) {
				$content.=$script."\n";	
			}
			return $content;
		}		
		$src_js = new Minify_Source(array(
			'id' => 'Modules JavaScripts',
			'getContentFunc' => 'src_js',
			'contentType' => Minify::TYPE_JS,    
			'lastModified' => filemtime("../modules/javascript.xml")
		));
	} else {
		error_log("ERROR: modules/javascript.xml file doesn\"t exsist.");	
	}
	
	return array(
		'js' => array('//'.SITE_FOLDER.'v2/ckeditor/ckeditor.min.js', '//'.SITE_FOLDER.'v2/scripts/jquery.min.js', '//'.SITE_FOLDER.'v2/scripts/sumo2.system.js', '//'.SITE_FOLDER.'v2/scripts/jquery.ui.js', '//'.SITE_FOLDER.'v2/scripts/jquery.plugins.js', '//'.SITE_FOLDER.'v2/scripts/sumo2.user.js', '//'.SITE_FOLDER.'v2/scripts/javascript.plugin.js', $src_js)
	);
}