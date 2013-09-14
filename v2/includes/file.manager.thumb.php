<?php
$file = explode('/', $_GET["name"]);

$saveName = stripslashes($file[count($file)-1]);
$savePath = stripslashes($_GET["path"]);
// send headers to initiate a binary download

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=$saveName");
header("Content-Transfer-Encoding: binary");
header("Content-length: " . filesize($savePath));

readfile($savePath);
?>