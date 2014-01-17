<?php require_once('../initialize.php'); 
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 	exit;
	}
?>
<div id="welcome_a" style="padding:20px;">
<?php echo $globals->welcome?>
</div>