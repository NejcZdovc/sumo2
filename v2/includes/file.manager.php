<?php
require_once('../configs/settings.php');
if(!$session->isLogedIn() || !$security->checkURL()) {
 exit;
}
if(ob_get_length()>0) {	ob_end_clean(); }
if(isset($_POST['rename']) && isset($_POST['path']) && strpos($_POST['path'],'/storage/') !== false) {
	$old = $_POST['path'];
	$newName = $_POST['name'];
	$oldFile = end(explode('/',$old));
	$extension = end(explode('.',$oldFile));
	$path = str_replace($oldFile,'',$old);
	if(rename($old,$path.$newName.'.'.$extension)) {
		echo 'done';
	} else {
		echo 'problem - '.$old.' - '.$path.$newName.'.'.$extension;	
	}
}
else if(isset($_POST['delete']) && isset($_POST['path']) && strpos($_POST['path'],'/storage/') !== false) {
	$old = $_POST['path'];
	unlink($old);
	echo 'ok';
}
else if(isset($_POST['deletef']) && isset($_POST['path']) && strpos($_POST['path'],'/storage/') !== false) {
	$old = $_POST['path'];
	recursive_remove_directory($old);
	echo 'ok';
}
else if(isset($_POST['renamef']) && isset($_POST['path']) && strpos($_POST['path'],'/storage/') !== false) {
	$old = $_POST['path'];
	$newName = $_POST['name'];
	$oldFile = end(explode('/',$old));
	$new = str_replace($oldFile,$newName,$old);
	if(rename($old,$new)) {
		echo 'done';
	} else {
		echo 'problem - '.$old.' - '.$new;	
	}
}
else if(isset($_POST['newf']) && isset($_POST['path']) && strpos($_POST['path'],'/storage/') !== false) {
	$old = $_POST['path'];
	$newName = $_POST['name'];
	mkdir($old.'/'.$newName);
	echo 'done';
}
?>