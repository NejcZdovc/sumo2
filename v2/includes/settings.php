<? require_once('../initialize.php'); 
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 exit;
	}
	if(ob_get_length()>0) {ob_end_clean();}
	if(isset($_POST['type'])) {
		if($_POST['type'] == 'sumo') {
			$lang=$db->filter('lang');
			$items=$db->filter('items');
			$accordion=$db->filter('panels');
			$preview=$db->filter('preview');
			$update=$db->filter('update');
			$beta=$db->filter('beta');
			$developer=$db->filter('developer');
			$translate_lang=$db->filter('translate_lang');
			$view=$db->filter('view');
			if($translate_lang==-1) {
				$translate_lang=$db->fetch($db->query('SELECT ID FROM cms_language_front WHERE enabled=1'));
				$translate_lang=$translate_lang['ID'];
			}
			$translate_state=$db->filter('translate_state');
			$db->query('UPDATE cms_user_settings SET lang="'.$lang.'", items="'.$items.'", accordion="'.$accordion.'", preview="'.$preview.'",translate_state="'.$translate_state.'", translate_lang="'.$translate_lang.'", updateOption="'.$update.'", view="'.$view.'", beta="'.$beta.'", developer="'.$developer.'" WHERE userID="'.$user->id.'"');
			echo "ok";
			exit;
		}
		else if($_POST['type'] == 'page') {
			$title=$db->filter('title');
			$keyword=$db->filter('keyword');
			$description=$db->filter('description');
			$template=$db->filter('template');
			$email=$db->filter('email');
			$offline=$db->filter('offline');
			$language=$db->filter('language');
			$display_title=$db->filter('display_title');
			$query=$db->fetch($db->query('SELECT keywords, description FROM cms_global_settings'));
			$old_description=$query['description'];
			$old_keywords=$query['keywords'];
			$db->query('UPDATE cms_global_settings SET title="'.$title.'", display_title="'.$display_title.'", email="'.$email.'", keywords="'.$keyword.'", description="'.$description.'", template="'.$template.'", offline="'.$offline.'", front_lang="'.$language.'" WHERE domain="'.$user->domain.'"');
			$query=$db->query('SELECT ID, keyword, description FROM cms_menus_items WHERE keyword="'.$old_keywords.'" OR description="'.$old_description.'"');
			while($result=$db->fetch($query)) {
				if($result['keyword'] == $old_keywords && $result['description'] == $old_description)
					$db->query('UPDATE cms_menus_items SET keyword="'.$keyword.'", description="'.$description.'" WHERE ID='.$result['ID'].'');
				else if($result['keyword'] == $old_keywords)
					$db->query('UPDATE cms_menus_items SET keyword="'.$keyword.'" WHERE ID='.$result['ID'].'');
				else if($result['description'] == $old_description)
					$db->query('UPDATE cms_menus_items SET description="'.$description.'" WHERE ID='.$result['ID'].'');
									
			}
			echo "ok";
			exit;
		}
		else if($_POST['type'] == 'global') {
			$GA_ID=$db->filter('GA_ID');
			$GA_type=$db->filter('GA_type');
			$WM_ID=$db->filter('WM_ID');
			$db->query('UPDATE cms_global_settings SET GA_ID="'.$GA_ID.'", GA_type="'.$GA_type.'", WM_ID="'.$WM_ID.'" WHERE domain="'.$user->domain.'"');
			echo "ok";
			exit;
		}
		else if($_POST['type'] == 'welcome') {
			$content=$db->filter('content');
			$db->query('UPDATE cms_sumo_settings SET welcome="'.$content.'" WHERE ID="'.$user->domain.'"');
			echo "ok";
			exit;
		}
		else if($_POST['type'] == 'addt') {
			$name=$db->filter('name');
			$number=$db->filter('number');
			$newname=strtolower(str_replace(' ', '_', $name));
			if(!file_exists('../../templates/'.$user->domainName.'/'.$newname)) {
				mkdir('../../templates/'.$user->domainName.'/'.$newname, 0777);
				chmod('../../templates/'.$user->domainName.'/'.$newname, 0777);
			}
			$zip = new ZipArchive;
			if ($zip->open('../temp/'.$number.'.zip') === TRUE) {
				$zip->extractTo('../../templates/'.$user->domainName.'/'.$newname.'/');
				if($zip->close()) {
					unlink('../temp/'.$number.'.zip');
					$files = scandir('../../templates/'.$user->domainName.'/'.$newname.'/');
					chmodAll('../../templates/'.$user->domainName.'/'.$newname.'/',0777,0777);
					foreach ($files as &$value) {
						if(is_dir('../../templates/'.$user->domainName.'/'.$newname.'/'.$value) && ($value == "images" || $value == "img")) {
								setImages('../../templates/'.$user->domainName.'/'.$newname.'/'.$value.'/',$number);
								recursive_remove_directory('../../templates/'.$user->domainName.'/'.$newname.'/'.$value);
						}
					}
					$db->query('INSERT INTO cms_template (name,folder, domain) VALUES ("'.$name.'", "'.$newname.'", "'.$user->domain.'")');
					echo 'ok';
				}
			}
			exit;
		}
		else if($_POST['type'] == 'addlb') {
			$name=$db->filter('name');
			$number=$db->filter('number');
			$short=$db->filter('short');
			if(!file_exists('../language/'.$short, 0777)) {
				mkdir('../language/'.$short, 0777, true);
				chmod('../language/'.$short, 0777);
			}
			$zip = new ZipArchive;
			if ($zip->open('../temp/'.$number.'.zip') === TRUE) {
				$zip->extractTo('../language/'.$short.'/');
				if($zip->close()) {
					unlink('../temp/'.$number.'.zip');
					$files = scandir('../language/'.$short.'/');
					foreach ($files as &$value) {
						chmod('../language/'.$short.'/'.$value, 0777);
					}
					$db->query('INSERT INTO cms_language (name,short) VALUES ("'.$name.'", "'.$short.'")');
					echo 'ok';
				}
			}
			exit;
		}
		else if($_POST['type'] == 'addlf') {
			$name= $db->filter('name');
			$short= $db->filter('short');
			$db->query("INSERT INTO cms_language_front (name,short) VALUES ('".$name."', '".$short."')");			
			echo 'ok';
			exit;
		}
		else if($_POST['type'] == 'statust') {
			$id = $crypt->decrypt($_POST['id']);
			$query = $db->query("SELECT enabled FROM cms_template WHERE ID='".$id."'");
			$result = $db->get($query);
			if($result) {
				if($result['enabled'] == 0) {
					$new = 1;
				} else {
					$new = 0;
				}
				$db->query("UPDATE cms_template SET enabled='".$new."' WHERE ID='".$id."'");
				echo 'ok';
				exit;
			}
		}
		else if($_POST['type'] == 'editt') {
			$id = $crypt->decrypt($_POST['id']);
			$name= $db->filter('name');
			$db->query("UPDATE cms_template SET name='".$name."' WHERE ID='".$id."'");
			echo 'ok';
			exit;
		}
		else if($_POST['type'] == 'deletet') {
			$id = $crypt->decrypt($_POST['id']);
			$db->query("UPDATE cms_template SET status='D' WHERE ID='".$id."'");
			echo 'ok';
			exit;
		}
		else if($_POST['type'] == 'deletetP') {
			$id = $crypt->decrypt($_POST['id']);
			$name=$db->get($db->query('SELECT prefix FROM cms_template_position WHERE ID="'.$id.'"'));
			$db->query("DELETE FROM cms_template_position WHERE ID='".$id."'");
			$db->query("DROP TABLE `cms_panel_".$name['prefix']."`");
			$query=$db->query('SELECT editTable FROM cms_modules_def');
			while($result=$db->fetch($query)) {
				$query1=$db->query('SELECT ID FROM '.$result['editTable'].' WHERE cms_layout="'.$name['prefix'].'"');
				while($result1=$db->fetch($query1)) {
					$db->query("DELETE FROM ".$result['editTable']." WHERE ID='".$result1['ID']."'");
				}
			}
			echo 'ok';
			exit;
		}
		else if($_POST['type'] == 'deleteP') {
			$id = $crypt->decrypt($_POST['id']);
			$name=$db->get($db->query('SELECT prefix FROM cms_modul_prefix WHERE ID="'.$id.'"'));
			$db->query("DELETE FROM cms_modul_prefix WHERE ID='".$id."'");
			$query=$db->query('SELECT prefix FROM cms_template_position');
			while($result=$db->fetch($query)) {
				$query1=$db->query('SELECT ID FROM cms_panel_'.$result['prefix'].' WHERE prefix="'.$name['prefix'].'"');
				while($result1=$db->fetch($query1)) {
					$db->query("DELETE FROM cms_panel_".$result['prefix']." WHERE ID='".$result1['ID']."'");
				}
			}
			echo 'ok';
			exit;
		}
		else if($_POST['type'] == 'statuslf') {
			$id = $crypt->decrypt($_POST['id']);
			$query = $db->query("SELECT enabled FROM cms_language_front WHERE ID='".$id."'");
			$result = $db->get($query);
			if($result) {
				if($result['enabled'] == 0) {
					$new = 1;
				} else {
					$new = 0;
				}
				$db->query("UPDATE cms_language_front SET enabled='".$new."' WHERE ID='".$id."'");
				echo 'ok';
				exit;
			}
		}
		else if($_POST['type'] == 'statuslb') {
			$id = $crypt->decrypt($_POST['id']);
			$query = $db->query("SELECT enabled FROM cms_language WHERE ID='".$id."'");
			$result = $db->get($query);
			if($result) {
				if($result['enabled'] == 0) {
					$new = 1;
				} else {
					$new = 0;
				}
				$db->query("UPDATE cms_language SET enabled='".$new."' WHERE ID='".$id."'");
				echo 'ok';
				exit;
			}
		}
		else if($_POST['type'] == 'gastat') {
			$query = $db->query("SELECT GA_enabled FROM cms_global_settings WHERE ID='".$user->domain."'");
			$result = $db->get($query);
			if($result) {
				if($result['GA_enabled'] == 0) {
					$new = 1;
				} else {
					$new = 0;
				}
				$db->query("UPDATE cms_global_settings SET GA_enabled='".$new."' WHERE ID='".$user->domain."'");
				echo 'ok';
				exit;
			}
		}
		else if($_POST['type'] == 'wmstat') {
			$query = $db->query("SELECT WM_enabled FROM cms_global_settings WHERE domain='".$user->domain."'");
			$result = $db->get($query);
			if($result) {
				if($result['WM_enabled'] == 0) {
					$new = 1;
				} else {
					$new = 0;
				}
				$db->query("UPDATE cms_global_settings SET WM_enabled='".$new."' WHERE ID='".$user->domain."'");
				echo 'ok';
				exit;
			}
		}
		else if($_POST['type'] == 'error') {
			$file = "../logs/error.log";
			$fh = fopen($file, 'w');
			$string="Error log cleaned (".date("d.m.Y H:m", time()).")\r\n\n";
			fwrite($fh, $string);
			fclose($fh);
		}
		else if($_POST['type'] == 'errorFront') {
			$file = "../logs/errorFront.log";
			$fh = fopen($file, 'w');
			$string="Error log cleaned (".date("d.m.Y H:m", time()).")\r\n\n";
			fwrite($fh, $string);
			fclose($fh);
		}
		else if($_POST['type'] == "checkp") {
			$name=$db->filter('name');
			$query = $db->query("SELECT ID FROM cms_modul_prefix WHERE name='".$name."'");
			$int=$db->rows($query);
			if($int > 0)
				echo 'error';
			else
				echo 'ok';
			exit;
		}
		else if($_POST['type'] == "saveprefix") {
			$name=$db->filter('name');
			$array=explode('##', $name);
			if(count($array)>1) {				
				for($i=0; $i<count($array); $i++) {
					if($array[$i] != '' && $array[$i] != ' ') {
						$prefix=strtolower(str_replace(' ', '_', $array[$i]));
						$db->query('INSERT INTO cms_modul_prefix (name, prefix, domain) VALUES ("'.$array[$i].'", "'.$prefix.'", "'.$user->domain.'")');
					}
				}
			}
			else {
				$prefix=strtolower(str_replace(' ', '_', $name));
				$db->query('INSERT INTO cms_modul_prefix (name, prefix, domain) VALUES ("'.$name.'", "'.$prefix.'", "'.$user->domain.'")');
			}
			echo "ok";
			exit;
		}
		else if($_POST['type'] == 'prefstat') {
			$id = $crypt->decrypt($_POST['id']);
			$query = $db->query("SELECT enabled FROM cms_modul_prefix WHERE ID='".$id."'");
			$result = $db->get($query);
			if($result) {
				if($result['enabled'] == 0) {
					$new = 1;
				} else {
					$new = 0;
				}
				$db->query("UPDATE cms_modul_prefix SET enabled='".$new."' WHERE ID='".$id."'");
				echo 'ok';
				exit;
			}
		}
		else if($_POST['type'] == "checktp") {
			$name=$db->filter('name');
			$query = $db->query("SELECT ID FROM cms_template_position WHERE name='".$name."'");
			$int=$db->rows($query);
			if($int > 0)
				echo 'error';
			else
				echo 'ok';
			exit;
		}
		else if($_POST['type'] == "saveposition") {
			$name=$db->filter('name');
			$array=explode('##', $name);
			if(count($array)>1) {
				for($i=0; $i<count($array); $i++) {
					if($array[$i] != '') {
						$prefix=strtolower(str_replace(' ', '_', $array[$i]));
						$sql_name="cms_panel_sumo_".$prefix;
						$sql = "CREATE TABLE IF NOT EXISTS ".$sql_name." 
						(
							ID int(11) NOT NULL AUTO_INCREMENT,
							PRIMARY KEY(ID),
							modulID int(11) NOT NULL,
							orderID int(5) NOT NULL,
							enabled tinyint(1) NOT NULL default '1',
							pageID int(11) NOT NULL,
							lang int(2) NOT NULL default '1',
							title varchar(50) NOT NULL,
							prefix varchar(100) NOT NULL,
							table_name varchar(100) NOT NULL,
							cache int(11) NOT NULL default '-1',
							specialPage int(2) NOT NULL default '0',
							copyModul int(2) NOT NULL default '0',
							domain int(11) NOT NULL
						)";
						$db->query($sql);
						$db->query('INSERT INTO cms_template_position (name, prefix, domain) VALUES ("'.$array[$i].'", "sumo_'.$prefix.'", "'.$user->domain.'")');
					}
				}
			}
			else {
				$prefix=strtolower(str_replace(' ', '_', $name));
				$sql_name="cms_panel_sumo_".$prefix;
				$sql = "CREATE TABLE IF NOT EXISTS ".$sql_name."
				(
					ID int(11) NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(ID),
					modulID int(11) NOT NULL,
					orderID int(5) NOT NULL,
					enabled tinyint(1) NOT NULL default '1',
					pageID int(11) NOT NULL,
					lang int(2) NOT NULL default '1',
					title varchar(50) NOT NULL,
					prefix varchar(100) NOT NULL,
					table_name varchar(100) NOT NULL,
					cache int(11) NOT NULL default '-1',
					specialPage int(2) NOT NULL default '0',
					copyModul int(2) NOT NULL default '0',
					domain int(11) NOT NULL
				)";
				$db->query($sql);
				$db->query('INSERT INTO cms_template_position (name, prefix, domain) VALUES ("'.$name.'", "sumo_'.$prefix.'", "'.$user->domain.'")');
			}
			echo "ok";
			exit;
		}
		else if($_POST['type'] == "saveFTP") {
			$user=$db->filter('user');
			$pass=$db->filter('password');
			$url=$db->filter('url');
			$port=$db->filter('port');
			$ftpConn = ftp_connect($url, $port);			
			if (!$ftpConn) {
				echo $lang->MOD_128." $ftpServer";
				exit;
			}
			
			if (@ftp_login($ftpConn, $user, $pass)){
				$db->query('UPDATE cms_sumo_settings SET FTP_user="'.$crypt->encrypt($user).'", FTP_pass="'.$crypt->encrypt($pass).'", FTP_url="'.$url.'", FTP_port="'.$port.'" WHERE ID="1"');
				ftp_close($ftpConn);	
				echo "ok";
				exit;
			}
			else {
				ftp_close($ftpConn);
				echo $lang->MOD_129." $user";					
				exit;
			}
		}
		else if($_POST['type'] == "changeChacheNumber") {
			$number=$db->get($db->query('SELECT cacheNumber, ID FROM cms_global_settings WHERE domain="'.$user->domain.'"'));
			$db->query('UPDATE cms_global_settings SET cacheNumber="'.($number['cacheNumber']+1).'" WHERE ID="'.$number['ID'].'"');
			
			echo "ok";
			exit;
		}
	}
?>