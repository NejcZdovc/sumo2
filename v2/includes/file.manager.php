<?php
require_once('../configs/settings.php');
$security->checkLogin();

if(ob_get_length()>0) {	ob_end_clean(); }
if($db->is('path') && strpos($db->filter('path'),'/storage/') !== false) {
	if($db->is('rename')) {
		$old = $db->filter('path');
		$newName = $db->filter('name');
		$oldFile = end(explode('/',$old));
		$extension = end(explode('.',$oldFile));
		$path = str_replace($oldFile,'',$old);
		if(rename($old,$path.$newName.'.'.$extension)) {
			echo 'done';
		} else {
			echo 'problem - '.$old.' - '.$path.$newName.'.'.$extension;
		}
	}
	else if($db->is('delete')) {
		$old = $db->filter('path');
		unlink($old);
		echo 'ok';
	}
	else if($db->is('deletef')) {
		$old = $db->filter('path');
		recursive_remove_directory($old);
		echo 'ok';
	}
	else if($db->is('renamef')) {
		$old = $db->filter('path');
		$newName = $db->filter('name');
		$oldFile = end(explode('/',$old));
		$new = str_replace($oldFile,$newName,$old);
		if(rename($old,$new)) {
			echo 'done';
		} else {
			echo 'problem - '.$old.' - '.$new;
		}
	}
	else if($db->is('newf')) {
		$old = $db->filter('path');
		$newName = $db->filter('name');
		mkdir($old.'/'.$newName);
		echo 'done';
	}
}
?>