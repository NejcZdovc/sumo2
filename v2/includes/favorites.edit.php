<?php 
	require_once('../initialize.php');
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 exit;
	}
	if(ob_get_length()>0) {ob_end_clean(); }
	if(!$session->isLogedIn()) {
		redirect_to('../login/');
	} else {
		$user = new User($session->getId()); 
	}
	if($valid->isNumber($db->filter('o1'))) {
		$o1 = $db->filter('o1');
	} else {
		$o1 = 0;
	}
	if($valid->isNumber($db->filter('o2'))) {
		$o2 = $db->filter('o2');
	} else {
		$o2 = 0;
	}
	if($valid->isNumber($db->filter('o3'))) {
		$o3 = $db->filter('o3');
	} else {
		$o3 = 0;
	}
	if($valid->isNumber($db->filter('o4'))) {
		$o4 = $db->filter('o4');
	} else {
		$o4 = 0;
	}
	if($valid->isNumber($db->filter('o5'))) {
		$o5 = $db->filter('o5');
	} else {
		$o5 = 0;
	}
	if($valid->isNumber($db->filter('o6'))) {
		$o6 = $db->filter('o6');
	} else {
		$o6 = 0;
	}
	if($valid->isNumber($db->filter('o7'))) {
		$o7 = $db->filter('o7');
	} else {
		$o7 = 0;
	}
	if($valid->isNumber($db->filter('o8'))) {
		$o8 = $db->filter('o8');
	} else {
		$o8 = 0;
	}
	if($valid->isNumber($db->filter('o9'))) {
		$o9 = $db->filter('o9');
	} else {
		$o9 = 0;
	}
	if($valid->isNumber($db->filter('o10'))) {
		$o10 = $db->filter('o10');
	} else {
		$o10 = 0;
	}
	$id = $user->id;
	$db->query("UPDATE cms_favorites SET option1='".$o1."',option2='".$o2."',option3='".$o3."',option4='".$o4."',option5='".$o5."',option6='".$o6."',option7='".$o7."',option8='".$o8."',option9='".$o9."',option10='".$o10."' WHERE UserID='".$id."'");
	echo 'Finished';
?>