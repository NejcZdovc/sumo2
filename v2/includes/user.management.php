<?php 
	require_once('../initialize.php');
	$security->checkMin();
	
if($db->is('type')) {
	if(ob_get_length()>0) {ob_end_clean();}
	if($db->filter('type') == 'add') {
		$name = $db->filter('name');
		$password = $db->filter('password');
		$email = $db->filter('email');
		$group = $db->filter('group');
		$username = $db->filter('username');
		$query = $db->query("SELECT username FROM cms_user WHERE username='".$username."' AND status='N'");
		$rows = $db->rows($query);
		$emailRows = $db->rows($db->query("SELECT * FROM cms_user WHERE email='".$email."' AND status='N'"));
		if($emailRows > 0) {
			echo 'email';
			exit;
		}
		if($rows > 0) {
			echo 'same';
			exit;
		}
		if(!$valid->isUsername($username,2,20)) {
			echo 'username';
			exit;
		}
		if(!$valid->isLength($password,5,20)) {
			echo 'password';
			exit;
		}
		if(!$valid->isNumber($group)) {
			echo 'group';
			exit;
		}
		$filName = $db->filterVar($name);
		$filUsername = $db->filterVar($username);
		$filPass = $crypt->passwordHash($password,$username);
		$filEmail = $db->filterVar($email);
		$db->query("INSERT INTO cms_user (username,pass,email,GroupID,name) VALUES ('".$filUsername."','".$filPass."','".$filEmail."','".$group."','".$filName."')");
		$last_insert=$db->getLastId();
		$db->query("INSERT INTO cms_favorites (UserID,option1,option2,option3) VALUES ('".$last_insert."','3','8','11')");
		$db->query("INSERT INTO cms_state (userID, state) VALUES ('".$last_insert."', 'empty')");
		$db->query("INSERT INTO cms_user_settings (userID,lang) VALUES ('".$last_insert."','1')");		
		$extraInfoValues = '';
		$extraInfo = '';
		$first = true;
		foreach($_POST as $key => $value) {
			if(strpos($key,"_e_x_t_r_a") > -1) {
				if($first) {
					$extraInfoValues .= str_replace("_e_x_t_r_a","",$key);
					$extraInfo .= "'".$db->filterVar($value)."'";
					$first = false;
				} else {
					$extraInfoValues .= ','.str_replace("_e_x_t_r_a","",$key);
					$extraInfo .= ",'".$db->filterVar($value)."'";
				}
			}
		}
		if(strlen($extraInfo) > 0) {
			$db->query("INSERT INTO cms_user_aditional (userID,".$extraInfoValues.") VALUES ('".$last_insert."',".$extraInfo.")");
		}		
		echo 'ok';
		exit;
	} else if($db->filter('type') == 'addgroup') {
		$name = $db->filter('name');
		$description = $db->filter('description');
		$creation = date("Y-m-d H:i:s");
		$db->query("INSERT INTO cms_user_groups (title,description,creation) VALUES ('".$name."','".$description."',''".$creation."')");
		$id=$db->getLastId();
		$domains=$db->filter('domains');
		$domains=explode('*/*', $domains);
		foreach($domains as $domain) {
			$db->query("INSERT INTO cms_domains_ids (elementID,domainID,type) VALUES ('".$id."','".$domain."','group')");			
		}
		echo 'ok';
		exit;
	} else if($db->filter('type') == 'status') {
		$id = $crypt->decrypt($db->filter('id'));
		$result = $db->get($db->query("SELECT enabled FROM cms_user WHERE ID='".$id."'"));
		if($result) {
			if($result['enabled'] == 0) {
				$new = 1;
			} else {
				$new = 0;
			}
			$db->query("UPDATE cms_user SET enabled='".$new."' WHERE ID='".$id."'");
			echo 'Finished';
			exit;
		}
	}else if($db->filter('type') == 'statusgroup') {
		$id = $crypt->decrypt($db->filter('id'));
		$result = $db->get($db->query("SELECT enabled FROM cms_user_groups WHERE ID='".$id."'"));
		if($result) {
			if($result['enabled'] == 0) {
				$new = 1;
			} else {
				$new = 0;
			}
			$db->query("UPDATE cms_user_groups SET enabled='".$new."' WHERE ID='".$id."'");
			echo 'Finished';
			exit;
		}
	} else if($db->filter('type') == 'deletegroup') {
		$id = $crypt->decrypt($db->filter('id'));
		$db->query("UPDATE cms_user_groups SET status='D' WHERE ID='".$id."'");
		echo 'Finished';
		exit;
	} else if($db->filter('type') == 'delete') {
		$id = $crypt->decrypt($db->filter('id'));
		$db->query("UPDATE cms_user SET status='D' WHERE ID='".$id."'");
		echo 'Finished';
		exit;
	} else if($db->filter('type') == 'editgroup') {
		$id = $crypt->decrypt($db->filter('id'));
		$name = $db->filter('name');
		$description = $db->filter('description');
		$cache = $db->filter('cache');
		$errorLog = $db->filter('errorLog');
		$dataLog = $db->filter('dataLog');
		$backendLogin = $db->filter('backendLogin');
		$db->query("UPDATE cms_user_groups SET title='".$name."', description='".$description."', cache='".$cache."', errorLog='".$errorLog."', dataLog='".$dataLog."', login='".$backendLogin."' WHERE ID='".$id."'");
		
		/*Access*/
		$access=str_replace('\"', '"', $db->filter('access'));
		$access = json_decode($access);
		$system = simplexml_load_file('..'.DS.'system.xml');
		$modules = simplexml_load_file('..'.DS.'modules'.DS.'system.xml');
		$db->query('DELETE FROM cms_user_groups_permissions WHERE groupID="'.$id.'"');
		foreach($system->accordion->item as $element) {
			$page=str_replace(array("\\", "/"), "@", $element->page);
			if($element->uniqueId!="a_welcome") {
				if(isset($access->{$element->uniqueId})) {
					$db->query('INSERT INTO cms_user_groups_permissions (groupID, objectID, permission, file, enabled) VALUES ("'.$id.'", "'.$element->uniqueId.'", "'.$access->{$element->uniqueId}.'", "'.$page.'", "1")');
				} else {
					$db->query('INSERT INTO cms_user_groups_permissions (groupID, objectID, permission, file, enabled) VALUES ("'.$id.'", "'.$element->uniqueId.'", "0", "'.$page.'", "0")');
				}
			} else {
				$db->query('INSERT INTO cms_user_groups_permissions (groupID, objectID, permission, file, enabled) VALUES ("'.$id.'", "a_welcome", "5", "pages@welcome.php", "1")');
			}
		}
		
		foreach($modules->accordion->item as $element) {
			$page=str_replace(array("\\", "/"), "@", $element->page);
			if(isset($access->{$element->uniqueId})) {
				$db->query('INSERT INTO cms_user_groups_permissions (groupID, objectID, permission, file, enabled) VALUES ("'.$id.'", "'.$element->uniqueId.'", "'.$access->{$element->uniqueId}.'", "'.$page.'", "1")');
			} else {
				$db->query('INSERT INTO cms_user_groups_permissions (groupID, objectID, permission, file, enabled) VALUES ("'.$id.'", "'.$element->uniqueId.'", "0", "'.$page.'", "0")');
			}
		}
		
		foreach($system->dialog->item as $element) {
			$page=str_replace(array("\\", "/"), "@", $element->page);
			if($element->uniqueId!="d_relogin") {
				if(isset($access->{$element->uniqueId})) {
					$db->query('INSERT INTO cms_user_groups_permissions (groupID, objectID, permission, file, enabled) VALUES ("'.$id.'", "'.$element->uniqueId.'", "'.$access->{$element->uniqueId}.'", "'.$page.'", "1")');
				} else {
					$db->query('INSERT INTO cms_user_groups_permissions (groupID, objectID, permission, file, enabled) VALUES ("'.$id.'", "'.$element->uniqueId.'", "0", "'.$page.'", "0")');
				}
			} else {
				$db->query('INSERT INTO cms_user_groups_permissions (groupID, objectID, permission, file, enabled) VALUES ("'.$id.'", "d_relogin", "2", "pages@relogin.php", "1")');
			}
		}
		
		foreach($modules->dialog->item as $element) {
			$page=str_replace(array("\\", "/"), "@", $element->page);
			if(isset($access->{$element->uniqueId})) {
				$db->query('INSERT INTO cms_user_groups_permissions (groupID, objectID, permission, file, enabled) VALUES ("'.$id.'", "'.$element->uniqueId.'", "'.$access->{$element->uniqueId}.'", "'.$page.'", "1")');
			} else {
				$db->query('INSERT INTO cms_user_groups_permissions (groupID, objectID, permission, file, enabled) VALUES ("'.$id.'", "'.$element->uniqueId.'", "0", "'.$page.'", "0")');
			}			
		}
		
		/*Domains*/
		$db->query("DELETE FROM cms_domains_ids WHERE type='group' AND elementID='".$id."'");
		$domains=$db->filter('domains');
		$domains=explode('*/*', $domains);
		foreach($domains as $domain) {
			$db->query("INSERT INTO cms_domains_ids (elementID,domainID,type) VALUES ('".$id."','".$domain."','group')");			
		}
		
		echo 'ok';
		exit;
	} else if($db->filter('type') == 'edit') {
		if($db->is('newpassword')) {
			$newpassword = $db->filter('newpassword');
			$id = $crypt->decrypt($db->filter('id'));
			$result = $db->get($db->query("SELECT username,pass FROM cms_user WHERE ID='".$id."'"));
			if($result) {				
				if(!$valid->isLength($newpassword,6,20)) {
					echo 'password';
					exit;
				}
				$name = $db->filter('name');
				$email = $db->filter('email');
				$group = $db->filter('group');
				$id = $crypt->decrypt($db->filter('id'));
				if(!$valid->isNumber($group)) {
					echo 'group';
					exit;
				}
				$filName = $db->filterVar($name);
				$filEmail = $db->filterVar($email);
				$filPass = $crypt->passwordHash($newpassword,$result['username']);
				$db->query("UPDATE cms_user SET pass='".$filPass."', email='".$filEmail."', GroupID='".$group."', name='".$filName."' WHERE ID='".$id."'");
			} else {
				echo 'id';
				exit;
			}
		} else {
			$group = $db->filter('group');
			$id = $crypt->decrypt($db->filter('id'));
			if(!$valid->isNumber($group)) {
				echo 'group';
				exit;
			}
			
			$filName = $db->filter('name');
			$filEmail = $db->filter('email');
			$db->query("UPDATE cms_user SET email='".$filEmail."',GroupID='".$group."',name='".$filName."' WHERE ID='".$id."'");
		}
		
		$checkResult = $db->get($db->query("SELECT * FROM cms_user_aditional WHERE userID='".$id."'"));			
		if($checkResult) {
			$extraInfo = '';
			$first = true;
			foreach($_POST as $key => $value) {
				if(strpos($key,"_e_x_t_r_a") > -1) {
					if($first) {
						$extraInfo .= str_replace("_e_x_t_r_a","",$key)."='".$db->filterVar($value)."'";
						$first = false;
					} else {
						$extraInfo .= ",".str_replace("_e_x_t_r_a","",$key)."='".$db->filterVar($value)."'";
					}
				}
			}
			if(strlen($extraInfo) > 0) {
				$db->query("UPDATE cms_user_aditional SET ".$extraInfo." WHERE userID='".$id."'");
			}
		} else {
			$extraInfoValues = '';
			$extraInfo = '';
			$first = true;
			foreach($_POST as $key => $value) {
				if(strpos($key,"_e_x_t_r_a") > -1) {
					if($first) {
						$extraInfoValues .= str_replace("_e_x_t_r_a","",$key);
						$extraInfo .= "'".$db->filterVar($value)."'";
						$first = false;
					} else {
						$extraInfoValues .= ','.str_replace("_e_x_t_r_a","",$key);
						$extraInfo .= ",'".$db->filterVar($value)."'";
					}
				}
			}
			if(strlen($extraInfo) > 0) {
				$db->query("INSERT INTO cms_user_aditional (userID,".$extraInfoValues.") VALUES ('".$id."',".$extraInfo.")");
			}			
		}
		echo 'ok';
		exit;
	}
}
?>