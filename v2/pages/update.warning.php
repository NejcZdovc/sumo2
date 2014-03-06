<?php require_once('../initialize.php');
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://3zsistemi.eu/update/'.$_SESSION['valArray'].'.txt');
	curl_setopt($ch, CURLOPT_FAILONERROR,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
	$text = curl_exec($ch);
	curl_close($ch);
	echo $text;
?>