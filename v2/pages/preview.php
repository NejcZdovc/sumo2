<?php
	require_once('../initialize.php');
	$security->checkFull();
	$off=$shield->protect('offline=true&user='.$user->id.'&username='.$user->username.'&date='.time().'');
?>


<iframe src ="http://<?php echo $_SERVER['SERVER_NAME']?>/index.php?<?php echo $off?>" width="100%" style="padding-top:10px;" height="98%"> <p>Your browser does not support iframes.</p>
</iframe>