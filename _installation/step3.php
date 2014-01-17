<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log','../v2/logs/error.log');
	include('../v2/configs/settings.php');
	
	include('sql.class.php');
	echo '<div id="help">In this step a database will be created and data, needed for the basic functioning of the site will be inserted. If an error occurs, please try pressing check, otherwise continue to the next step.</div>';
	echo '%#%#%';
	$link = new mysqli(__DB_SERVER__, __DB_USER__, __DB_PASSWORD__, __DB_DATABASE__);
	$link->set_charset(__ENCODING__);
	if ($link->connect_errno) {
		printf("Connect failed: %s<br/>", $link->mysqli_error);
		exit();
	}
		
	
	$dbms_schema='database.sql';
	$sql_query = @fread(@fopen($dbms_schema, 'r'), @filesize($dbms_schema));
	$sql_query = remove_remarks($sql_query);
	$sql_query = remove_comments($sql_query);
	$sql_query = split_sql_file($sql_query, ';');
	foreach($sql_query as $query) {
		if (!$link->query($query)) {
			printf("Error: %s<br/>", $link->error);
		}
	}
	echo "0";
?>