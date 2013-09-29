<?php require_once('../initialize.php'); 
	if($db->is('hash') && $_POST['hash'] == "haC7wrEjepahuswa#?=_c373rupR@9efeafrujesUQA7_u7hESpu49Dat34swa")
	{
		$description = htmlentities($_POST['desc']);
		$location = htmlentities($_POST['loc']);
		error_log("JAVASCRIPT ERROR: ".$description." at ".$location);
	}
	else
	{
	    error_log("Javascript error authentication failed.");
	}
?>