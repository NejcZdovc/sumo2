<?	
	header('Content-disposition: attachment; filename=error_log.txt');
	header('Content-type: text/plain');


	$file = "../logs/error.log";
	$fh = fopen($file, 'r');
	echo  fread($fh, filesize($file));
	fclose($fh);
?>