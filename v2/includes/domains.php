<?	require_once('../initialize.php');
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 exit;
	}
if(ob_get_length()>0) { ob_end_clean(); }
if(isset($_POST['type'])) {
	if($db->filter('type') == 'add') {
		$name = $db->filter('name');
		$exist=$db->rows($db->query('SELECT ID FROM cms_domains WHERE name="'.$name.'"'));
		if($exist>0) {
			echo "exist";
			exit;
		}
			
		$alias = $db->filter('alias');
		$main = $db->filter('main');
		$language = $db->filter('language');
		$white = $db->filter('white');
		$black = $db->filter('black');
		$iplocator = $db->filter('iplocator');
		$countries = $db->filter('countries');
				
		$db->query("INSERT INTO cms_domains (name, parentID, locator) VALUES ('".$name."', '".$main."', '".$iplocator."')");
		$id=$db->getLastId();
		
		//language
		$languages=explode("*/*", $language);
		foreach($languages as $language) {
			if(strlen($language)>1) {
				$db->query("INSERT INTO cms_domains_ids (domainID, value, type)  VALUES	('".$id."', '".$language."', 'lang')");
				$lang=$db->get($db->query('SELECT ID FROM cms_language_front WHERE short="'.$language.'" LIMIT 1'));
				$db->query("INSERT INTO cms_homepage (title,lang,selection, domain) VALUES ('Home - ".$name."', '".$lang['ID']."', 1, '".$id."')");
				$db->query("INSERT INTO cms_menus_items (link,title,menuID,status,parentID,orderID,template,selection,target) VALUES ('".$db->getLastId()."', 'Home - ".$name."', '-1','DD','-1','-1','-1','-1','-1')"); 
			}
		}		
		
		//white ip
		$ips=explode("*/*", $white);
		foreach($ips as $ip) {
			if(strlen($ip)>2)
				$db->query("INSERT INTO cms_domains_ips (domainID, value, type)  VALUES	('".$id."', '".$ip."', '1')");
		}
		
		//balck ip
		$ips=explode("*/*", $black);
		foreach($ips as $ip) {
			if(strlen($ip)>2)
				$db->query("INSERT INTO cms_domains_ips (domainID, value, type)  VALUES	('".$id."', '".$ip."', '0')");
		}
		
		//countries
		$countries=explode("*/*", $countries);
		foreach($countries as $country) {
			if(strlen($country)>1)
				$db->query("INSERT INTO cms_domains_countries (domainID, value)  VALUES	('".$id."', '".$country."')");
		}
		
		//alias
		$alias=explode("*/*", $alias);
		foreach($alias as $alia) {
			if(strlen($alia)>3)
				$db->query("INSERT INTO cms_domains (name, parentID, alias) VALUES ('".$alia."', '".$id."', '1')");
		}
		//template
		mkdir('../../templates/'.$name.'/', 0777);
		chmod('../../templates/'.$name.'/', 0777);
		mkdir('../../templates/'.$name.'/images', 0777);
		chmod('../../templates/'.$name.'/images', 0777);
		
		//module
		mkdir('../../modules/'.$name, 0777);
		chmod('../../modules/'.$name, 0777);
		mkdir('../../modules/'.$name.'/images', 0777);
		chmod('../../modules/'.$name.'/images', 0777);
		
		//off
		mkdir('../../off/'.$name, 0777);
		chmod('../../off/'.$name, 0777);
		copyFiles('../../off/default', '../../off/'.$name);
		
		//block
		mkdir('../../block/'.$name, 0777);
		chmod('../../block/'.$name, 0777);
		copyFiles('../../block/default', '../../block/'.$name);
		
		//block
		mkdir('../../404/'.$name, 0777);
		chmod('../../404/'.$name, 0777);
		copyFiles('../../404/default', '../../404/'.$name);
		
		//images
		mkdir('../../images/'.$name, 0777);
		chmod('../../images/'.$name, 0777);
		mkdir('../../images/'.$name.'/article', 0777);
		chmod('../../images/'.$name.'/article', 0777);
		
		//storage
		mkdir('../../storage/'.$name, 0777);
		chmod('../../storage/'.$name, 0777);
		mkdir('../../storage/'.$name.'/Documents', 0777);
		chmod('../../storage/'.$name.'/Documents', 0777);
		mkdir('../../storage/'.$name.'/Flash', 0777);
		chmod('../../storage/'.$name.'/Flash', 0777);
		mkdir('../../storage/'.$name.'/Images', 0777);
		chmod('../../storage/'.$name.'/Images', 0777);
		mkdir('../../storage/'.$name.'/Documents/Pdf', 0777);
		chmod('../../storage/'.$name.'/Documents/Pdf', 0777);
		mkdir('../../storage/'.$name.'/Documents/Word', 0777);
		chmod('../../storage/'.$name.'/Documents/Word', 0777);
		
		
		//global settings
		$db->query("INSERT INTO cms_global_settings (domain, title, display_title) VALUES ('".$id."', 'Website', 'D')");
		
		//add domain to super admin group
		$db->query("INSERT INTO cms_domains_ids (type, domainID, elementID) VALUES ('group', '".$id."', '1')");
		
		echo 'ok';
		exit;
	} else if($db->filter('type') == 'edit') {
		$id = $db->filter('id');
		$alias = $db->filter('alias');
		$main = $db->filter('main');
		$language = $db->filter('language');
		$white = $db->filter('white');
		$black = $db->filter('black');
		$iplocator = $db->filter('iplocator');
		$countries = $db->filter('countries');
				
		$db->query("UPDATE cms_domains SET parentID='".$main."', locator='".$iplocator."' WHERE ID='".$id."'");

		//language
		$db->query('DELETE FROM cms_domains_ids WHERE type="lang" AND domainID="'.$id.'"');
		$languages=explode("*/*", $language);
		foreach($languages as $language) {
			if(strlen($language)>1) {
				$lang=$db->get($db->query('SELECT ID FROM cms_language_front WHERE short="'.$language.'" LIMIT 1'));
				$num=$db->rows($db->query('SELECT ID FROM cms_homepage WHERE domain="'.$id.'" AND lang="'.$lang['ID'].'" AND selection="1"'));
				if($num==0) {				
					$db->query("INSERT INTO cms_homepage (title,lang,selection, domain) VALUES ('Home - ".$language."', '".$lang['ID']."', 1, '".$id."')");
					$db->query("INSERT INTO cms_menus_items (link,title,menuID,status,parentID,orderID,template,selection,target) VALUES ('".$db->getLastId()."', 'Home - ".$language."', '-1','DD','-1','-1','-1','-1','-1')"); 
				} 
				$db->query("INSERT INTO cms_domains_ids (domainID, value, type)  VALUES	('".$id."', '".$language."', 'lang')");	
			}
		}	
		
		//white ip
		$db->query("DELETE FROM cms_domains_ips WHERE domainID='".$id."'");
		$ips=explode("*/*", $white);
		foreach($ips as $ip) {
			if(strlen($ip)>2)
				$db->query("INSERT INTO cms_domains_ips (domainID, value, type)  VALUES	('".$id."', '".$ip."', '1')");
		}
		
		//balck ip
		$ips=explode("*/*", $black);
		foreach($ips as $ip) {
			if(strlen($ip)>2)
				$db->query("INSERT INTO cms_domains_ips (domainID, value, type)  VALUES	('".$id."', '".$ip."', '0')");
		}
		
		//countries
		$db->query("DELETE FROM cms_domains_countries WHERE domainID='".$id."'");
		$countries=explode("*/*", $countries);
		foreach($countries as $country) {
			if(strlen($country)>1)
				$db->query("INSERT INTO cms_domains_countries (domainID, value)  VALUES	('".$id."', '".$country."')");
		}
		
		//alias
		$db->query("DELETE FROM cms_domains WHERE parentID='".$id."' AND alias='1'");
		$alias=explode("*/*", $alias);
		foreach($alias as $alia) {
			if(strlen($alia)>2)
				$db->query("INSERT INTO cms_domains (name, parentID, alias) VALUES ('".$alia."', '".$id."', '1')");
		}
		echo 'ok';
		exit;
	} else if($db->filter('type')=="saveSelection") {
		$id=$crypt->decrypt($db->filter('id'));
		$domains=$db->get($db->query('SELECT value FROM cms_domains_ids WHERE type="lang" AND domainID="'.$id.'" LIMIT 1'));
		$lang=$db->get($db->query('SELECT ID FROM cms_language_front WHERE short="'.$domains['value'].'" LIMIT 1'));
		$db->query('UPDATE cms_user_settings SET domain="'.$id.'", translate_lang="'.$lang['ID'].'" WHERE userID="'.$user->id.'"');
		echo 'ok';
		exit;
	} else if($db->filter('type')=="delete") {
		$id=$db->filter('id');		
	}
}
?>