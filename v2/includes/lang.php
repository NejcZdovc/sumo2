<?php 
	require_once('../initialize.php');
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 exit;
	}
	if(isset($_POST['type'])) {
		if($_POST['type']=='save'){
			$id_lang = $crypt->decrypt($_POST['id']);
			$id = $user->id;
			$db->query("UPDATE cms_user_settings SET translate_lang='".$id_lang."' WHERE userID='".$id."'");
			echo $id_lang;
			exit;
		}
	}
?>
