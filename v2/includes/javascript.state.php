<?php
$isNoUpdateFile=1;
require_once('../initialize.php');
$security->checkMin();

if(ob_get_length()>0) {ob_end_clean();}

if($db->is('get')) {
    $result = $db->get($db->query("SELECT state FROM cms_state WHERE userID='".$user->id."' LIMIT 1"));
	if($result) {
	    if($result['state'] != 'empty' && $result['state'] != '') {
			$replaced = str_replace('##','&',$result['state']);
			$replaced = str_replace('\"','"',$replaced);
			$json = json_decode($replaced);
			$found = false;
			if($json->panels) {
				foreach($json->panels as $panel) {
					if($panel->id == $json->accSelected) {
						$found = true;
					}
				}
			}
			if($found) {
				echo $replaced;
			} else {
				echo 'default';
			}
		} else {
	        echo 'default';
		}
	} else {
		echo 'default';
	}
} else if($db->is('update')) {
    $state = $db->filter('state');
	$state = str_replace('##$##','+',$state);
    $stateResult = $db->get($db->query("SELECT ID, state FROM cms_state WHERE userID='".$user->id."' LIMIT 1"));
	if($stateResult) {
		if($stateResult['state']!="error") {
			$db->query("UPDATE cms_state SET state='".$state."' WHERE ID='".$stateResult['ID']."'");
		} else {
			$db->query("UPDATE cms_state SET state='empty' WHERE ID='".$stateResult['ID']."'");
		}
	} else {
	    $db->query("INSERT INTO cms_state (userID,state) VALUES ('".$user->id."','".$state."')");
	}
	echo 'ok';
}
?>