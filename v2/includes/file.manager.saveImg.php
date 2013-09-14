<?php

	require_once('../initialize.php');
	
	$source = $_GET['image'];
	$save_to = SITE_ROOT.SITE_FOLDER.'/storage/Images/';
	$set_extension = $_GET['type'];

	$new_name = $_GET['title'].'.'.$set_extension;
	
	$save_to = $save_to.$new_name;

	$save_image = LoadImageCURL($save_to, $source);

	
	

	function LoadImageCURL($save_to, $source)
	{
	$ch = curl_init($source);
	$fp = fopen($save_to, "wb");
	
	$options = array(CURLOPT_FILE => $fp,
					 CURLOPT_HEADER => 0,
					 CURLOPT_TIMEOUT => 150);
	
	curl_setopt_array($ch, $options);
	
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);
	}
?>