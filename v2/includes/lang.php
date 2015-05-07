<?php
	require_once('../initialize.php');
	$security->checkMin();
	if($db->is('type')) {
		if($db->filter('type')=='save'){
			$id_lang = $crypt->decrypt($db->filter('id'));
			$id = $user->id;
			$db->query("UPDATE cms_user_settings SET translate_lang='".$id_lang."' WHERE userID='".$id."'");
			echo $id_lang;
			exit;
		}
	}
?>
