<?php
	$isNoUpdateFile=1;
	require_once('../initialize.php');
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 exit;
	}
	if($user->preview != 2) {
		if($globals->offline=="Y") {
			$off=$shield->protect('offline=true&user='.$user->id.'&username='.$user->username.'&date='.time().'');
			$preview_url='href="http://'.$user->domainName.'/index.php?'.$off.'"';
		}
		else
			$preview_url='href="http://'.$user->domainName.'/index.php"';
		if($user->preview == 1)
			$preview_url.=' target="_blank"';
		if($user->preview == 3)
			$preview_url.=' target="_blank"';
		if($user->preview == 4)
			$preview_url.='';
	} else
		$preview_url=' onclick="sumo2.dialog.NewDialog(\'d_preview\')"';
		
	echo '<a '.$preview_url.'>'.$lang->INDEX_PREW.'</a> | ';
?>