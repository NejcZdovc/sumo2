<?php
	require_once('../initialize.php');
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 exit;
	}
	
if(ob_get_length()>0) {ob_end_clean();}
if($db->is('mode')) {
	if($db->filter('mode') == 'add') {
		$fname = $db->filter('fname');
		$name = $db->filter('name');
		$fid = $db->filter('fid');
		$type = $db->filter('type');
		$grlist = $db->filter('grlist');
		$required = $db->filter('required');
		$min = $db->filter('min');
		$max = $db->filter('max');
		$description = $db->filter('descr');
		$exists = $db->checkField('cms_user_aditional',$fid);
		if($exists) {
			echo 'exists';
			exit;
		} else {
			$db->query("INSERT INTO cms_user_fields (labelName,name,fieldId,description,type,required,min,max,extra) VALUES ('".$fname."','".$name."','".$fid."','".$description."','".$type."','".$required."','".$min."','".$max."','".$grlist."')");
			switch($type) {
				case 1:
					$support = 'VARCHAR(300) NULL';
					break;
				case 2:
					$support = 'TEXT NULL';
					break;
				case 3:
					$support = "VARCHAR(200) NULL";
					break;
				case 4:
					$support = "VARCHAR(200) NULL";
					break;
				case 5:
					$support = "VARCHAR(200) NULL";
					break;
				case 6:
					$support = "TEXT NULL";
					break;
				case 7:
					$support = "VARCHAR(200) NULL";
					break;
				default:
					$support = "VARCHAR(200) NULL";
					break;
			}
			$db->query("ALTER TABLE cms_user_aditional ADD COLUMN ".$fid." ".$support);
			echo 'ok';
			exit;
		}
	}
	if($db->filter('mode') == 'status') {
		$id = $crypt->decrypt($db->filter('id'));
		$result = $db->get($db->query("SELECT enabled FROM cms_user_fields WHERE ID='".$id."'"));
		if($result) {
			if($result['enabled'] == 0) {
				$new = 1;
			} else {
				$new = 0;
			}
			$db->query("UPDATE cms_user_fields SET enabled='".$new."' WHERE ID='".$id."'");
			echo 'ok';
			exit;
		}
		echo 'problem';
		exit;
	}
	if($db->filter('mode') == 'delete') {
		$id = $crypt->decrypt($db->filter('id'));
		$db->query("UPDATE cms_user_fields SET status='D' WHERE ID='".$id."'");
		echo 'ok';
		exit;
	}
	if($db->filter('mode') == 'edit') {
		$fname = $db->filter('fname');
		$name = $db->filter('name');
		$fid = $db->filter('fid');
		$descr = $db->filter('descr');
		$grlist = $db->filter('grlist');
		$required = $db->filter('required');
		$min = $db->filter('min');
		$max = $db->filter('max');
		$id = $crypt->decrypt($db->filter('id'));
		$db->query("UPDATE cms_user_fields SET labelName='".$fname."', name='".$name."', description='".$descr."', required='".$required."', min='".$min."', max='".$max."', extra='".$grlist."' WHERE ID='".$id."'");
		echo 'ok';
		exit;
	}
	if($db->filter('mode') == '') {
		echo '';
		exit;
	}
}
?>