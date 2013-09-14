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
	if($valid->isNumber($_POST['o1'])) {
		$o1 = $_POST['o1'];
	} else {
		$o1 = 0;
	}
	if($valid->isNumber($_POST['o2'])) {
		$o2 = $_POST['o2'];
	} else {
		$o2 = 0;
	}
	if($valid->isNumber($_POST['o3'])) {
		$o3 = $_POST['o3'];
	} else {
		$o3 = 0;
	}
	if($valid->isNumber($_POST['o4'])) {
		$o4 = $_POST['o4'];
	} else {
		$o4 = 0;
	}
	if($valid->isNumber($_POST['o5'])) {
		$o5 = $_POST['o5'];
	} else {
		$o5 = 0;
	}
	if($valid->isNumber($_POST['o6'])) {
		$o6 = $_POST['o6'];
	} else {
		$o6 = 0;
	}
	if($valid->isNumber($_POST['o7'])) {
		$o7 = $_POST['o7'];
	} else {
		$o7 = 0;
	}
	if($valid->isNumber($_POST['o8'])) {
		$o8 = $_POST['o8'];
	} else {
		$o8 = 0;
	}
	if($valid->isNumber($_POST['o9'])) {
		$o9 = $_POST['o9'];
	} else {
		$o9 = 0;
	}
	if($valid->isNumber($_POST['o10'])) {
		$o10 = $_POST['o10'];
	} else {
		$o10 = 0;
	}
	$id = $user->id;
	$db->query("UPDATE cms_favorites SET option1='".$o1."',option2='".$o2."',option3='".$o3."',option4='".$o4."',option5='".$o5."',option6='".$o6."',option7='".$o7."',option8='".$o8."',option9='".$o9."',option10='".$o10."' WHERE UserID='".$id."'");
	echo 'Finished';
?>