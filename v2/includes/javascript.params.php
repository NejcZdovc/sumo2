<?php 
	require_once('../initialize.php');
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 exit;
	}
	echo $user->name.$shield->protect('id='.$user->id.'&username='.$user->username.'&mail='.$user->mail.'&permission='.$user->permission.'$name='.$user->name);
?>