<?php
	require_once('../initialize.php');
	
	header('Content-disposition: attachment; filename=errorFront_'.$_GET['page'].'_log.txt');
	header('Content-type: text/plain');

	$file = "..".DS."logs".DS."errorFront_".$_GET['page'].".log";
	$fh = fopen($file, 'r');
	echo  fread($fh, filesize($file));
	fclose($fh);
?>