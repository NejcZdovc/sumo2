<?php 
require_once('../initialize.php'); 
if(!$session->isLogedIn() || !$security->checkURL()) {
 exit;
}
$session->logout();
echo 'ok';
?>