<?php
	include('../v2/configs/settings.php');
	include('sql.class.php');
	$link = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD,DB_DATABASE);
	$link->set_charset("utf8");
	if ($link->connect_errno) {
		printf("Connect failed: %s", $link->mysqli_error);
		exit();
	}
	echo "0";	
	
	$dbms_schema='database.sql';
	$sql_query = @fread(@fopen($dbms_schema, 'r'), @filesize($dbms_schema));
	$sql_query = remove_remarks($sql_query);
	$sql_query = remove_comments($sql_query);
	$sql_query = split_sql_file($sql_query, ';');
	foreach($sql_query as $query) {
		if (!$link->query($query)) {
			printf("Error: %s\n", $link->error);
		}
	}
?>