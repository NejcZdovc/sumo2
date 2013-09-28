<?php  
$isNoUpdateFile=1;
require_once('../initialize.php');
if(!$session->isLogedIn() || !$security->checkURL()) {
 exit;
}
if(ob_get_length()>0) {ob_end_clean();}
if($db->is('get')) {
    $query = $db->query("SELECT state FROM cms_state WHERE userID='".$user->id."' LIMIT 1");
    $num_rows = $db->rows($query);
	if($num_rows > 0) {
	    $result = $db->get($query);
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
    $query = $db->query("SELECT ID FROM cms_state WHERE userID='".$user->id."' LIMIT 1");
	$num_rows = $db->rows($query);
	if($num_rows > 0) {
	    $stateResult = $db->get($query);
	    $db->query("UPDATE cms_state SET state='".$state."' WHERE ID='".$stateResult['ID']."'");
	} else {
	    $db->query("INSERT INTO cms_state (userID,state) VALUES ('".$user->id."','".$state."')");
	}
	echo 'ok';
}
?>