<?	
	header('Content-disposition: attachment; filename=errorFront_log.txt');
	header('Content-type: text/plain');


	$file = "../logs/errorFront.log";
	$fh = fopen($file, 'r');
	echo  fread($fh, filesize($file));
	fclose($fh);
?>