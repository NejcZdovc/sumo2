<?php
	require_once('../initialize.php');
	
	header('Content-disposition: attachment; filename=data_'.$_GET['page'].'_log.txt');
	header('Content-type: text/plain');

	$file = "..".DS."logs".DS."data_".$_GET['page'].".log";
	$fh = fopen($file, 'r');
	echo  fread($fh, filesize($file));
	fclose($fh);
?>