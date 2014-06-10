<?php 
	require_once('../initialize.php');
	$security->checkMin();
	echo $user->name.$shield->protect('id='.$user->id.'&username='.$user->username.'&mail='.$user->mail.'&permission='.$user->permission.'$name='.$user->name);
?>