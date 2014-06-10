<?php 
require_once('../initialize.php'); 
$security->checkMin();
$session->logout();
echo 'ok';
?>