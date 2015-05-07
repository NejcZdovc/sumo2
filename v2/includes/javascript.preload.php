<?php
	require_once('../initialize.php');
	if($db->is('images')) {
		$dir = SITE_ROOT.SITE_FOLDER.'/sumoctrl/images/';
		$dh = opendir($dir);
		$images = '#%#';
		while(($file = readdir($dh)) !== false) {
			if($file != '.' && $file != '..') {
				$images .= '#images/'.$file;
			}
		}
		$images = str_replace('#%##','',$images);
		echo $images;
	} else if($db->is('scripts')) {
		$dir = SITE_ROOT.SITE_FOLDER.'/sumoctrl/scripts/';
		$dh = opendir($dir);
		$scripts = '#%#';
		while(($file = readdir($dh)) !== false) {
			if($file != '.' && $file != '..' && $file != 'sumo2.system.js') {
				$scripts .= '#scripts/'.$file;
			}
		}
		$scripts = str_replace('#%##','',$scripts);
		echo $scripts;
	}
?>